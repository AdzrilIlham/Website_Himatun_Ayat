<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BeritaKegiatan;
use App\Models\Donasi;
use App\Models\Kampanye;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LandingPageController extends Controller
{
    /**
     * Menampilkan halaman landing page utama untuk user / publik.
     */
    public function index()
    {
        $kampanyeAktif = Kampanye::query()
            ->where('status', 'aktif')
            ->withCount(['donasi as donatur_count' => fn (Builder $query): Builder => $query->where('status', 'verified')])
            ->orderByDesc('id')
            ->take(4)
            ->get();

        $programUtama = $kampanyeAktif->first();
        $paketKilat = $kampanyeAktif->slice(1, 3)->values();

        $beritaTerbaru = $this->beritaTerbaru();

        return view('user.landing-page.index', [
            'recentDonors' => $this->recentDonors(),
            'programUtama' => $programUtama ? $this->formatKampanye($programUtama, 260) : null,
            'paketKilat' => $paketKilat->map(fn (Kampanye $kampanye): array => $this->formatKampanye($kampanye, 150)),
            'beritaTerbaru' => $beritaTerbaru,
            'kategoriBerita' => $beritaTerbaru->pluck('kategori')->unique()->values(),
            'title' => 'Yayasan Himmatun Ayat Bandung - Mengukir Senyum dan Masa Depan Anak Yatim',
        ]);
    }

    /**
     * Donasi terverifikasi terbaru untuk ticker di landing page.
     *
     * @return Collection<int, array<string, string>>
     */
    private function recentDonors(): Collection
    {
        return Donasi::query()
            ->where('status', 'verified')
            ->latest()
            ->take(6)
            ->get()
            ->map(fn (Donasi $donasi): array => [
                'name' => filled($donasi->nama_donatur) ? $donasi->nama_donatur : 'Hamba Allah',
                'amount' => 'Rp'.number_format((float) $donasi->nominal, 0, ',', '.'),
                'time' => $donasi->created_at?->locale('id')->diffForHumans() ?? '-',
            ]);
    }

    /**
     * Empat kabar terbaru yang sudah tayang dan tidak dipakai di kalender kegiatan.
     *
     * @return Collection<int, array<string, string>>
     */
    private function beritaTerbaru(): Collection
    {
        return BeritaKegiatan::query()
            ->dipublikasikan()
            ->where('terjadwal_di_kalender', false)
            ->terbaru()
            ->take(4)
            ->get()
            ->map(fn (BeritaKegiatan $berita): array => [
                'kategori' => $berita->kategori,
                'judul' => $berita->judul,
                'ringkasan' => $berita->ringkasan,
                'tanggal' => $berita->tanggal->locale('id')->translatedFormat('j M Y'),
                'lokasi' => $berita->lokasi,
                'image' => $berita->gambar_url,
                'url' => route('berita-kegiatan.show', $berita->slug),
            ]);
    }

    /**
     * Format satu kampanye agar siap dikonsumsi landing page.
     *
     * @return array<string, mixed>
     */
    private function formatKampanye(Kampanye $kampanye, int $excerptLength): array
    {
        return [
            'judul' => $kampanye->judul,
            'kategori' => $kampanye->kategori,
            'deskripsi' => Str::limit(strip_tags((string) $kampanye->deskripsi), $excerptLength),
            'image' => asset($kampanye->banner_path ?: 'images/User/Renovasi Ruang Belajar.png'),
            'donatur' => (int) $kampanye->donatur_count,
            'terkumpul' => 'Rp '.number_format($kampanye->total_terkumpul, 0, ',', '.'),
            'target' => 'Rp '.number_format((float) $kampanye->target_dana, 0, ',', '.'),
            'persentase' => (int) round($kampanye->persentase_tercapai),
            'url' => route('kampanye.index', ['program' => $kampanye->slug]),
        ];
    }
}
