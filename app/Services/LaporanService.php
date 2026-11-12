<?php

namespace App\Services;

use App\Models\AnakAsuh;
use App\Models\Berita;
use App\Models\Donasi;
use App\Models\Kampanye;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class LaporanService
{
    /**
     * Get summary metrics for admin dashboard.
     */
    public function getDashboardSummary(): array
    {
        return [
            'total_donasi_bulan_ini' => (float) Donasi::where('status', 'verified')
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->sum('nominal'),
            'total_anak_asuh_aktif' => AnakAsuh::where('status_asuhan', 'Aktif')->count(),
            'kampanye_aktif' => Kampanye::where('status', 'aktif')->count(),
            'total_donasi_pending' => Donasi::where('status', 'pending')->count(),
            'total_donasi_terverifikasi' => (float) Donasi::where('status', 'verified')->sum('nominal'),
            'total_transaksi_verified' => Donasi::where('status', 'verified')->count(),
        ];
    }

    /**
     * Get counts for filter tabs in dashboard.
     */
    public function getCountByMetode(?string $tanggal = null): array
    {
        $base = Donasi::where('status', 'pending');

        if ($tanggal) {
            $base->whereDate('created_at', Carbon::parse($tanggal));
        }

        $all = (clone $base)->count();
        $transfer = (clone $base)->where(function ($q) {
            $q->where('metode_pembayaran', 'like', '%Transfer%')
                ->orWhere('metode_pembayaran', 'like', '%Bank%');
        })->count();
        $qris = (clone $base)->where('metode_pembayaran', 'like', '%QRIS%')->count();

        return [
            'semua' => $all,
            'transfer_bank' => $transfer,
            'qris_manual' => $qris,
        ];
    }

    /**
     * Get pending donations with search, method, and date filters.
     */
    public function getPendingDonasi(
        ?string $search = null,
        ?string $metode = null,
        ?string $tanggal = null,
        int $perPage = 5
    ): LengthAwarePaginator {
        $query = Donasi::with('kampanye')->where('status', 'pending');

        if ($tanggal) {
            $query->whereDate('created_at', Carbon::parse($tanggal));
        }

        if ($metode && $metode !== 'all') {
            if ($metode === 'transfer_bank') {
                $query->where(function ($q) {
                    $q->where('metode_pembayaran', 'like', '%Transfer%')
                        ->orWhere('metode_pembayaran', 'like', '%Bank%');
                });
            } elseif ($metode === 'qris_manual') {
                $query->where('metode_pembayaran', 'like', '%QRIS%');
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('nama_donatur', 'like', "%{$search}%")
                    ->orWhere('no_whatsapp', 'like', "%{$search}%")
                    ->orWhereHas('kampanye', function ($kq) use ($search) {
                        $kq->where('judul', 'like', "%{$search}%");
                    });
            });
        }

        return $query->latest('id')->paginate($perPage);
    }

    /**
     * Filter verified donations for reporting and export.
     */
    public function getLaporanDonasi(?string $startDate = null, ?string $endDate = null, ?int $kampanyeId = null)
    {
        $query = Donasi::with('kampanye')->where('status', 'verified');

        if ($startDate) {
            $query->whereDate('created_at', '>=', Carbon::parse($startDate));
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', Carbon::parse($endDate));
        }

        if ($kampanyeId) {
            $query->where('kampanye_id', $kampanyeId);
        }

        return $query->latest()->get();
    }

    /**
     * Data grafik pertumbuhan donasi berdasarkan rentang waktu (6 Bulan, 30 Hari, 1 Tahun).
     */
    public function getDonasiChartData(): array
    {
        // 1. Data 6 Bulan Terakhir
        $categories6m = [];
        $nominal6m = [];
        $donatur6m = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $categories6m[] = $month->translatedFormat('M Y');

            $stats = Donasi::where('status', 'verified')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->selectRaw('COALESCE(SUM(nominal), 0) as total_nominal, COUNT(*) as total_donatur')
                ->first();

            $nominal6m[] = (float) ($stats->total_nominal ?? 0);
            $donatur6m[] = (int) ($stats->total_donatur ?? 0);
        }

        // 2. Data 30 Hari Terakhir (Dikelompokkan per 5 hari = 6 titik)
        $categories30d = [];
        $nominal30d = [];
        $donatur30d = [];

        for ($i = 5; $i >= 0; $i--) {
            $endDays = $i * 5;
            $startDays = $endDays + 4;
            $startDate = Carbon::now()->subDays($startDays)->startOfDay();
            $endDate = Carbon::now()->subDays($endDays)->endOfDay();

            $categories30d[] = $startDate->translatedFormat('d M') . ' - ' . $endDate->translatedFormat('d M');

            $stats = Donasi::where('status', 'verified')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('COALESCE(SUM(nominal), 0) as total_nominal, COUNT(*) as total_donatur')
                ->first();

            $nominal30d[] = (float) ($stats->total_nominal ?? 0);
            $donatur30d[] = (int) ($stats->total_donatur ?? 0);
        }

        // 3. Data Tahun Berjalan (12 Bulan)
        $categories1y = [];
        $nominal1y = [];
        $donatur1y = [];
        $currentYear = Carbon::now()->year;

        for ($m = 1; $m <= 12; $m++) {
            $date = Carbon::createFromDate($currentYear, $m, 1);
            $categories1y[] = $date->translatedFormat('M');

            $stats = Donasi::where('status', 'verified')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $m)
                ->selectRaw('COALESCE(SUM(nominal), 0) as total_nominal, COUNT(*) as total_donatur')
                ->first();

            $nominal1y[] = (float) ($stats->total_nominal ?? 0);
            $donatur1y[] = (int) ($stats->total_donatur ?? 0);
        }

        return [
            '6m' => [
                'label'         => '6 Bulan Terakhir',
                'categories'    => $categories6m,
                'nominal'       => $nominal6m,
                'donatur'       => $donatur6m,
                'total_nominal' => array_sum($nominal6m),
                'total_donatur' => array_sum($donatur6m),
            ],
            '30d' => [
                'label'         => '30 Hari Terakhir',
                'categories'    => $categories30d,
                'nominal'       => $nominal30d,
                'donatur'       => $donatur30d,
                'total_nominal' => array_sum($nominal30d),
                'total_donatur' => array_sum($donatur30d),
            ],
            '1y' => [
                'label'         => 'Tahun ' . $currentYear,
                'categories'    => $categories1y,
                'nominal'       => $nominal1y,
                'donatur'       => $donatur1y,
                'total_nominal' => array_sum($nominal1y),
                'total_donatur' => array_sum($donatur1y),
            ],
        ];
    }

    /**
     * Data distribusi program/kategori donasi untuk Donut Chart.
     */
    public function getDonasiDistribusiData(): array
    {
        $programs = Donasi::join('kampanye', 'donasi.kampanye_id', '=', 'kampanye.id')
            ->where('donasi.status', 'verified')
            ->selectRaw('kampanye.judul, sum(donasi.nominal) as total_nominal, count(donasi.id) as total_donasi')
            ->groupBy('kampanye.id', 'kampanye.judul')
            ->orderByDesc('total_nominal')
            ->get();

        $totalNominal = (float) $programs->sum('total_nominal');
        if ($totalNominal <= 0) {
            $totalNominal = 1; // cegah division by zero
        }

        $labels = [];
        $series = [];
        $percentages = [];
        // Palet warna kontras & representatif: hijau, biru, oranye, kuning, ungu, teal
        $colors = ['#16a34a', '#2563eb', '#ea580c', '#eab308', '#8b5cf6', '#0d9488'];

        // Ambil top 4 program terbesar, sisanya dikelompokkan ke "Program Lainnya"
        $top4 = $programs->take(4);
        foreach ($top4 as $p) {
            $nom = (float) $p->total_nominal;
            $labels[] = Str::limit($p->judul, 22);
            $series[] = (float) round($nom, 2);
            $percentages[] = (float) round(($nom / $totalNominal) * 100, 1);
        }

        if ($programs->count() > 4) {
            $otherNominal = (float) $programs->slice(4)->sum('total_nominal');
            if ($otherNominal > 0) {
                $labels[] = 'Program Lainnya';
                $series[] = (float) round($otherNominal, 2);
                $percentages[] = (float) round(($otherNominal / $totalNominal) * 100, 1);
            }
        }

        // Fallback jika belum ada data donasi terverifikasi
        if (empty($series)) {
            $labels = ['Belum Ada Data'];
            $series = [100.0];
            $percentages = [100.0];
            $colors = ['#94a3b8'];
        }

        return [
            'labels'        => $labels,
            'series'        => $series,
            'percentages'   => $percentages,
            'colors'        => array_slice($colors, 0, count($series)),
            'total_nominal' => (float) $programs->sum('total_nominal'),
            'total_donasi'  => (int) $programs->sum('total_donasi'),
        ];
    }

    /**
     * Mengambil daftar aktivitas terbaru lintas modul (Donasi pending, Doa donatur, Konten).
     */
    public function getRecentActivities(int $limit = 7): array
    {
        try {
            $activities = [];

            // 1. Donasi Pending yang Butuh Verifikasi
            if (\Illuminate\Support\Facades\Schema::hasTable('donasi')) {
                $pendingDonations = Donasi::with('kampanye')
                    ->where('status', 'pending')
                    ->latest('id')
                    ->take(4)
                    ->get();

                foreach ($pendingDonations as $d) {
                    $activities[] = [
                        'id'          => 'donasi-' . $d->id,
                        'type'        => 'donasi',
                        'title'       => 'Donasi Baru Masuk',
                        'desc'        => 'Rp' . number_format($d->nominal, 0, ',', '.') . ' dari ' . $d->nama_donatur . ' (' . $d->metode_pembayaran . ')',
                        'badge'       => 'Perlu Verifikasi',
                        'badge_color' => 'amber',
                        'icon'        => 'wallet',
                        'time_ago'    => $d->created_at ? $d->created_at->diffForHumans() : 'Baru saja',
                        'timestamp'   => $d->created_at ? $d->created_at->timestamp : 0,
                        'url'         => route('admin.kelola-donasi.index', ['search' => $d->nama_donatur]),
                    ];
                }

                // 2. Pesan & Doa Donatur Terbaru
                $prayers = Donasi::whereNotNull('pesan_doa')
                    ->where('pesan_doa', '!=', '')
                    ->latest('id')
                    ->take(3)
                    ->get();

                foreach ($prayers as $d) {
                    $activities[] = [
                        'id'          => 'doa-' . $d->id,
                        'type'        => 'pesan',
                        'title'       => 'Pesan & Doa Donatur',
                        'desc'        => '“' . Str::limit($d->pesan_doa, 65) . '” — ' . $d->nama_donatur,
                        'badge'       => 'Doa Santri',
                        'badge_color' => 'emerald',
                        'icon'        => 'heart',
                        'time_ago'    => $d->created_at ? $d->created_at->diffForHumans() : 'Baru saja',
                        'timestamp'   => $d->created_at ? $d->created_at->timestamp : 0,
                        'url'         => route('admin.kelola-donasi.index', ['search' => $d->nama_donatur]),
                    ];
                }
            }

            // 3. Aktivitas Data Anak Asuh
            if (\Illuminate\Support\Facades\Schema::hasTable('anak_asuh')) {
                $latestAnakAsuh = AnakAsuh::latest('updated_at')->take(2)->get();
                foreach ($latestAnakAsuh as $a) {
                    $activities[] = [
                        'id'          => 'anak-' . $a->id,
                        'type'        => 'konten',
                        'title'       => 'Data Anak Asuh',
                        'desc'        => 'Pembaruan data santri binaan jenjang ' . $a->pendidikan_terakhir,
                        'badge'       => 'Anak Asuh',
                        'badge_color' => 'blue',
                        'icon'        => 'user',
                        'time_ago'    => $a->updated_at ? $a->updated_at->diffForHumans() : 'Baru saja',
                        'timestamp'   => $a->updated_at ? $a->updated_at->timestamp : 0,
                        'url'         => route('admin.anak-asuh.index'),
                    ];
                }
            }

            // 4. Aktivitas CMS Berita
            if (\Illuminate\Support\Facades\Schema::hasTable('berita')) {
                $latestBerita = Berita::latest('updated_at')->take(2)->get();
                foreach ($latestBerita as $b) {
                    $activities[] = [
                        'id'          => 'berita-' . $b->id,
                        'type'        => 'konten',
                        'title'       => 'Aktivitas Berita',
                        'desc'        => 'Artikel: ' . Str::limit($b->judul, 45) . ' (' . ($b->status ?? 'Draft') . ')',
                        'badge'       => 'CMS Berita',
                        'badge_color' => 'purple',
                        'icon'        => 'newspaper',
                        'time_ago'    => $b->updated_at ? $b->updated_at->diffForHumans() : 'Baru saja',
                        'timestamp'   => $b->updated_at ? $b->updated_at->timestamp : 0,
                        'url'         => route('admin.berita.index'),
                    ];
                }
            }

            // Urutkan berdasarkan timestamp terbaru
            usort($activities, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);

            return array_slice($activities, 0, $limit);
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Ringkasan notifikasi untuk Topbar Notification Dropdown.
     */
    public function getNotifications(int $limit = 6): array
    {
        try {
            $activities = $this->getRecentActivities($limit);
            $unreadCount = \Illuminate\Support\Facades\Schema::hasTable('donasi') 
                ? Donasi::where('status', 'pending')->count() 
                : 0;

            return [
                'unread_count' => $unreadCount,
                'items'        => $activities,
            ];
        } catch (\Throwable $e) {
            return [
                'unread_count' => 0,
                'items'        => [],
            ];
        }
    }
}
