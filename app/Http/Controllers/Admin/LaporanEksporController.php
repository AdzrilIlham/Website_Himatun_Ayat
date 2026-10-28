<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AnakAsuhExport;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanEksporController extends Controller
{
    /**
     * Menampilkan halaman Laporan & Ekspor.
     */
    public function index(Request $request)
    {
        $jenisLaporan = $request->query('jenis_laporan', 'Data Anak Asuh');
        $bulan = $request->query('bulan', 'September');
        $tahun = $request->query('tahun', '2025');
        $kategori = $request->query('kategori', 'Semua Kategori');

        $stats = [
            'total' => 128,
            'aktif_pendidikan' => 112,
            'aktif_pendidikan_persen' => '87,5%',
            'aktif_asrama' => 98,
            'aktif_asrama_persen' => '76,6%',
            'perlu_perhatian' => 16,
            'perlu_perhatian_persen' => '12,5%',
        ];

        $previewData = [
            [
                'no' => '001',
                'id_anak' => 'AA-0021',
                'nama' => 'Nur Aisyah',
                'kategori' => 'Pendidikan',
                'status' => 'Aktif',
                'tanggal' => '12 Sep 2025',
            ],
            [
                'no' => '002',
                'id_anak' => 'AA-0022',
                'nama' => 'Muhammad Rizki',
                'kategori' => 'Yatim',
                'status' => 'Aktif',
                'tanggal' => '10 Sep 2025',
            ],
            [
                'no' => '003',
                'id_anak' => 'AA-0023',
                'nama' => 'Siti Rahma',
                'kategori' => 'Pendidikan',
                'status' => 'Perlu Pembaruan',
                'tanggal' => '08 Sep 2025',
            ],
            [
                'no' => '004',
                'id_anak' => 'AA-0024',
                'nama' => 'Ahmad Fauzan',
                'kategori' => 'Yatim',
                'status' => 'Aktif',
                'tanggal' => '05 Sep 2025',
            ],
            [
                'no' => '005',
                'id_anak' => 'AA-0025',
                'nama' => 'Fadli Ramadhan',
                'kategori' => 'Pendidikan',
                'status' => 'Aktif',
                'tanggal' => '01 Sep 2025',
            ],
        ];

        $daftarBulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        $daftarTahun = ['2023', '2024', '2025', '2026'];

        $daftarKategori = [
            'Semua Kategori',
            'Pendidikan',
            'Yatim',
            'Dhuafa',
            'Tahfidz',
        ];

        return view('admin.laporan-ekspor.index', compact(
            'jenisLaporan',
            'bulan',
            'tahun',
            'kategori',
            'stats',
            'previewData',
            'daftarBulan',
            'daftarTahun',
            'daftarKategori'
        ));
    }

    /**
     * Ekspor laporan ke format Excel.
     */
    public function exportExcel(Request $request)
    {
        $filename = 'laporan-ekspor-'.strtolower(str_replace(' ', '-', $request->query('jenis_laporan', 'anak-asuh'))).'-'.now()->format('Ymd-His').'.xlsx';

        return Excel::download(new AnakAsuhExport(null, null), $filename);
    }

    /**
     * Ekspor laporan ke format PDF.
     */
    public function exportPdf(Request $request)
    {
        $jenisLaporan = $request->query('jenis_laporan', 'Data Anak Asuh');
        $bulan = $request->query('bulan', 'September');
        $tahun = $request->query('tahun', '2025');

        $data = [
            'jenisLaporan' => $jenisLaporan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tanggalCetak' => now()->translatedFormat('d F Y H:i'),
        ];

        $pdf = Pdf::loadView('admin.laporan.pdf', [
            'donasi' => collect([]),
            'summary' => [
                'total_donasi_terverifikasi' => 0,
                'total_transaksi_verified' => 0,
            ],
            'startDate' => null,
            'endDate' => null,
        ]);

        return $pdf->download('laporan-ekspor-'.now()->format('Ymd-His').'.pdf');
    }
}
