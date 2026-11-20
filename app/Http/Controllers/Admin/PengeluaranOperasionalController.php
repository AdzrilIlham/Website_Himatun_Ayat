<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengeluaranOperasional;
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
}
