<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeritaKegiatan;
use App\Support\BeritaHtml;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $sort = $request->query('sort', 'status');
        $status = $request->query('status');
        $kategori = $request->query('kategori');

        $baseQuery = BeritaKegiatan::query()->where('terjadwal_di_kalender', false);

        $allBerita = (clone $baseQuery)->get();
        $published = $allBerita->filter(fn (BeritaKegiatan $item): bool => $item->status === 'tayang' && $item->tanggal->lte(today()))->count();
        $stats = [
            'total_berita' => $allBerita->count(),
            'reguler' => $published,
            'mendesak' => $allBerita->where('status', 'draft')->count(),
            'dipublikasikan' => $published,
            'draft' => $allBerita->where('status', 'draft')->count(),
            'arsip' => $allBerita->where('status', 'arsip')->count(),
        ];

        $query = clone $baseQuery;

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if ($sort === 'kategori') {
            $query->orderBy('kategori', 'asc')->latest('tanggal')->latest('id');
        } elseif ($sort === 'status') {
            $query->orderByRaw("CASE WHEN status = 'tayang' THEN 1 WHEN status = 'draft' THEN 2 ELSE 3 END")
                ->latest('tanggal')
                ->latest('id');
        } else {
            $query->latest('tanggal')->latest('id');
        }

        $daftarBerita = $query->paginate(10)->withQueryString();

        return view('admin.berita.index', compact('stats', 'daftarBerita', 'search', 'sort', 'status', 'kategori'));
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
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }
        $berita->fill($data)->save();
        if (isset($data['gambar']) && $oldImage && str_starts_with($oldImage, 'berita/')) {
            Storage::disk('public')->delete($oldImage);
        }

        return to_route('admin.berita.index')->with('success', 'Berita berhasil disimpan.');
    }

    public function destroy(BeritaKegiatan $berita): RedirectResponse
    {
        abort_if($berita->terjadwal_di_kalender, 404);
        $berita->delete();
        if ($berita->gambar && str_starts_with($berita->gambar, 'berita/')) {
            Storage::disk('public')->delete($berita->gambar);
        }

        return to_route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}
