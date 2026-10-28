<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AnakAsuhExport;
use App\Http\Controllers\Controller;
use App\Models\AnakAsuh;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AnakAsuhController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        // 1. Ringkasan 5 Statistik (Sesuai Desain Tangkapan Layar: Jiwa Aktif)
        $totalAktif = AnakAsuh::where('status_asuhan', 'Aktif')->count();
        $totalAll = AnakAsuh::count();
        $baseTotal = max($totalAktif ?: $totalAll, 1);

        // Filter per jenjang pada santri aktif (atau fallback ke semua jika belum ada status aktif)
        $sdQuery = AnakAsuh::where(function ($q) {
            $q->where('pendidikan_terakhir', 'SD')
              ->orWhere('pendidikan_terakhir', 'like', '%SD%')
              ->orWhere('pendidikan_terakhir', 'like', '%MI%');
        });
        if ($totalAktif > 0) {
            $sdQuery->where('status_asuhan', 'Aktif');
        }
        $sdCount = $sdQuery->count();

        $smpQuery = AnakAsuh::where(function ($q) {
            $q->where('pendidikan_terakhir', 'SMP')
              ->orWhere('pendidikan_terakhir', 'like', '%SMP%')
              ->orWhere('pendidikan_terakhir', 'like', '%MTS%');
        });
        if ($totalAktif > 0) {
            $smpQuery->where('status_asuhan', 'Aktif');
        }
        $smpCount = $smpQuery->count();

        $smaQuery = AnakAsuh::where(function ($q) {
            $q->where('pendidikan_terakhir', 'SMA')
              ->orWhere('pendidikan_terakhir', 'like', '%SMA%')
              ->orWhere('pendidikan_terakhir', 'like', '%SMK%')
              ->orWhere('pendidikan_terakhir', 'like', '%MA%');
        });
        if ($totalAktif > 0) {
            $smaQuery->where('status_asuhan', 'Aktif');
        }
        $smaCount = $smaQuery->count();

        $ptQuery = AnakAsuh::where(function ($q) {
            $q->where('pendidikan_terakhir', 'Perguruan Tinggi')
              ->orWhere('pendidikan_terakhir', 'like', '%Kuliah%')
              ->orWhere('pendidikan_terakhir', 'like', '%Perguruan%')
              ->orWhere('pendidikan_terakhir', 'like', '%Diploma%');
        });
        if ($totalAktif > 0) {
            $ptQuery->where('status_asuhan', 'Aktif');
        }
        $ptCount = $ptQuery->count();

        $stats = [
            'total_aktif' => $totalAktif ?: $totalAll,
            'sd' => [
                'count' => $sdCount,
                'persen' => round(($sdCount / $baseTotal) * 100),
            ],
            'smp' => [
                'count' => $smpCount,
                'persen' => round(($smpCount / $baseTotal) * 100),
            ],
            'sma' => [
                'count' => $smaCount,
                'persen' => round(($smaCount / $baseTotal) * 100),
            ],
            'pt' => [
                'count' => $ptCount,
                'persen' => round(($ptCount / $baseTotal) * 100),
            ],
        ];

        // 2. Query Data Anak Asuh dengan Filter & Pencarian
        $query = AnakAsuh::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nama_panggilan', 'like', "%{$search}%");
            });
        }

        if ($status && $status !== 'Semua Status' && $status !== 'all') {
            if (in_array($status, ['SD', 'SMP', 'SMA', 'Perguruan Tinggi'])) {
                $query->where(function ($q) use ($status) {
                    $q->where('pendidikan_terakhir', $status)
                      ->orWhere('pendidikan_terakhir', 'like', "%{$status}%");
                });
            } else {
                $query->where('status_asuhan', $status);
            }
        }

        // Paginasi 15 item per halaman sesuai permintaan
        $anakAsuhList = $query->latest('id')->paginate(15)->withQueryString();

        return view('admin.anak-asuh.index', compact(
            'stats',
            'anakAsuhList',
            'search',
            'status'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lengkap'        => 'required|string|max:255',
            'nama_panggilan'      => 'nullable|string|max:100',
            'jenis_kelamin'       => 'required|in:L,P',
            'tanggal_lahir'       => 'nullable|date',
            'pendidikan_terakhir' => 'nullable|string|max:50',
            'status_asuhan'       => 'required|string|max:50',
            'keterangan'          => 'nullable|string',
            'foto'                => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('anak-asuh', 'public');
            $validated['foto_path'] = 'storage/' . $path;
        }

        AnakAsuh::create($validated);

        return redirect()->route('admin.anak-asuh.index')
            ->with('success', 'Data anak asuh berhasil ditambahkan.');
    }

    public function update(Request $request, AnakAsuh $anakAsuh): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lengkap'        => 'required|string|max:255',
            'nama_panggilan'      => 'nullable|string|max:100',
            'jenis_kelamin'       => 'required|in:L,P',
            'tanggal_lahir'       => 'nullable|date',
            'pendidikan_terakhir' => 'nullable|string|max:50',
            'status_asuhan'       => 'required|string|max:50',
            'keterangan'          => 'nullable|string',
            'foto'                => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($anakAsuh->foto_path && str_starts_with($anakAsuh->foto_path, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $anakAsuh->foto_path));
            }
            $path = $request->file('foto')->store('anak-asuh', 'public');
            $validated['foto_path'] = 'storage/' . $path;
        }

        $anakAsuh->update($validated);

        return redirect()->route('admin.anak-asuh.index')
            ->with('success', "Data anak asuh {$anakAsuh->nama_lengkap} berhasil diperbarui.");
    }

    public function destroy(AnakAsuh $anakAsuh): RedirectResponse
    {
        if ($anakAsuh->foto_path && str_starts_with($anakAsuh->foto_path, 'storage/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $anakAsuh->foto_path));
        }

        $nama = $anakAsuh->nama_lengkap;
        $anakAsuh->delete();

        return redirect()->route('admin.anak-asuh.index')
            ->with('success', "Data anak asuh {$nama} berhasil dihapus.");
    }

    public function exportExcel(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $filename = 'data-anak-asuh-himmatun-ayat-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(new AnakAsuhExport($search, $status), $filename);
    }

    public function exportPdf(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status');

        $query = AnakAsuh::query();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nama_panggilan', 'like', "%{$search}%");
            });
        }
        if ($status && $status !== 'Semua Status' && $status !== 'all') {
            if (in_array($status, ['SD', 'SMP', 'SMA', 'Perguruan Tinggi'])) {
                $query->where(function ($q) use ($status) {
                    $q->where('pendidikan_terakhir', $status)
                      ->orWhere('pendidikan_terakhir', 'like', "%{$status}%");
                });
            } else {
                $query->where('status_asuhan', $status);
            }
        }

        $anakAsuh = $query->latest('id')->get();
        $filename = 'data-anak-asuh-himmatun-ayat-' . now()->format('Ymd-His') . '.pdf';

        $pdf = Pdf::loadView('admin.anak-asuh.pdf', compact('anakAsuh', 'search', 'status'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }
}
