<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}
