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
        $sort = $request->query('sort', 'terbaru');
        $sortWaktu = $request->query('sort_waktu', $sort === 'terlama' ? 'terlama' : 'terbaru');
        $sortNama = $request->query('sort_nama', in_array($sort, ['nama_asc', 'nama'], true) ? 'nama_asc' : ($sort === 'nama_desc' ? 'nama_desc' : ''));
        $kampanyeFilter = (string) $request->query('kampanye_id', '');
        $statusFilter = $request->query('status', 'semua');
        $metodeFilter = $request->query('metode', 'all');

        if ($request->filled('sort_nama')) {
            $sort = $sortNama;
        } elseif ($request->filled('sort_waktu')) {
            $sort = $sortWaktu;
        }

        // Query donasi
        $query = Donasi::with('kampanye');

        if ($search) {
            $cleanSearch = ltrim(trim($search), '#');
            $numericId = preg_replace('/[^0-9]/', '', $search);
            $query->where(function ($q) use ($search, $cleanSearch, $numericId) {
                $q->where('kode_donasi', 'like', "%{$cleanSearch}%")
                    ->orWhere('nama_donatur', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_whatsapp', 'like', "%{$search}%")
                    ->orWhere('pesan_doa', 'like', "%{$search}%")
                    ->orWhereHas('kampanye', function ($kq) use ($search) {
                        $kq->where('judul', 'like', "%{$search}%");
                    });

                if ($numericId !== '') {
                    $q->orWhere('id', (int) $numericId);
                }
            });
        }

        if (in_array($statusFilter, ['pending', 'verified', 'rejected'], true)) {
            $query->where('status', $statusFilter);
        }

        if ($kampanyeFilter === 'umum') {
            $query->whereNull('kampanye_id');
        } elseif ($kampanyeFilter !== '' && is_numeric($kampanyeFilter)) {
            $query->where('kampanye_id', (int) $kampanyeFilter);
        }

        if ($metodeFilter === 'transfer_bank') {
            $query->where(function ($q) {
                $q->where('metode_pembayaran', 'like', '%Transfer%')
                    ->orWhere('metode_pembayaran', 'like', '%Bank%');
            });
        } elseif ($metodeFilter === 'qris_manual') {
            $query->where('metode_pembayaran', 'like', '%QRIS%');
        }

        // Sorting logic based on unified filter / sort parameters
        if ($sortNama === 'nama_asc' || in_array($sort, ['nama', 'nama_asc'], true)) {
            $query->orderBy('nama_donatur', 'asc')
                ->orderBy('created_at', $sortWaktu === 'terlama' ? 'asc' : 'desc')
                ->orderByDesc('id');
        } elseif ($sortNama === 'nama_desc' || $sort === 'nama_desc') {
            $query->orderBy('nama_donatur', 'desc')
                ->orderBy('created_at', $sortWaktu === 'terlama' ? 'asc' : 'desc')
                ->orderByDesc('id');
        } elseif ($sort === 'terlama' || $sortWaktu === 'terlama') {
            $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
        } elseif ($sort === 'total_donasi') {
            $query->orderByDesc('nominal')->orderByDesc('id');
        } else {
            $query->orderByDesc('created_at')->orderByDesc('id');
        }

        $donasiList = $query->paginate(10)->withQueryString();

        $statusCounts = [
            'semua' => Donasi::count(),
            'pending' => Donasi::where('status', 'pending')->count(),
            'verified' => Donasi::where('status', 'verified')->count(),
            'rejected' => Donasi::where('status', 'rejected')->count(),
        ];

        // --- 4 Top Metrics (dari DB) ---
        $totalZis = (float) Donasi::where('status', 'verified')->sum('nominal');

        $totalPenyaluranProgram = (float) PenyaluranDana::sum('nominal');

        $totalOperasional = (float) PengeluaranOperasional::sum('nominal');

        $saldoKas = max(0, $totalZis - $totalPenyaluranProgram - $totalOperasional);

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
            'sortWaktu',
            'sortNama',
            'kampanyeFilter',
            'statusFilter',
            'metodeFilter',
            'statusCounts',
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
            'nama_donatur' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'no_whatsapp' => 'nullable|string|max:50',
            'kampanye_id' => 'nullable|exists:kampanye,id',
            'nominal' => 'required|numeric|min:1000',
            'metode_pembayaran' => 'nullable|string|max:100',
            'pesan_doa' => 'nullable|string',
            'status' => 'required|in:pending,verified,rejected',
        ]);

        DB::transaction(function () use ($donasi, $validated) {
            $locked = Donasi::lockForUpdate()->findOrFail($donasi->id);
            $wasVerified = $locked->status === 'verified';
            $willBeVerified = $validated['status'] === 'verified';
            $nominalLama = (float) $locked->nominal;
            $kampanyeIdLama = $locked->kampanye_id;

            if ($willBeVerified && ! $wasVerified) {
                $validated['diverifikasi_pada'] = now();
            } elseif (! $willBeVerified) {
                $validated['diverifikasi_pada'] = null;
            }

            // Rollback dana_terkumpul kampanye lama jika sebelumnya verified
            if ($wasVerified && $kampanyeIdLama) {
                $oldKampanye = Kampanye::lockForUpdate()->find($kampanyeIdLama);
                if ($oldKampanye) {
                    $oldKampanye->update([
                        'dana_terkumpul' => max(0, (float) $oldKampanye->dana_terkumpul - $nominalLama),
                    ]);
                }
            }

            $locked->update($validated);

            // Tambah dana_terkumpul kampanye baru jika akan/masih verified
            if ($willBeVerified && ! empty($validated['kampanye_id'])) {
                Kampanye::where('id', $validated['kampanye_id'])
                    ->increment('dana_terkumpul', (float) $validated['nominal']);
            }
        });

        return back()->with('success', "Data donasi {$validated['nama_donatur']} berhasil diperbarui.");
    }

    /**
     * Verify a donation (alias for terima).
     */
    public function verifikasi(Donasi $donasi): RedirectResponse
    {
        return $this->terima($donasi);
    }

    /**
     * Accept / Verify a donation.
     */
    public function terima(Donasi $donasi): RedirectResponse
    {
        DB::transaction(function () use ($donasi) {
            $locked = Donasi::lockForUpdate()->findOrFail($donasi->id);
            if ($locked->status === 'verified') {
                return;
            }

            $locked->update([
                'status' => 'verified',
                'diverifikasi_pada' => now(),
            ]);

            if ($locked->kampanye_id) {
                Kampanye::where('id', $locked->kampanye_id)
                    ->increment('dana_terkumpul', (float) $locked->nominal);
            }
        });

        $kode = $donasi->kode_donasi ?: 'DNS-'.str_pad((string) $donasi->id, 4, '0', STR_PAD_LEFT);

        return back()->with('success', "Donasi #{$kode} dari {$donasi->nama_donatur} telah diverifikasi.");
    }

    /**
     * Reject a donation and revert kampanye dana_terkumpul if previously verified.
     */
    public function tolak(Donasi $donasi): RedirectResponse
    {
        DB::transaction(function () use ($donasi) {
            $locked = Donasi::lockForUpdate()->findOrFail($donasi->id);
            if ($locked->status === 'rejected') {
                return;
            }

            if ($locked->status === 'verified' && $locked->kampanye_id) {
                $kampanye = Kampanye::lockForUpdate()->find($locked->kampanye_id);
                if ($kampanye) {
                    $kampanye->update([
                        'dana_terkumpul' => max(0, (float) $kampanye->dana_terkumpul - (float) $locked->nominal),
                    ]);
                }
            }

            $locked->update([
                'status' => 'rejected',
                'diverifikasi_pada' => now(),
            ]);
        });

        $kode = $donasi->kode_donasi ?: 'DNS-'.str_pad((string) $donasi->id, 4, '0', STR_PAD_LEFT);

        return back()->with('warning', "Donasi #{$kode} dari {$donasi->nama_donatur} telah ditolak.");
    }

    /**
     * Delete / reject a donation.
     */
    public function destroy(Donasi $donasi): RedirectResponse
    {
        $nama = $donasi->nama_donatur;

        DB::transaction(function () use ($donasi) {
            $locked = Donasi::lockForUpdate()->findOrFail($donasi->id);
            if ($locked->status === 'verified' && $locked->kampanye_id) {
                $kampanye = Kampanye::lockForUpdate()->find($locked->kampanye_id);
                if ($kampanye) {
                    $kampanye->update([
                        'dana_terkumpul' => max(0, (float) $kampanye->dana_terkumpul - (float) $locked->nominal),
                    ]);
                }
            }

            $locked->delete();
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
