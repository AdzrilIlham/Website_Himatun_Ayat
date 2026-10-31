<?php

namespace App\Http\Controllers;

use App\Models\AgendaKegiatan;
use App\Models\BeritaKegiatan;
use Illuminate\Http\Request;

class BeritaKegiatanController extends Controller
{
    /**
     * Menampilkan halaman publik Berita & Kegiatan.
     */
    public function index()
    {
        $daftarBerita = BeritaKegiatan::query()->terbaru()->get();

        $featuredModel = $daftarBerita->firstWhere('unggulan', true) ?? $daftarBerita->first();

        $featured = $featuredModel ? $this->formatBerita($featuredModel) : null;

        $articles = $daftarBerita
            ->reject(fn (BeritaKegiatan $berita) => $featuredModel && $berita->is($featuredModel))
            ->values()
            ->map(fn (BeritaKegiatan $berita) => $this->formatBerita($berita));

        $events = AgendaKegiatan::query()
            ->mendatang()
            ->get()
            ->map(fn (AgendaKegiatan $agenda) => $this->formatAgenda($agenda));

        return view('user.berita-kegiatan.index', compact('featured', 'articles', 'events'));
    }

    /**
     * Format satu berita/kegiatan agar siap dikonsumsi view & Alpine.
     *
     * @return array<string, mixed>
     */
    private function formatBerita(BeritaKegiatan $berita): array
    {
        return [
            'id' => $berita->id,
            'type' => ucfirst($berita->tipe),
            'category' => $berita->kategori,
            'title' => $berita->judul,
            'excerpt' => $berita->ringkasan,
            'date' => $berita->tanggal->locale('id')->translatedFormat('j F Y'),
            'date_iso' => $berita->tanggal->toDateString(),
            'location' => $berita->lokasi,
            'image' => $berita->gambar
                ? asset('images/User/' . $berita->gambar)
                : asset('images/User/potopalingatas.png'),
            'url' => '#',
        ];
    }

    /**
     * Format satu agenda kegiatan untuk kartu "Kegiatan Mendatang".
     *
     * @return array<string, mixed>
     */
    private function formatAgenda(AgendaKegiatan $agenda): array
    {
        return [
            'day' => $agenda->tanggal->format('d'),
            'month' => strtoupper($agenda->tanggal->locale('id')->translatedFormat('M Y')),
            'badge' => $agenda->label,
            'title' => $agenda->judul,
            'time' => trim($agenda->tanggal->locale('id')->translatedFormat('l') . ', ' . $agenda->waktu, ', '),
            'location' => $agenda->lokasi,
            'description' => $agenda->deskripsi,
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BeritaKegiatan $beritaKegiatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BeritaKegiatan $beritaKegiatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BeritaKegiatan $beritaKegiatan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BeritaKegiatan $beritaKegiatan)
    {
        //
    }
}
