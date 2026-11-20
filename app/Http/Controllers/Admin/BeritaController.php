<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeritaKegiatan;
use App\Services\CloudflareR2Service;
use App\Support\BeritaHtml;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function __construct(
        protected CloudflareR2Service $r2Service
    ) {}

    public function index(): View
    {
        $berita = BeritaKegiatan::query()->where('terjadwal_di_kalender', false)->terbaru()->get();
        $published = $berita->filter(fn (BeritaKegiatan $item): bool => $item->status === 'tayang' && $item->tanggal->lte(today()))->count();
        $stats = [
            'total_berita' => $berita->count(),
            'reguler' => $published,
            'mendesak' => $berita->where('status', 'draft')->count(),
            'dipublikasikan' => $published,
            'draft' => $berita->where('status', 'draft')->count(),
            'arsip' => $berita->where('status', 'arsip')->count(),
        ];
        $daftarBerita = $berita->map(fn (BeritaKegiatan $item): array => [
            'id' => $item->id,
            'judul' => $item->judul,
            'kategori' => $item->kategori,
            'status' => $item->status === 'tayang' && $item->tanggal->gt(today()) ? 'Terjadwal' : ucfirst($item->status),
            'tanggal' => $item->tanggal->locale('id')->translatedFormat('d M Y'),
            'thumbnail' => $item->gambar_url,
            'edit_url' => route('admin.berita.edit', $item),
            'delete_url' => route('admin.berita.destroy', $item),
        ]);

        return view('admin.berita.index', compact('stats', 'daftarBerita'));
    }

    public function create(): View
    {
        return $this->form(new BeritaKegiatan);
    }

    public function edit(BeritaKegiatan $berita): View
    {
        abort_if($berita->terjadwal_di_kalender, 404);

        return $this->form($berita);
    }

    private function form(BeritaKegiatan $berita): View
    {
        $kategoriList = collect(['Dakwah & Syiar', 'Gizi & Sembako', 'Pendidikan', 'Kesehatan', 'Sosial & Kemanusiaan', 'Kegiatan Yayasan'])
            ->merge(BeritaKegiatan::query()->distinct()->pluck('kategori'))->unique()->values();

        return view('admin.berita.create', compact('kategoriList', 'berita'));
    }

    public function store(Request $request): RedirectResponse
    {
        return $this->save($request, new BeritaKegiatan);
    }

    public function update(Request $request, BeritaKegiatan $berita): RedirectResponse
    {
        abort_if($berita->terjadwal_di_kalender, 404);

        return $this->save($request, $berita);
    }

    private function save(Request $request, BeritaKegiatan $berita): RedirectResponse
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'tanggal_publikasi' => ['required', 'date_format:Y-m-d'],
            'status' => ['required', 'in:tayang,draft,arsip'],
            'ringkasan' => ['required', 'string', 'max:300'],
            'konten' => ['required', 'string', 'max:5000000'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'tags' => ['nullable', 'array', 'max:20'],
            'tags.*' => ['required', 'string', 'max:50'],
        ]);
        $data['konten'] = BeritaHtml::sanitize($data['konten']);
        if (blank(trim(strip_tags($data['konten']))) && ! str_contains($data['konten'], '<img')) {
            throw ValidationException::withMessages(['konten' => 'Isi berita wajib diisi.']);
        }
        $data['tanggal'] = $data['tanggal_publikasi'];
        unset($data['tanggal_publikasi'], $data['gambar']);
        $data['tags'] = $data['tags'] ?? [];
        if (! $berita->exists) {
            $base = Str::slug($data['judul']) ?: 'berita';
            $slug = $base;
            $counter = 1;
            while (BeritaKegiatan::where('slug', $slug)->exists()) {
                $slug = $base.'-'.$counter++;
            }
            $data['slug'] = $slug;
        }
        $oldImage = $berita->gambar;
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $this->r2Service->uploadPublic($request->file('gambar'), 'berita');
        }
        $berita->fill($data)->save();
        if (isset($data['gambar']) && $oldImage && (str_starts_with($oldImage, 'berita/') || str_starts_with($oldImage, 'storage/berita/'))) {
            $this->r2Service->deletePublic($oldImage);
        }

        return to_route('admin.berita.index')->with('success', 'Berita berhasil disimpan.');
    }

    public function destroy(BeritaKegiatan $berita): RedirectResponse
    {
        abort_if($berita->terjadwal_di_kalender, 404);
        $berita->delete();
        if ($berita->gambar && (str_starts_with($berita->gambar, 'berita/') || str_starts_with($berita->gambar, 'storage/berita/'))) {
            $this->r2Service->deletePublic($berita->gambar);
        }

        return to_route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}
