<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DonasiExport;
use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\Kampanye;
use App\Models\PengeluaranOperasional;
use App\Models\PenyaluranDana;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class KelolaDonasiController extends Controller
{
    /**
     * Display the Kelola Donasi page with metrics, filters, and list.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sort = $request->query('sort', 'total_donasi');

        // Query donasi
        $query = Donasi::with('kampanye');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_donatur', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_whatsapp', 'like', "%{$search}%")
                    ->orWhereHas('kampanye', function ($kq) use ($search) {
                        $kq->where('judul', 'like', "%{$search}%");
                    });
            });
        }

        // Sorting logic based on segmented button
        switch ($sort) {
            case 'frekuensi':
                $query->latest('created_at');
                break;
            case 'nama':
                $query->orderBy('nama_donatur', 'asc');
                break;
            case 'total_donasi':
            default:
                $query->orderByDesc('nominal');
                break;
        }

        $donasiList = $query->paginate(10)->withQueryString();

        // --- 4 Top Metrics (dari DB) ---
        $totalZis = (float) Donasi::where('status', 'verified')->sum('nominal');

        $totalPenyaluranProgram = (float) PenyaluranDana::sum('nominal');

        $totalOperasional = (float) PengeluaranOperasional::sum('nominal');

        $saldoKas = $totalZis - $totalPenyaluranProgram - $totalOperasional;

        // Rasio penyaluran (%) terhadap total ZIS
        $rasioEfektivitas = $totalZis > 0
            ? round(($totalPenyaluranProgram / $totalZis) * 100, 1)
            : 0;

        // Rasio operasional (%) terhadap total ZIS
        $rasioOperasional = $totalZis > 0
            ? round(($totalOperasional / $totalZis) * 100, 1)
            : 0;

        // Persentase bulan lalu dibanding bulan sebelumnya
        $nominalBulanIni = (float) Donasi::where('status', 'verified')
            ->whereMonth('diverifikasi_pada', now()->month)
            ->whereYear('diverifikasi_pada', now()->year)
            ->sum('nominal');

        $nominalBulanLalu = (float) Donasi::where('status', 'verified')
            ->whereMonth('diverifikasi_pada', now()->subMonth()->month)
            ->whereYear('diverifikasi_pada', now()->subMonth()->year)
            ->sum('nominal');

        $persenBulanLalu = $nominalBulanLalu > 0
            ? round((($nominalBulanIni - $nominalBulanLalu) / $nominalBulanLalu) * 100, 1)
            : 0;

        $metrics = [
            'total_zis' => $totalZis,
            'persen_bulan_lalu' => $persenBulanLalu,
            'penyaluran_program' => $totalPenyaluranProgram,
            'rasio_efektivitas' => $rasioEfektivitas,
            'operasional_hak_milik' => $totalOperasional,
            'rasio_operasional' => $rasioOperasional,
            'saldo_kas' => $saldoKas,
        ];

        // --- Klaster Asnaf Breakdown (dari DB) ---
        $klasterLabels = PenyaluranDana::klasterLabels();

        $klasterRows = PenyaluranDana::select('klaster_asnaf', DB::raw('SUM(nominal) as total'))
            ->groupBy('klaster_asnaf')
            ->get()
            ->keyBy('klaster_asnaf');

        $totalRealisasi = $klasterRows->sum('total');

        $klasterColors = [
            'pendidikan' => ['bg' => 'bg-brand-700',       'dot' => 'bg-brand-700'],
            'nutrisi' => ['bg' => 'bg-brand-400',        'dot' => 'bg-brand-400'],
            'kesehatan' => ['bg' => 'bg-warning',          'dot' => 'bg-warning'],
            'asrama' => ['bg' => 'bg-muted-foreground', 'dot' => 'bg-muted-foreground'],
            'lainnya' => ['bg' => 'bg-slate-400',        'dot' => 'bg-slate-400'],
        ];

        $asnafBreakdown = [];
        foreach ($klasterLabels as $key => $label) {
            $nominal = isset($klasterRows[$key]) ? (float) $klasterRows[$key]->total : 0;
            $persen = $totalRealisasi > 0 ? round(($nominal / $totalRealisasi) * 100) : 0;
            $asnafBreakdown[$key] = [
                'nama' => $label,
                'nominal' => $nominal,
                'persen' => $persen,
                'bg' => $klasterColors[$key]['bg'] ?? 'bg-slate-400',
                'dot' => $klasterColors[$key]['dot'] ?? 'bg-slate-400',
            ];
        }

        $asnaf = [
            'total_realisasi' => $totalRealisasi,
            ...$asnafBreakdown,
        ];

        $kampanyeList = Kampanye::where('status', 'aktif')->get();
        $totalDonaturTerkini = Donasi::distinct('nama_donatur')->count('nama_donatur');
        $penyaluranList = PenyaluranDana::latest('tanggal_penyaluran')->latest('id')->get();
        $operasionalList = PengeluaranOperasional::latest('tanggal_pengeluaran')->latest('id')->get();

        return view('admin.kelola-donasi.index', compact(
            'donasiList',
            'metrics',
            'asnaf',
            'search',
            'sort',
            'kampanyeList',
            'totalDonaturTerkini',
            'penyaluranList',
            'operasionalList',
            'klasterLabels'
        ));
    }

    /**
     * Store newly created donatur / donasi.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_donatur' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_whatsapp' => 'nullable|string|max:50',
            'kampanye_id' => 'nullable|exists:kampanye,id',
            'nominal' => 'required|numeric|min:1000',
            'metode_pembayaran' => 'nullable|string|max:100',
            'pesan_doa' => 'nullable|string',
            'status' => 'nullable|in:pending,verified',
        ]);

        $validated['metode_pembayaran'] = $validated['metode_pembayaran'] ?? 'Transfer Bank (BSI)';
        $validated['bukti_transfer_path'] = 'images/receipt-placeholder.svg';
        $status = $validated['status'] ?? 'verified';
        $validated['status'] = $status;

        if ($status === 'verified') {
            $validated['diverifikasi_pada'] = now();
        }

        DB::transaction(function () use ($validated, $status) {
            $donasi = Donasi::create($validated);

            if ($status === 'verified' && ! empty($validated['kampanye_id'])) {
                Kampanye::where('id', $validated['kampanye_id'])
                    ->increment('dana_terkumpul', (float) $validated['nominal']);
            }
        });

        return back()->with('success', "Data donatur {$validated['nama_donatur']} berhasil ditambahkan.");
    }

    /**
     * Update an existing donasi record.
     */
    public function update(Request $request, Donasi $donasi): RedirectResponse
    {
        $validated = $request->validate([
            'nama_donatur'      => 'required|string|max:255',
            'email'             => 'nullable|email|max:255',
            'no_whatsapp'       => 'nullable|string|max:50',
            'kampanye_id'       => 'nullable|exists:kampanye,id',
            'nominal'           => 'required|numeric|min:1000',
            'metode_pembayaran' => 'nullable|string|max:100',
            'pesan_doa'         => 'nullable|string',
            'status'            => 'required|in:pending,verified,rejected',
        ]);

        $wasVerified = $donasi->status === 'verified';
        $willBeVerified = $validated['status'] === 'verified';
        $nominalLama = (float) $donasi->nominal;
        $kampanyeIdLama = $donasi->kampanye_id;

        if ($willBeVerified && ! $wasVerified) {
            $validated['diverifikasi_pada'] = now();
        } elseif (! $willBeVerified) {
            $validated['diverifikasi_pada'] = null;
        }

        DB::transaction(function () use ($donasi, $validated, $wasVerified, $willBeVerified, $nominalLama, $kampanyeIdLama) {
            // Rollback dana_terkumpul kampanye lama jika sebelumnya verified
            if ($wasVerified && $kampanyeIdLama) {
                Kampanye::where('id', $kampanyeIdLama)
                    ->decrement('dana_terkumpul', $nominalLama);
            }

            $donasi->update($validated);

            // Tambah dana_terkumpul kampanye baru jika akan/masih verified
            if ($willBeVerified && ! empty($validated['kampanye_id'])) {
                Kampanye::where('id', $validated['kampanye_id'])
                    ->increment('dana_terkumpul', (float) $validated['nominal']);
            }
        });

        return back()->with('success', "Data donasi {$validated['nama_donatur']} berhasil diperbarui.");
    }

    /**
     * Accept / Verify a donation.
     */
    public function terima(Donasi $donasi): RedirectResponse
    {
        if ($donasi->status !== 'verified') {
            DB::transaction(function () use ($donasi) {
                $donasi->update([
                    'status' => 'verified',
                    'diverifikasi_pada' => now(),
                ]);

                if ($donasi->kampanye_id) {
                    Kampanye::where('id', $donasi->kampanye_id)
                        ->increment('dana_terkumpul', (float) $donasi->nominal);
                }
            });
        }

        return back()->with('success', "Donasi dari {$donasi->nama_donatur} telah diterima dan diverifikasi.");
    }

    /**
     * Delete / reject a donation.
     */
    public function destroy(Donasi $donasi): RedirectResponse
    {
        $nama = $donasi->nama_donatur;

        DB::transaction(function () use ($donasi) {
            if ($donasi->status === 'verified' && $donasi->kampanye_id) {
                Kampanye::where('id', $donasi->kampanye_id)
                    ->decrement('dana_terkumpul', (float) $donasi->nominal);
            }

            $donasi->delete();
        });

        return back()->with('success', "Data donasi {$nama} berhasil dihapus.");
    }

    /**
     * Export donasi data to Excel.
     */
    public function export(Request $request)
    {
        $filename = 'data-kelola-donasi-himmatun-ayat-'.now()->format('Ymd-His').'.xlsx';

        return Excel::download(new DonasiExport, $filename);
    }
}
