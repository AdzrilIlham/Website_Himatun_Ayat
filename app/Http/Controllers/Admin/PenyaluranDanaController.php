<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\PengeluaranOperasional;
use App\Models\PenyaluranDana;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PenyaluranDanaController extends Controller
{
    /**
     * Store a newly created penyaluran dana record.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'klaster_asnaf' => 'required|in:pendidikan,nutrisi,kesehatan,asrama,lainnya',
            'keterangan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1000',
            'tanggal_penyaluran' => 'required|date',
            'penerima_manfaat' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $saldoTersedia = $this->hitungSaldoKasTersedia();
        if ((float) $validated['nominal'] > $saldoTersedia) {
            return back()
                ->withErrors(['nominal' => 'Nominal penyaluran program tidak boleh melebihi sisa Saldo Kas & Cadangan (Rp '.number_format($saldoTersedia, 0, ',', '.').').'])
                ->withInput();
        }

        PenyaluranDana::create($validated);

        return back()->with('success', 'Data penyaluran dana berhasil ditambahkan.');
    }

    /**
     * Update the specified penyaluran dana record.
     */
    public function update(Request $request, PenyaluranDana $penyaluranDana): RedirectResponse
    {
        $validated = $request->validate([
            'klaster_asnaf' => 'required|in:pendidikan,nutrisi,kesehatan,asrama,lainnya',
            'keterangan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1000',
            'tanggal_penyaluran' => 'required|date',
            'penerima_manfaat' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $saldoTersedia = $this->hitungSaldoKasTersedia($penyaluranDana->id);
        if ((float) $validated['nominal'] > $saldoTersedia) {
            return back()
                ->withErrors(['nominal' => 'Nominal penyaluran program tidak boleh melebihi sisa Saldo Kas & Cadangan (Rp '.number_format($saldoTersedia, 0, ',', '.').').'])
                ->withInput();
        }

        $penyaluranDana->update($validated);

        return back()->with('success', 'Data penyaluran dana berhasil diperbarui.');
    }

    /**
     * Remove the specified penyaluran dana record.
     */
    public function destroy(PenyaluranDana $penyaluranDana): RedirectResponse
    {
        $penyaluranDana->delete();

        return back()->with('success', 'Data penyaluran dana berhasil dihapus.');
    }

    /**
     * Hitung sisa saldo kas & cadangan yang tersedia untuk penyaluran.
     */
    private function hitungSaldoKasTersedia(?int $excludePenyaluranId = null): float
    {
        $totalZis = (float) Donasi::where('status', 'verified')->sum('nominal');
        $totalPenyaluran = (float) PenyaluranDana::when(
            $excludePenyaluranId !== null,
            fn ($query) => $query->where('id', '!=', $excludePenyaluranId)
        )->sum('nominal');
        $totalOperasional = (float) PengeluaranOperasional::sum('nominal');

        return max(0, $totalZis - $totalPenyaluran - $totalOperasional);
    }
}
