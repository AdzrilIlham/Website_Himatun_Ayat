<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AnakAsuh;
use App\Models\BeritaKegiatan;
use App\Models\Donasi;
use App\Models\Kampanye;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LandingPageController extends Controller
{
    /**
     * Menampilkan halaman landing page utama untuk user / publik.
     */
    public function index()
    {
        $kampanyeAktif = Kampanye::query()
            ->where('status', 'aktif')
            ->withCount(['donasi as donatur_count' => fn (Builder $query): Builder => $query->where('status', 'verified')])
            ->orderByDesc('id')
            ->take(4)
            ->get();

        $programUtama = $kampanyeAktif->first();
        $paketKilat = $kampanyeAktif->slice(1, 3)->values();

        $beritaTerbaru = $this->beritaTerbaru();
        $kegiatanMendatang = $this->kegiatanMendatang();

        return view('user.landing-page.index', [
            'impactStats' => $this->impactStats(),
            'recentDonors' => $this->recentDonors(),
            'programUtama' => $programUtama ? $this->formatKampanye($programUtama, 260) : null,
            'paketKilat' => $paketKilat->map(fn (Kampanye $kampanye): array => $this->formatKampanye($kampanye, 150)),
            'beritaTerbaru' => $beritaTerbaru,
            'kategoriBerita' => $beritaTerbaru->pluck('kategori')->unique()->values(),
            'kegiatanMendatang' => $kegiatanMendatang,
            'title' => 'Yayasan Himmatun Ayat Bandung - Mengukir Senyum dan Masa Depan Anak Yatim',
        ]);
    }

    /**
     * Statistik dampak yayasan untuk section statistik di landing page.
     *
     * @return array{
     *     tahun_akumulasi: int,
     *     total_dana_nominal: float,
     *     total_dana_formatted: string,
     *     anak_asuh_aktif: int,
     *     anak_asuh_formatted: string,
     *     program_aktif: int,
     *     program_aktif_formatted: string,
     * }
     */
    private function impactStats(): array
    {
        $totalDana = (float) Donasi::query()
            ->where('status', 'verified')
            ->sum('nominal');

        $anakAsuhAktif = AnakAsuh::query()
            ->where('status_asuhan', 'Aktif')
            ->count();

        $programAktif = Kampanye::query()
            ->where('status', 'aktif')
            ->count();

        return [
            'tahun_akumulasi' => now()->year,
            'total_dana_nominal' => $totalDana,
            'total_dana_formatted' => $this->formatRingkasRupiah($totalDana),
            'anak_asuh_aktif' => $anakAsuhAktif,
            'anak_asuh_formatted' => number_format($anakAsuhAktif, 0, ',', '.'),
            'program_aktif' => $programAktif,
            'program_aktif_formatted' => number_format($programAktif, 0, ',', '.'),
        ];
    }

    /**
     * Format nominal rupiah secara ringkas untuk nominal besar (Juta/Miliar/Triliun).
     */
    private function formatRingkasRupiah(float $nominal): string
    {
        $rounded = round(max(0.0, $nominal));

        if ($rounded >= 1_000_000_000_000 || ($rounded >= 1_000_000_000 && round($rounded / 1_000_000_000, 1) >= 1000)) {
            return 'Rp '.$this->formatDesimalRingkas(round($rounded / 1_000_000_000_000, 1)).' Triliun';
        }

        if ($rounded >= 1_000_000_000 || ($rounded >= 1_000_000 && round($rounded / 1_000_000, 1) >= 1000)) {
            return 'Rp '.$this->formatDesimalRingkas(round($rounded / 1_000_000_000, 1)).' Miliar';
        }

        if ($rounded >= 1_000_000) {
            return 'Rp '.$this->formatDesimalRingkas(round($rounded / 1_000_000, 1)).' Juta';
        }

        return 'Rp '.number_format($rounded, 0, ',', '.');
    }

    /**
     * Format angka desimal maksimal 1 digit di belakang koma tanpa akhiran ",0".
     */
    private function formatDesimalRingkas(float $value): string
    {
        $formatted = number_format($value, 1, ',', '.');

        return str_ends_with($formatted, ',0') ? substr($formatted, 0, -2) : $formatted;
    }

    /**
     * Donasi terverifikasi terbaru untuk ticker di landing page.
     *
     * @return Collection<int, array<string, string>>
     */
    private function recentDonors(): Collection
    {
        return Donasi::query()
            ->where('status', 'verified')
            ->latest()
            ->take(6)
            ->get()
            ->map(fn (Donasi $donasi): array => [
                'name' => filled($donasi->nama_donatur) ? $donasi->nama_donatur : 'Hamba Allah',
                'amount' => 'Rp'.number_format((float) $donasi->nominal, 0, ',', '.'),
                'time' => $donasi->created_at?->locale('id')->diffForHumans() ?? '-',
            ]);
    }

    /**
     * Empat kabar terbaru yang sudah tayang dan tidak dipakai di kalender kegiatan.
     *
     * @return Collection<int, array<string, string>>
     */
    private function beritaTerbaru(): Collection
    {
        return BeritaKegiatan::query()
            ->dipublikasikan()
            ->where('terjadwal_di_kalender', false)
            ->terbaru()
            ->take(4)
            ->get()
            ->map(fn (BeritaKegiatan $berita): array => [
                'kategori' => $berita->kategori,
                'judul' => $berita->judul,
                'ringkasan' => $berita->ringkasan,
                'tanggal' => $berita->tanggal->locale('id')->translatedFormat('j M Y'),
                'lokasi' => $berita->lokasi,
                'image' => $berita->gambar_url,
                'url' => route('berita-kegiatan.show', $berita->slug),
            ]);
    }

    /**
     * Format satu kampanye agar siap dikonsumsi landing page.
     *
     * @return array<string, mixed>
     */
    private function formatKampanye(Kampanye $kampanye, int $excerptLength): array
    {
        return [
            'judul' => $kampanye->judul,
            'kategori' => $kampanye->kategori,
            'deskripsi' => Str::limit(strip_tags((string) $kampanye->deskripsi), $excerptLength),
            'image' => asset($kampanye->banner_path ?: 'images/User/Renovasi Ruang Belajar.png'),
            'donatur' => (int) $kampanye->donatur_count,
            'terkumpul' => 'Rp '.number_format($kampanye->total_terkumpul, 0, ',', '.'),
            'target' => 'Rp '.number_format((float) $kampanye->target_dana, 0, ',', '.'),
            'persentase' => (int) round($kampanye->persentase_tercapai),
            'url' => route('kampanye.index', ['program' => $kampanye->slug]),
        ];
    }

    /**
     * Tiga agenda kegiatan mendatang yang terjadwal di kalender untuk section Kolaborasi Kegiatan.
     *
     * @return Collection<int, array<string, string>>
     */
    private function kegiatanMendatang(): Collection
    {
        $items = BeritaKegiatan::query()
            ->where('terjadwal_di_kalender', true)
            ->whereDate('tanggal', '>=', today())
            ->orderBy('tanggal')
            ->orderBy('id')
            ->take(3)
            ->get();

        if ($items->isEmpty()) {
            $items = BeritaKegiatan::query()
                ->where('terjadwal_di_kalender', true)
                ->whereDate('tanggal', '>=', now()->startOfMonth()->toDateString())
                ->orderBy('tanggal')
                ->orderBy('id')
                ->take(3)
                ->get();
        }

        if ($items->isNotEmpty()) {
            return $items->map(function (BeritaKegiatan $kegiatan): array {
                $waktu = filled($kegiatan->waktu_teks) ? $kegiatan->waktu_teks : '09.00–11.30 WIB';
                $hari = $kegiatan->tanggal->locale('id')->translatedFormat('l');

                return [
                    'day' => $kegiatan->tanggal->format('d'),
                    'month_year' => Str::upper($kegiatan->tanggal->locale('id')->translatedFormat('M Y')),
                    'badge' => filled($kegiatan->kategori) ? 'Terbuka untuk umum • '.$kegiatan->kategori : 'Terbuka untuk umum',
                    'judul' => $kegiatan->judul,
                    'waktu' => $hari.', '.$waktu,
                    'lokasi' => $kegiatan->lokasi ?: 'Asrama Cibiru, Bandung',
                    'penyelenggara' => $kegiatan->penyelenggara ?: 'Yayasan Himmatun Ayat Bandung',
                    'url' => route('berita-kegiatan.index').'#kalender-kegiatan',
                ];
            });
        }

        $currentMonth = now()->startOfMonth();
        $d1 = $currentMonth->copy()->day(19);
        $d2 = $currentMonth->copy()->day(25);
        $d3 = $currentMonth->copy()->addMonth()->day(2);

        return collect([
            [
                'day' => $d1->format('d'),
                'month_year' => Str::upper($d1->locale('id')->translatedFormat('M Y')),
                'badge' => 'Terbuka untuk umum',
                'judul' => 'Doa Bersama & Santunan Anak Yatim',
                'waktu' => $d1->locale('id')->translatedFormat('l').', 09.00–11.30 WIB',
                'lokasi' => 'Asrama Cibiru, Bandung',
                'penyelenggara' => 'Yayasan Himmatun Ayat Bandung',
                'url' => route('berita-kegiatan.index').'#kalender-kegiatan',
            ],
            [
                'day' => $d2->format('d'),
                'month_year' => Str::upper($d2->locale('id')->translatedFormat('M Y')),
                'badge' => 'Pendaftaran dibuka',
                'judul' => 'Kelas Inspirasi Bersama Relawan',
                'waktu' => $d2->locale('id')->translatedFormat('l').', 09.00–12.00 WIB',
                'lokasi' => 'Ruang Belajar Cikutra',
                'penyelenggara' => 'Komunitas Relawan Muda Bandung',
                'url' => route('berita-kegiatan.index').'#kalender-kegiatan',
            ],
            [
                'day' => $d3->format('d'),
                'month_year' => Str::upper($d3->locale('id')->translatedFormat('M Y')),
                'badge' => 'Terbuka untuk umum',
                'judul' => 'Kajian Ahad & Khataman Al-Quran',
                'waktu' => $d3->locale('id')->translatedFormat('l').', 08.00–10.30 WIB',
                'lokasi' => 'Asrama Cibiru, Bandung',
                'penyelenggara' => 'Pengurus Asrama & Pembimbing Tahfidz',
                'url' => route('berita-kegiatan.index').'#kalender-kegiatan',
            ],
        ]);
    }
}
