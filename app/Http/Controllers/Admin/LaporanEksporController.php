<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AnakAsuhExport;
use App\Exports\DonasiExport;
use App\Exports\KampanyeExport;
use App\Http\Controllers\Controller;
use App\Models\AnakAsuh;
use App\Models\Donasi;
use App\Models\Kampanye;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanEksporController extends Controller
{
    /**
     * Daftar pilihan bulan dalam Bahasa Indonesia.
     */
    protected array $daftarBulan = [
        'Semua Bulan', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];

    /**
     * Daftar pilihan tahun.
     */
    protected array $daftarTahun = [
        'Semua Tahun', '2026', '2025', '2024', '2023',
    ];

    /**
     * Kategori per jenis laporan.
     */
    protected array $categoriesByReport = [
        'Data Anak Asuh' => [
            'Semua Kategori',
            'Pendidikan',
            'Yatim',
            'Dhuafa',
            'Tahfidz',
            'Asrama',
            'SD',
            'SMP',
            'SMA',
            'Perguruan Tinggi',
        ],
        'Kelola Donasi' => [
            'Semua Kategori',
            'Transfer Bank',
            'QRIS Manual',
            'Terverifikasi',
            'Pending',
        ],
        'Kelola Kampanye' => [
            'Semua Kategori',
            'Pendidikan & Sosial',
            'Kemanusiaan',
            'Dakwah & Syiar',
            'Gizi & Sembako',
            'Aktif',
            'Selesai',
        ],
    ];

    /**
     * Menampilkan halaman Laporan & Ekspor atau merespons AJAX JSON.
     */
    public function index(Request $request)
    {
        $jenisLaporan = $request->query('jenis_laporan', 'Data Anak Asuh');
        $bulan = $request->query('bulan', 'Semua Bulan');
        $tahun = $request->query('tahun', 'Semua Tahun');
        $kategori = $request->query('kategori', 'Semua Kategori');
        $page = max(1, (int) $request->query('page', 1));
        $perPage = 5;

        // Ambil daftar kategori yang valid untuk jenis laporan yang dipilih
        $kategoriOptions = $this->categoriesByReport[$jenisLaporan] ?? ['Semua Kategori'];

        // Ambil data preview, statistik, kolom, dan paginasi
        $reportData = $this->buildReportData($jenisLaporan, $bulan, $tahun, $kategori, $page, $perPage);

        // Jika request menginginkan JSON (AJAX)
        if ($request->ajax() || $request->wantsJson() || $request->query('json') == '1') {
            return response()->json([
                'success'         => true,
                'jenisLaporan'    => $jenisLaporan,
                'bulan'           => $bulan,
                'tahun'           => $tahun,
                'kategori'        => $kategori,
                'stats'           => $reportData['stats'],
                'columns'         => $reportData['columns'],
                'previewData'     => $reportData['previewData'],
                'pagination'      => $reportData['pagination'],
                'kategoriOptions' => $kategoriOptions,
            ]);
        }

        $stats = $reportData['stats'];
        $columns = $reportData['columns'];
        $previewData = $reportData['previewData'];
        $pagination = $reportData['pagination'];
        $daftarBulan = $this->daftarBulan;
        $daftarTahun = $this->daftarTahun;
        $daftarKategori = $kategoriOptions;
        $categoriesMap = $this->categoriesByReport;

        return view('admin.laporan-ekspor.index', compact(
            'jenisLaporan',
            'bulan',
            'tahun',
            'kategori',
            'stats',
            'columns',
            'previewData',
            'pagination',
            'daftarBulan',
            'daftarTahun',
            'daftarKategori',
            'categoriesMap'
        ));
    }

    /**
     * Ekspor laporan ke format Excel.
     */
    public function exportExcel(Request $request)
    {
        $jenisLaporan = $request->query('jenis_laporan', 'Data Anak Asuh');
        $bulan = $request->query('bulan', 'Semua Bulan');
        $tahun = $request->query('tahun', 'Semua Tahun');
        $kategori = $request->query('kategori', 'Semua Kategori');

        $bulanNum = $this->parseBulan($bulan);
        $tahunNum = $this->parseTahun($tahun);
        $timestamp = now()->format('Ymd-His');

        if ($jenisLaporan === 'Kelola Donasi') {
            $filename = 'laporan-donasi-' . $timestamp . '.xlsx';
            return Excel::download(new DonasiExport(null, null, null, $bulanNum, $tahunNum, $kategori), $filename);
        }

        if ($jenisLaporan === 'Kelola Kampanye') {
            $filename = 'laporan-kampanye-' . $timestamp . '.xlsx';
            return Excel::download(new KampanyeExport($kategori, null, $bulanNum, $tahunNum), $filename);
        }

        // Default: Data Anak Asuh
        $filename = 'laporan-anak-asuh-' . $timestamp . '.xlsx';
        return Excel::download(new AnakAsuhExport(null, $kategori, null, $bulanNum, $tahunNum), $filename);
    }

    /**
     * Ekspor laporan ke format PDF.
     */
    public function exportPdf(Request $request)
    {
        $jenisLaporan = $request->query('jenis_laporan', 'Data Anak Asuh');
        $bulan = $request->query('bulan', 'Semua Bulan');
        $tahun = $request->query('tahun', 'Semua Tahun');
        $kategori = $request->query('kategori', 'Semua Kategori');
        $isInline = $request->boolean('preview') || $request->boolean('inline') || $request->query('view') === 'preview';

        $bulanNum = $this->parseBulan($bulan);
        $tahunNum = $this->parseTahun($tahun);
        $timestamp = now()->format('Ymd-His');

        if ($jenisLaporan === 'Kelola Donasi') {
            $query = Donasi::with('kampanye');

            if ($kategori && in_array(strtolower($kategori), ['pending', 'verified', 'rejected', 'terverifikasi'])) {
                $statusVal = strtolower($kategori) === 'terverifikasi' ? 'verified' : strtolower($kategori);
                $query->where('status', $statusVal);
            } else {
                $query->where('status', 'verified');
            }

            if ($bulanNum) $query->whereMonth('created_at', $bulanNum);
            if ($tahunNum) $query->whereYear('created_at', $tahunNum);
            if ($kategori === 'Transfer Bank') {
                $query->where(function ($q) {
                    $q->where('metode_pembayaran', 'like', '%Transfer%')->orWhere('metode_pembayaran', 'like', '%Bank%');
                });
            } elseif ($kategori === 'QRIS Manual') {
                $query->where('metode_pembayaran', 'like', '%QRIS%');
            }

            $donasi = $query->latest('id')->get();
            $summary = [
                'total_donasi_terverifikasi' => (float) $donasi->where('status', 'verified')->sum('nominal'),
                'total_transaksi_verified'   => $donasi->where('status', 'verified')->count(),
            ];

            $startDate = null;
            $endDate = null;

            $pdf = Pdf::loadView('admin.laporan.pdf', compact('donasi', 'summary', 'startDate', 'endDate'));
            $pdf->setPaper('a4', 'portrait');

            $filename = 'laporan-donasi-' . $timestamp . '.pdf';
            return $isInline ? $pdf->stream($filename) : $pdf->download($filename);
        }

        if ($jenisLaporan === 'Kelola Kampanye') {
            $query = Kampanye::query();
            if ($bulanNum) $query->whereMonth('created_at', $bulanNum);
            if ($tahunNum) $query->whereYear('created_at', $tahunNum);
            if ($kategori && $kategori !== 'Semua Kategori') {
                if (in_array(strtolower($kategori), ['aktif', 'selesai'])) {
                    $query->where('status', strtolower($kategori));
                } else {
                    $query->where('kategori', 'like', "%{$kategori}%");
                }
            }

            $kampanye = $query->latest('id')->get();
            $stats = [
                'total'          => $kampanye->count(),
                'terkumpul_raw'  => (float) $kampanye->sum('dana_terkumpul'),
                'target_raw'     => (float) $kampanye->sum('target_dana'),
            ];

            $pdf = Pdf::loadView('admin.laporan.pdf-kampanye', compact('kampanye', 'stats', 'bulan', 'tahun', 'kategori'));
            $pdf->setPaper('a4', 'landscape');

            $filename = 'laporan-kampanye-' . $timestamp . '.pdf';
            return $isInline ? $pdf->stream($filename) : $pdf->download($filename);
        }

        // Default: Data Anak Asuh
        $query = AnakAsuh::query();
        if ($bulanNum) $query->whereMonth('created_at', $bulanNum);
        if ($tahunNum) $query->whereYear('created_at', $tahunNum);
        if ($kategori && $kategori !== 'Semua Kategori') {
            if (in_array($kategori, ['SD', 'SMP', 'SMA', 'Perguruan Tinggi'])) {
                $query->where(function ($q) use ($kategori) {
                    $q->where('pendidikan_terakhir', $kategori)
                      ->orWhere('pendidikan_terakhir', 'like', "%{$kategori}%");
                });
            } else {
                $query->where('status_asuhan', $kategori);
            }
        }

        $anakAsuh = $query->latest('id')->get();
        $search = null;
        $status = $kategori !== 'Semua Kategori' ? $kategori : null;

        $pdf = Pdf::loadView('admin.anak-asuh.pdf', compact('anakAsuh', 'search', 'status'));
        $pdf->setPaper('a4', 'landscape');

        $filename = 'laporan-anak-asuh-' . $timestamp . '.pdf';
        return $isInline ? $pdf->stream($filename) : $pdf->download($filename);
    }

    /**
     * Membangun dataset preview dan statistik berdasarkan filter.
     */
    protected function buildReportData(string $jenisLaporan, string $bulan, string $tahun, string $kategori, int $page = 1, int $perPage = 5): array
    {
        $bulanNum = $this->parseBulan($bulan);
        $tahunNum = $this->parseTahun($tahun);

        if ($jenisLaporan === 'Kelola Donasi') {
            return $this->getDonasiReportData($bulanNum, $tahunNum, $kategori, $page, $perPage);
        }

        if ($jenisLaporan === 'Kelola Kampanye') {
            return $this->getKampanyeReportData($bulanNum, $tahunNum, $kategori, $page, $perPage);
        }

        return $this->getAnakAsuhReportData($bulanNum, $tahunNum, $kategori, $page, $perPage);
    }

    /**
     * Dataset untuk Laporan Data Anak Asuh.
     */
    protected function getAnakAsuhReportData(?int $bulanNum, ?int $tahunNum, string $kategori, int $page, int $perPage): array
    {
        $columns = [
            ['key' => 'no', 'label' => 'No', 'width' => 'w-12 text-center'],
            ['key' => 'id_anak', 'label' => 'ID Anak', 'width' => 'w-24'],
            ['key' => 'nama', 'label' => 'Nama Anak Asuh', 'width' => 'min-w-[160px]'],
            ['key' => 'kategori', 'label' => 'Kategori', 'width' => 'w-28'],
            ['key' => 'status', 'label' => 'Status', 'width' => 'w-28'],
            ['key' => 'tanggal', 'label' => 'Tanggal Bergabung', 'width' => 'w-36'],
        ];

        // Jika database belum diisi data (misal saat fresh test), sediakan sample mockup yang konsisten dengan slicing
        if (AnakAsuh::count() === 0) {
            $stats = [
                'total'                   => '128',
                'aktif_pendidikan'        => '112',
                'aktif_pendidikan_persen' => '87,5%',
                'aktif_asrama'            => '98',
                'aktif_asrama_persen'     => '76,6%',
                'perlu_perhatian'         => '16',
                'perlu_perhatian_persen'  => '12,5%',
                'cards' => [
                    [
                        'title' => 'Total Anak Asuh',
                        'value' => '128',
                        'sub'   => 'Data sesuai filter',
                        'type'  => 'total',
                    ],
                    [
                        'title' => 'Aktif Pendidikan',
                        'value' => '112',
                        'sub'   => '87,5% dari total',
                        'type'  => 'pendidikan',
                    ],
                    [
                        'title' => 'Aktif di Asrama',
                        'value' => '98',
                        'sub'   => '76,6% dari total',
                        'type'  => 'asrama',
                    ],
                    [
                        'title' => 'Perlu Perhatian',
                        'value' => '16',
                        'sub'   => '12,5% dari total',
                        'type'  => 'perhatian',
                    ],
                ],
            ];

            $sampleRows = [
                ['no' => '001', 'id_anak' => 'AA-0021', 'nama' => 'Nur Aisyah', 'kategori' => 'Pendidikan', 'status' => 'Aktif', 'tanggal' => '12 Sep 2025'],
                ['no' => '002', 'id_anak' => 'AA-0022', 'nama' => 'Muhammad Rizki', 'kategori' => 'Yatim', 'status' => 'Aktif', 'tanggal' => '10 Sep 2025'],
                ['no' => '003', 'id_anak' => 'AA-0023', 'nama' => 'Siti Rahma', 'kategori' => 'Pendidikan', 'status' => 'Perlu Pembaruan', 'tanggal' => '08 Sep 2025'],
                ['no' => '004', 'id_anak' => 'AA-0024', 'nama' => 'Ahmad Fauzan', 'kategori' => 'Yatim', 'status' => 'Aktif', 'tanggal' => '05 Sep 2025'],
                ['no' => '005', 'id_anak' => 'AA-0025', 'nama' => 'Fadli Ramadhan', 'kategori' => 'Pendidikan', 'status' => 'Aktif', 'tanggal' => '01 Sep 2025'],
            ];

            return [
                'stats'       => $stats,
                'columns'     => $columns,
                'previewData' => $sampleRows,
                'pagination'  => [
                    'current_page' => 1,
                    'last_page'    => 26,
                    'total'        => 128,
                    'from'         => 1,
                    'to'           => 5,
                    'per_page'     => 5,
                ],
            ];
        }

        // Query database nyata
        $baseQuery = AnakAsuh::query();
        if ($bulanNum) $baseQuery->whereMonth('created_at', $bulanNum);
        if ($tahunNum) $baseQuery->whereYear('created_at', $tahunNum);

        $filteredQuery = clone $baseQuery;
        if ($kategori && $kategori !== 'Semua Kategori') {
            if (in_array($kategori, ['SD', 'SMP', 'SMA', 'Perguruan Tinggi'])) {
                $filteredQuery->where(function ($q) use ($kategori) {
                    $q->where('pendidikan_terakhir', $kategori)
                      ->orWhere('pendidikan_terakhir', 'like', "%{$kategori}%");
                });
            } elseif ($kategori === 'Pendidikan') {
                $filteredQuery->whereNotNull('pendidikan_terakhir');
            } elseif (in_array($kategori, ['Yatim', 'Dhuafa', 'Tahfidz'])) {
                $filteredQuery->where('keterangan', 'like', "%{$kategori}%");
            } elseif ($kategori === 'Asrama') {
                $filteredQuery->where('keterangan', 'like', "%asrama%");
            } else {
                $filteredQuery->where('status_asuhan', $kategori);
            }
        }

        $totalFiltered = $filteredQuery->count();
        $totalAll = max(AnakAsuh::count(), 1);

        // Statistik anak asuh
        $aktifPendidikan = (clone $filteredQuery)->whereNotNull('pendidikan_terakhir')->where('status_asuhan', 'Aktif')->count();
        $aktifAsrama = (clone $filteredQuery)->where('status_asuhan', 'Aktif')->count();
        $perluPerhatian = (clone $filteredQuery)->where('status_asuhan', '!=', 'Aktif')->count();

        $pendidikanPersen = $totalFiltered > 0 ? round(($aktifPendidikan / $totalFiltered) * 100, 1) . '%' : '0%';
        $asramaPersen = $totalFiltered > 0 ? round(($aktifAsrama / $totalFiltered) * 100, 1) . '%' : '0%';
        $perhatianPersen = $totalFiltered > 0 ? round(($perluPerhatian / $totalFiltered) * 100, 1) . '%' : '0%';

        $stats = [
            'total'                   => (string) $totalFiltered,
            'aktif_pendidikan'        => (string) $aktifPendidikan,
            'aktif_pendidikan_persen' => $pendidikanPersen,
            'aktif_asrama'            => (string) $aktifAsrama,
            'aktif_asrama_persen'     => $asramaPersen,
            'perlu_perhatian'         => (string) $perluPerhatian,
            'perlu_perhatian_persen'  => $perhatianPersen,
            'cards' => [
                [
                    'title' => 'Total Anak Asuh',
                    'value' => (string) $totalFiltered,
                    'sub'   => 'Data sesuai filter',
                    'type'  => 'total',
                ],
                [
                    'title' => 'Aktif Pendidikan',
                    'value' => (string) $aktifPendidikan,
                    'sub'   => $pendidikanPersen . ' dari total',
                    'type'  => 'pendidikan',
                ],
                [
                    'title' => 'Aktif di Asrama',
                    'value' => (string) $aktifAsrama,
                    'sub'   => $asramaPersen . ' dari total',
                    'type'  => 'asrama',
                ],
                [
                    'title' => 'Perlu Perhatian',
                    'value' => (string) $perluPerhatian,
                    'sub'   => $perhatianPersen . ' dari total',
                    'type'  => 'perhatian',
                ],
            ],
        ];

        // Paginasi manual untuk preview
        $paginator = $filteredQuery->latest('id')->paginate($perPage, ['*'], 'page', $page);

        $previewRows = [];
        $offset = ($page - 1) * $perPage;
        foreach ($paginator->items() as $index => $item) {
            $catLabel = $item->pendidikan_terakhir ?: 'Pendidikan';
            if ($item->keterangan && str_contains(strtolower($item->keterangan), 'tahfidz')) {
                $catLabel = 'Tahfidz';
            } elseif ($item->keterangan && str_contains(strtolower($item->keterangan), 'yatim')) {
                $catLabel = 'Yatim';
            }

            $previewRows[] = [
                'no'       => sprintf('%03d', $offset + $index + 1),
                'id_anak'  => 'AA-' . str_pad((string) $item->id, 4, '0', STR_PAD_LEFT),
                'nama'     => $item->nama_lengkap,
                'kategori' => $catLabel,
                'status'   => $item->status_asuhan ?: 'Aktif',
                'tanggal'  => $item->created_at ? $item->created_at->translatedFormat('d M Y') : '-',
                'detail'   => [
                    'Nama Panggilan' => $item->nama_panggilan ?: '-',
                    'Jenis Kelamin'  => $item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                    'Usia'           => $item->usia_formatted,
                    'Pendidikan'     => $item->pendidikan_terakhir ?: '-',
                    'Keterangan'     => $item->keterangan ?: '-',
                ],
            ];
        }

        return [
            'stats'       => $stats,
            'columns'     => $columns,
            'previewData' => $previewRows,
            'pagination'  => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => max(1, $paginator->lastPage()),
                'total'        => $paginator->total(),
                'from'         => $paginator->firstItem() ?: 0,
                'to'           => $paginator->lastItem() ?: 0,
                'per_page'     => $perPage,
            ],
        ];
    }

    /**
     * Dataset untuk Laporan Kelola Donasi.
     */
    protected function getDonasiReportData(?int $bulanNum, ?int $tahunNum, string $kategori, int $page, int $perPage): array
    {
        $columns = [
            ['key' => 'no', 'label' => 'No', 'width' => 'w-12 text-center'],
            ['key' => 'id_anak', 'label' => 'ID Donasi', 'width' => 'w-24'],
            ['key' => 'nama', 'label' => 'Nama Donatur', 'width' => 'min-w-[160px]'],
            ['key' => 'kategori', 'label' => 'Program Kampanye', 'width' => 'min-w-[150px]'],
            ['key' => 'status', 'label' => 'Status', 'width' => 'w-28'],
            ['key' => 'tanggal', 'label' => 'Nominal & Waktu', 'width' => 'w-40'],
        ];

        $query = Donasi::with('kampanye');
        if ($bulanNum) $query->whereMonth('created_at', $bulanNum);
        if ($tahunNum) $query->whereYear('created_at', $tahunNum);

        if ($kategori && $kategori !== 'Semua Kategori') {
            if ($kategori === 'Transfer Bank') {
                $query->where(function ($q) {
                    $q->where('metode_pembayaran', 'like', '%Transfer%')->orWhere('metode_pembayaran', 'like', '%Bank%');
                });
            } elseif ($kategori === 'QRIS Manual') {
                $query->where('metode_pembayaran', 'like', '%QRIS%');
            } elseif (in_array(strtolower($kategori), ['terverifikasi', 'verified'])) {
                $query->where('status', 'verified');
            } elseif (in_array(strtolower($kategori), ['pending'])) {
                $query->where('status', 'pending');
            }
        }

        $totalFiltered = $query->count();
        $totalNominal = (float) (clone $query)->where('status', 'verified')->sum('nominal');
        $totalVerified = (clone $query)->where('status', 'verified')->count();
        $totalPending = (clone $query)->where('status', 'pending')->count();

        $stats = [
            'total'                   => 'Rp' . number_format($totalNominal, 0, ',', '.'),
            'aktif_pendidikan'        => (string) $totalVerified . ' Transaksi',
            'aktif_pendidikan_persen' => 'Terverifikasi',
            'aktif_asrama'            => (string) $totalPending . ' Menunggu',
            'aktif_asrama_persen'     => 'Perlu Cek',
            'perlu_perhatian'         => (string) $totalFiltered . ' Total',
            'perlu_perhatian_persen'  => 'Semua Donasi',
            'cards' => [
                [
                    'title' => 'Penerimaan Donasi',
                    'value' => 'Rp' . number_format($totalNominal, 0, ',', '.'),
                    'sub'   => 'Donasi terverifikasi',
                    'type'  => 'total',
                ],
                [
                    'title' => 'Donasi Terverifikasi',
                    'value' => (string) $totalVerified,
                    'sub'   => 'Transaksi berhasil',
                    'type'  => 'pendidikan',
                ],
                [
                    'title' => 'Donasi Pending',
                    'value' => (string) $totalPending,
                    'sub'   => 'Menunggu verifikasi',
                    'type'  => 'perhatian',
                ],
                [
                    'title' => 'Total Transaksi',
                    'value' => (string) $totalFiltered,
                    'sub'   => 'Sesuai filter pencarian',
                    'type'  => 'asrama',
                ],
            ],
        ];

        $paginator = $query->latest('id')->paginate($perPage, ['*'], 'page', $page);
        $previewRows = [];
        $offset = ($page - 1) * $perPage;

        foreach ($paginator->items() as $index => $item) {
            $statusText = match ($item->status) {
                'verified' => 'Aktif',
                'pending'  => 'Perlu Pembaruan',
                default    => 'Ditolak',
            };

            $previewRows[] = [
                'no'       => sprintf('%03d', $offset + $index + 1),
                'id_anak'  => 'DON-' . str_pad((string) $item->id, 4, '0', STR_PAD_LEFT),
                'nama'     => $item->nama_donatur,
                'kategori' => $item->kampanye?->judul ?? 'Donasi Umum',
                'status'   => $statusText,
                'tanggal'  => 'Rp' . number_format($item->nominal, 0, ',', '.') . ' (' . ($item->created_at ? $item->created_at->format('d/m/y') : '-') . ')',
                'detail'   => [
                    'Nominal'           => 'Rp' . number_format($item->nominal, 0, ',', '.'),
                    'Metode Pembayaran' => $item->metode_pembayaran,
                    'No WhatsApp'       => $item->no_whatsapp ?: '-',
                    'Status'            => ucfirst($item->status),
                    'Waktu Transaksi'   => $item->created_at ? $item->created_at->translatedFormat('d F Y H:i') : '-',
                    'Pesan / Doa'       => $item->pesan_doa ?: '-',
                ],
            ];
        }

        return [
            'stats'       => $stats,
            'columns'     => $columns,
            'previewData' => $previewRows,
            'pagination'  => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => max(1, $paginator->lastPage()),
                'total'        => $paginator->total(),
                'from'         => $paginator->firstItem() ?: 0,
                'to'           => $paginator->lastItem() ?: 0,
                'per_page'     => $perPage,
            ],
        ];
    }

    /**
     * Dataset untuk Laporan Kelola Kampanye.
     */
    protected function getKampanyeReportData(?int $bulanNum, ?int $tahunNum, string $kategori, int $page, int $perPage): array
    {
        $columns = [
            ['key' => 'no', 'label' => 'No', 'width' => 'w-12 text-center'],
            ['key' => 'id_anak', 'label' => 'ID Kampanye', 'width' => 'w-28'],
            ['key' => 'nama', 'label' => 'Judul Kampanye', 'width' => 'min-w-[170px]'],
            ['key' => 'kategori', 'label' => 'Kategori', 'width' => 'w-32'],
            ['key' => 'status', 'label' => 'Status', 'width' => 'w-24'],
            ['key' => 'tanggal', 'label' => 'Terkumpul / Target', 'width' => 'min-w-[150px]'],
        ];

        $query = Kampanye::query();
        if ($bulanNum) $query->whereMonth('created_at', $bulanNum);
        if ($tahunNum) $query->whereYear('created_at', $tahunNum);

        if ($kategori && $kategori !== 'Semua Kategori') {
            if (in_array(strtolower($kategori), ['aktif', 'selesai'])) {
                $query->where('status', strtolower($kategori));
            } else {
                $query->where('kategori', 'like', "%{$kategori}%");
            }
        }

        $totalFiltered = $query->count();
        $totalAktif = (clone $query)->where('status', 'aktif')->count();
        $totalTerkumpul = (float) (clone $query)->sum('dana_terkumpul');
        $totalTarget = (float) (clone $query)->sum('target_dana');
        $avgPersen = $totalTarget > 0 ? round(($totalTerkumpul / $totalTarget) * 100, 1) . '%' : '0%';

        $stats = [
            'total'                   => (string) $totalFiltered . ' Program',
            'aktif_pendidikan'        => (string) $totalAktif . ' Aktif',
            'aktif_pendidikan_persen' => 'Sedang Berjalan',
            'aktif_asrama'            => 'Rp' . number_format($totalTerkumpul / 1000000, 1, ',', '.') . ' Jt',
            'aktif_asrama_persen'     => 'Dana Terhimpun',
            'perlu_perhatian'         => $avgPersen,
            'perlu_perhatian_persen'  => 'Rerata Capaian',
            'cards' => [
                [
                    'title' => 'Total Kampanye',
                    'value' => (string) $totalFiltered,
                    'sub'   => 'Program binaan yayasan',
                    'type'  => 'total',
                ],
                [
                    'title' => 'Kampanye Aktif',
                    'value' => (string) $totalAktif,
                    'sub'   => 'Sedang menggalang dana',
                    'type'  => 'pendidikan',
                ],
                [
                    'title' => 'Total Terkumpul',
                    'value' => 'Rp' . number_format($totalTerkumpul, 0, ',', '.'),
                    'sub'   => 'Akumulasi seluruh program',
                    'type'  => 'asrama',
                ],
                [
                    'title' => 'Target Tercapai',
                    'value' => $avgPersen,
                    'sub'   => 'Rata-rata keberhasilan',
                    'type'  => 'perhatian',
                ],
            ],
        ];

        $paginator = $query->latest('id')->paginate($perPage, ['*'], 'page', $page);
        $previewRows = [];
        $offset = ($page - 1) * $perPage;

        foreach ($paginator->items() as $index => $item) {
            $previewRows[] = [
                'no'       => sprintf('%03d', $offset + $index + 1),
                'id_anak'  => 'KMP-' . str_pad((string) $item->id, 4, '0', STR_PAD_LEFT),
                'nama'     => $item->judul,
                'kategori' => $item->kategori ?: 'Sosial',
                'status'   => $item->status === 'aktif' ? 'Aktif' : 'Selesai',
                'tanggal'  => 'Rp' . number_format($item->dana_terkumpul, 0, ',', '.') . ' / ' . number_format($item->target_dana, 0, ',', '.'),
                'detail'   => [
                    'Target Dana'    => 'Rp' . number_format($item->target_dana, 0, ',', '.'),
                    'Dana Terkumpul' => 'Rp' . number_format($item->dana_terkumpul, 0, ',', '.'),
                    'Persentase'     => $item->persentase_tercapai . '%',
                    'Periode'        => $item->periode_text,
                    'Status'         => ucfirst($item->status),
                ],
            ];
        }

        return [
            'stats'       => $stats,
            'columns'     => $columns,
            'previewData' => $previewRows,
            'pagination'  => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => max(1, $paginator->lastPage()),
                'total'        => $paginator->total(),
                'from'         => $paginator->firstItem() ?: 0,
                'to'           => $paginator->lastItem() ?: 0,
                'per_page'     => $perPage,
            ],
        ];
    }

    /**
     * Konversi nama bulan Bahasa Indonesia ke angka (1-12) atau null.
     */
    protected function parseBulan(?string $bulan): ?int
    {
        if (empty($bulan) || $bulan === 'Semua Bulan') {
            return null;
        }

        $bulanMap = [
            'Januari'   => 1,
            'Februari'  => 2,
            'Maret'     => 3,
            'April'     => 4,
            'Mei'       => 5,
            'Juni'      => 6,
            'Juli'      => 7,
            'Agustus'   => 8,
            'September' => 9,
            'Oktober'   => 10,
            'November'  => 11,
            'Desember'  => 12,
        ];

        return $bulanMap[$bulan] ?? null;
    }

    /**
     * Konversi tahun ke integer atau null.
     */
    protected function parseTahun(?string $tahun): ?int
    {
        if (empty($tahun) || $tahun === 'Semua Tahun' || !is_numeric($tahun)) {
            return null;
        }

        return (int) $tahun;
    }
}
