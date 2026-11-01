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
            'url' => route('berita-kegiatan.show', $berita->slug),
        ];
    }

    /**
     * Format satu berita/kegiatan untuk halaman detail.
     *
     * @return array<string, mixed>
     */
    private function formatDetail(BeritaKegiatan $berita): array
    {
        return [
            ...$this->formatBerita($berita),
            'slug' => $berita->slug,
            'content_html' => $this->renderContent($berita),
            'tags' => $this->resolveTags($berita),
            'image_caption' => $berita->keterangan_gambar
                ?: 'Dokumentasi Yayasan Himmatun Ayat Bandung',
        ];
    }

    /**
     * Isi artikel. Data lama yang belum memiliki konten tetap mendapat
     * rangkuman yang layak tampil di halaman detail.
     */
    private function renderContent(BeritaKegiatan $berita): string
    {
        if (filled($berita->konten)) {
            return $berita->konten;
        }

        $excerpt = e($berita->ringkasan);
        $category = e(strtolower($berita->kategori));

        return <<<HTML
            <p class="lead">{$excerpt}</p>
            <p>Catatan ini menjadi bagian dari dokumentasi kegiatan {$category} Yayasan Himmatun Ayat Bandung bersama anak asuh dan keluarga binaan. Setiap langkah kecil yang dijalani bersama diharapkan membawa manfaat nyata bagi keseharian mereka.</p>
            <h2>Bersama merawat amanah</h2>
            <p>Dukungan para donatur dan mitra menjadi bagian penting dari keberlangsungan program ini. Amanah yang dititipkan tidak hanya memenuhi kebutuhan sesaat, tetapi juga menemani proses tumbuh dan belajar adik-adik asuh.</p>
            <div class="callout">
                <p class="callout-title">Terima kasih telah menjadi bagian dari kebaikan ini.</p>
                <p class="callout-text">Mari terus menemani langkah kecil mereka menuju masa depan yang lebih baik.</p>
            </div>
            HTML;
    }

    /**
     * @return array<int, string>
     */
    private function resolveTags(BeritaKegiatan $berita): array
    {
        $tags = $berita->tags ?: [$berita->kategori, ucfirst($berita->tipe), 'Anak Asuh'];

        return array_values(array_unique(array_filter($tags)));
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
        $article = $this->formatDetail($beritaKegiatan);

        $related = BeritaKegiatan::query()
            ->terbaru()
            ->whereKeyNot($beritaKegiatan->getKey())
            ->take(3)
            ->get()
            ->map(fn (BeritaKegiatan $berita) => $this->formatBerita($berita));

        return view('user.berita-kegiatan.show', [
            'article' => $article,
            'related' => $related,
            'title' => $beritaKegiatan->judul . ' — Yayasan Himmatun Ayat Bandung',
        ]);
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
