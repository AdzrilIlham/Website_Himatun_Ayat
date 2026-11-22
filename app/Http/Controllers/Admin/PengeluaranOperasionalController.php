<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donasi;
use App\Models\PengeluaranOperasional;
use App\Models\PenyaluranDana;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PengeluaranOperasionalController extends Controller
{
    /**
     * Store a newly created pengeluaran operasional record.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori' => 'required|string|max:100',
            'keterangan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1000',
            'tanggal_pengeluaran' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $saldoTersedia = $this->hitungSaldoKasTersedia();
        if ((float) $validated['nominal'] > $saldoTersedia) {
            return back()
                ->withErrors(['nominal' => 'Nominal pengeluaran operasional tidak boleh melebihi sisa Saldo Kas & Cadangan (Rp '.number_format($saldoTersedia, 0, ',', '.').').'])
                ->withInput();
        }

        PengeluaranOperasional::create($validated);

        return back()->with('success', 'Data pengeluaran operasional berhasil ditambahkan.');
    }

    /**
     * Update the specified pengeluaran operasional record.
     */
    public function update(Request $request, PengeluaranOperasional $pengeluaranOperasional): RedirectResponse
    {
        $validated = $request->validate([
            'kategori' => 'required|string|max:100',
            'keterangan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1000',
            'tanggal_pengeluaran' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $saldoTersedia = $this->hitungSaldoKasTersedia($pengeluaranOperasional->id);
        if ((float) $validated['nominal'] > $saldoTersedia) {
            return back()
                ->withErrors(['nominal' => 'Nominal pengeluaran operasional tidak boleh melebihi sisa Saldo Kas & Cadangan (Rp '.number_format($saldoTersedia, 0, ',', '.').').'])
                ->withInput();
        }

        $pengeluaranOperasional->update($validated);

        return back()->with('success', 'Data pengeluaran operasional berhasil diperbarui.');
    }

    /**
     * Remove the specified pengeluaran operasional record.
     */
    public function destroy(PengeluaranOperasional $pengeluaranOperasional): RedirectResponse
    {
        $pengeluaranOperasional->delete();

        return back()->with('success', 'Data pengeluaran operasional berhasil dihapus.');
    }

    /**
     * Hitung sisa saldo kas & cadangan yang tersedia untuk pengeluaran operasional.
     */
    private function hitungSaldoKasTersedia(?int $excludeOperasionalId = null): float
    {
        $totalZis = (float) Donasi::where('status', 'verified')->sum('nominal');
        $totalPenyaluran = (float) PenyaluranDana::sum('nominal');
        $totalOperasional = (float) PengeluaranOperasional::when(
            $excludeOperasionalId !== null,
            fn ($query) => $query->where('id', '!=', $excludeOperasionalId)
        )->sum('nominal');

        return max(0, $totalZis - $totalPenyaluran - $totalOperasional);
    }
}
