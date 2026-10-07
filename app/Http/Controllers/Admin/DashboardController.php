<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DonasiExport;
use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\Kampanye;
use App\Services\LaporanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function __construct(
        protected LaporanService $laporanService
    ) {}

    public function index(Request $request)
    {
        $search = $request->query('search');
        $metode = $request->query('metode', 'all');
        $tanggal = $request->query('tanggal');

        $summary = $this->laporanService->getDashboardSummary();
        $counts = $this->laporanService->getCountByMetode($tanggal);
        $donasiPending = $this->laporanService->getPendingDonasi($search, $metode, $tanggal, 5);

        return view('admin.dashboard', compact(
            'summary',
            'counts',
            'donasiPending',
            'search',
            'metode',
            'tanggal'
        ));
    }

    public function verifikasi(Donasi $donasi): RedirectResponse
    {
        if ($donasi->status !== 'verified') {
            DB::transaction(function () use ($donasi) {
                $donasi->update([
                    'status'            => 'verified',
                    'diverifikasi_pada' => now(),
                ]);

                if ($donasi->kampanye_id) {
                    Kampanye::where('id', $donasi->kampanye_id)
                        ->increment('dana_terkumpul', (float) $donasi->nominal);
                }
            });
        }

        return back()->with('success', "Donasi #{$donasi->id} atas nama {$donasi->nama_donatur} berhasil diverifikasi.");
    }

    public function tolak(Donasi $donasi): RedirectResponse
    {
        DB::transaction(function () use ($donasi) {
            if ($donasi->status === 'verified' && $donasi->kampanye_id) {
                Kampanye::where('id', $donasi->kampanye_id)
                    ->decrement('dana_terkumpul', (float) $donasi->nominal);
            }

            $donasi->update([
                'status'            => 'rejected',
                'diverifikasi_pada' => now(),
            ]);
        });

        return back()->with('warning', "Donasi #{$donasi->id} atas nama {$donasi->nama_donatur} telah ditolak.");
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->query('tanggal_awal');
        $endDate   = $request->query('tanggal_akhir');
        $filename  = 'rekap-donasi-himmatun-ayat-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(new DonasiExport($startDate, $endDate), $filename);
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->query('tanggal_awal');
        $endDate   = $request->query('tanggal_akhir');
        $donasi    = $this->laporanService->getLaporanDonasi($startDate, $endDate);
        $summary   = $this->laporanService->getDashboardSummary();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('donasi', 'summary', 'startDate', 'endDate'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('laporan-donasi-himmatun-ayat-' . now()->format('Ymd-His') . '.pdf');
    }
}
