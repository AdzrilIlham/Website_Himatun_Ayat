<?php

namespace App\Http\Controllers;

use App\Models\BeritaKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BeritaKegiatanController extends Controller
{
    /**
     * Menampilkan halaman publik Berita & Kegiatan.
     */
    public function index()
    {
        $hasKalenderColumn = Schema::hasColumn('berita_kegiatans', 'terjadwal_di_kalender');

        $beritaQuery = BeritaKegiatan::query()->terbaru();
        if ($hasKalenderColumn) {
            $beritaQuery->where('terjadwal_di_kalender', false);
        }

        $daftarBerita = $beritaQuery->get();
        if ($daftarBerita->isEmpty()) {
            $daftarBerita = BeritaKegiatan::query()->terbaru()->get();
        }

        $featuredModel = $daftarBerita->firstWhere('unggulan', true) ?? $daftarBerita->first();

        $featured = $featuredModel ? $this->formatBerita($featuredModel) : null;

        $articles = $daftarBerita
            ->reject(fn (BeritaKegiatan $berita) => $featuredModel && $berita->is($featuredModel))
            ->values()
            ->map(fn (BeritaKegiatan $berita) => $this->formatBerita($berita));

        $startOfCurrentMonth = now()->startOfMonth()->toDateString();

        if ($hasKalenderColumn) {
            // Hapus data jadwal kalender dari bulan-bulan sebelumnya
            $deletedOldEvents = BeritaKegiatan::query()
                ->where('terjadwal_di_kalender', true)
                ->whereDate('tanggal', '<', $startOfCurrentMonth)
                ->delete();

            // Jika sebelumnya masih memakai data seeder bulan lama, sinkronkan ke bulan berjalan
            if ($deletedOldEvents > 0 && ! BeritaKegiatan::query()->where('terjadwal_di_kalender', true)->whereDate('tanggal', '>=', $startOfCurrentMonth)->exists()) {
                (new \Database\Seeders\BeritaKegiatanSeeder())->run();
            }
        }

        $kalenderQuery = BeritaKegiatan::query()
            ->where('tipe', 'kegiatan')
            ->whereDate('tanggal', '>=', $startOfCurrentMonth);

        if ($hasKalenderColumn) {
            $kalenderQuery->where('terjadwal_di_kalender', true);
        }

        $kalenderModels = $kalenderQuery
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        $calendarEvents = $kalenderModels
            ->map(fn (BeritaKegiatan $kegiatan) => $this->formatKalenderKegiatan($kegiatan))
            ->values();

        if ($calendarEvents->isEmpty()) {
            $calendarEvents = collect($this->defaultKalenderKegiatan());
        }

        $events = $calendarEvents;

        return view('user.berita-kegiatan.index', compact('featured', 'articles', 'events', 'calendarEvents'));
    }

    /**
     * Simpan pengajuan kegiatan baru dari form publik kalender.
     */
    public function storePengajuan(Request $request)
    {
        $validated = $request->validate([
            'nama_pemohon'     => 'required|string|max:255',
            'nomor_whatsapp'   => 'required|string|max:50',
            'tanggal'          => 'required|date',
            'waktu'            => 'required|string|max:100',
            'jenis_kegiatan'   => 'required|string|max:150',
            'rencana_kegiatan' => 'required|string',
        ]);

        if (\Illuminate\Support\Facades\Schema::hasTable('pengajuan_kegiatans')) {
            \App\Models\PengajuanKegiatan::create([
                ...$validated,
                'status' => 'menunggu',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan kegiatan berhasil disimpan.',
        ]);
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
     * Format satu kegiatan dari tabel berita_kegiatans untuk Kalender Kegiatan.
     *
     * @return array<string, mixed>
     */
    private function formatKalenderKegiatan(BeritaKegiatan $kegiatan): array
    {
        $waktu = $kegiatan->waktu_teks;

        if (blank($waktu) && ($kegiatan->waktu_mulai || $kegiatan->waktu_selesai)) {
            $mulai = $kegiatan->waktu_mulai
                ? substr(str_replace(':', '.', (string) $kegiatan->waktu_mulai), 0, 5)
                : '';
            $selesai = $kegiatan->waktu_selesai
                ? substr(str_replace(':', '.', (string) $kegiatan->waktu_selesai), 0, 5)
                : '';
            $waktu = trim($mulai . ($mulai && $selesai ? '–' : '') . $selesai) . ' WIB';
        }

        $deskripsi = filled($kegiatan->ringkasan)
            ? $kegiatan->ringkasan
            : trim(strip_tags((string) $kegiatan->konten));

        $badgeTitle = Str::contains($kegiatan->judul, ' & ')
            ? trim(Str::before($kegiatan->judul, ' & '))
            : Str::limit($kegiatan->judul, 18, '');

        return [
            'id' => $kegiatan->id,
            'date_iso' => $kegiatan->tanggal->toDateString(),
            'day' => (int) $kegiatan->tanggal->format('j'),
            'month' => (int) $kegiatan->tanggal->format('n') - 1,
            'year' => (int) $kegiatan->tanggal->format('Y'),
            'date_formatted' => $kegiatan->tanggal->locale('id')->translatedFormat('l, j F Y'),
            'title' => $kegiatan->judul,
            'badge_title' => $badgeTitle,
            'organizer' => $kegiatan->penyelenggara ?: 'Pengurus Yayasan Himmatun Ayat Bandung',
            'time' => $waktu ?: '09.00–12.00 WIB',
            'location' => $kegiatan->lokasi ?: 'Asrama Cibiru, Bandung',
            'description' => $deskripsi,
            'category' => $kegiatan->kategori,
        ];
    }

    /**
     * Fallback data kalender pada bulan berjalan jika migrasi/seeder terbaru belum dijalankan.
     *
     * @return array<int, array<string, mixed>>
     */
    private function defaultKalenderKegiatan(): array
    {
        $currentMonth = now()->startOfMonth();
        $date12 = $currentMonth->copy()->day(12);
        $date19 = $currentMonth->copy()->day(19);
        $date25 = $currentMonth->copy()->day(25);

        return [
            [
                'id' => 101,
                'date_iso' => $date12->toDateString(),
                'day' => 12,
                'month' => (int) $date12->format('n') - 1,
                'year' => (int) $date12->format('Y'),
                'date_formatted' => $date12->locale('id')->translatedFormat('l, j F Y'),
                'title' => 'Khotmil Quran & Doa Bersama Santri',
                'badge_title' => 'Khotmil Quran',
                'organizer' => 'Pengurus Asrama & Pembimbing Tahfidz',
                'time' => '08.00–11.00 WIB',
                'location' => 'Asrama Cibiru, Bandung',
                'description' => "Majelis khataman Al-Qur'an bulanan bersama seluruh santri asuh, pembimbing tahfidz, serta pembacaan doa keberkahan bagi para donatur dan muhsinin.",
                'category' => 'Tahfidz Quran',
            ],
            [
                'id' => 102,
                'date_iso' => $date19->toDateString(),
                'day' => 19,
                'month' => (int) $date19->format('n') - 1,
                'year' => (int) $date19->format('Y'),
                'date_formatted' => $date19->locale('id')->translatedFormat('l, j F Y'),
                'title' => 'Doa Bersama & Santunan Bulanan Santri',
                'badge_title' => 'Doa Bersama',
                'organizer' => 'HIMA RPL UPI Cibiru',
                'time' => '13.30–15.00 WIB',
                'location' => 'Asrama Cibiru, Bandung',
                'description' => 'Silaturahmi berkah bersama seluruh anak asuh, pembacaan tahlil & istighotsah bersama, tausiyah santapan rohani, serta penyaluran santunan amanah donatur.',
                'category' => 'Sosial',
            ],
            [
                'id' => 103,
                'date_iso' => $date25->toDateString(),
                'day' => 25,
                'month' => (int) $date25->format('n') - 1,
                'year' => (int) $date25->format('Y'),
                'date_formatted' => $date25->locale('id')->translatedFormat('l, j F Y'),
                'title' => 'Kelas Relawan & Inspirasi Profesi',
                'badge_title' => 'Kelas Relawan',
                'organizer' => 'Komunitas Relawan Muda Bandung',
                'time' => '09.00–12.00 WIB',
                'location' => 'Ruang Belajar Cibiru, Bandung',
                'description' => 'Berbagi cerita profesi, pendampingan belajar interaktif, dan sesi motivasi kreatif bersama kakak-kakak relawan untuk menumbuhkan cita-cita anak asuh.',
                'category' => 'Pendidikan',
            ],
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
