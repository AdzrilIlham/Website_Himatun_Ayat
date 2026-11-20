<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeritaKegiatan;
use App\Services\CloudinaryImageService;
use App\Support\BeritaHtml;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function __construct(
        protected CloudinaryImageService $images
    ) {}

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
            'gambar' => ['nullable', 'array:id,public_id'],
            'gambar_konten' => ['sometimes', 'array', 'max:10'],
            'gambar_konten.*' => ['required', 'array:id,public_id'],
            'tags' => ['nullable', 'array', 'max:20'],
            'tags.*' => ['required', 'string', 'max:50'],
        ]);
        $data['konten'] = BeritaHtml::sanitize($data['konten']);
        $sources = BeritaHtml::imageSources($data['konten']);
        $legacySources = BeritaHtml::imageSources(BeritaHtml::sanitize($berita->konten ?? ''));
        foreach (array_diff($sources, $legacySources) as $source) {
            if (str_starts_with(strtolower($source), 'data:')) {
                throw ValidationException::withMessages(['konten' => 'Gunakan tombol sisipkan gambar untuk menambah gambar ke isi berita.']);
            }
        }
        if (blank(trim(strip_tags($data['konten']))) && $sources === []) {
            throw ValidationException::withMessages(['konten' => 'Isi berita wajib diisi.']);
        }
        $data['tanggal'] = $data['tanggal_publikasi'];
        unset($data['tanggal_publikasi']);
        $coverReference = Arr::pull($data, 'gambar');
        $inlineReferences = Arr::pull($data, 'gambar_konten', []);
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
        $cover = null;
        $removedInline = DB::transaction(function () use ($request, $berita, $data, $sources, $coverReference, $inlineReferences, &$cover): array {
            $newCover = $coverReference ? $this->images->consume($request, $coverReference, 'gambar', $berita->id) : null;
            $newInline = [];
            foreach ($inlineReferences as $reference) {
                $asset = $this->images->consume($request, $reference, 'gambar_konten', $berita->id);
                if (! in_array($asset['secure_url'], $sources, true)) {
                    throw ValidationException::withMessages(['konten' => 'Gambar yang diunggah harus ada di isi berita.']);
                }
                $newInline[] = $asset;
            }

            $record = $berita->exists ? BeritaKegiatan::lockForUpdate()->findOrFail($berita->id) : $berita;
            $existingSources = BeritaHtml::imageSources(BeritaHtml::sanitize($record->konten ?? ''));
            $verifiedSources = array_column($newInline, 'secure_url');
            if (array_diff($sources, $existingSources, $verifiedSources) !== []) {
                throw ValidationException::withMessages(['konten' => 'Gambar baru di isi berita harus diunggah melalui tombol sisipkan gambar.']);
            }
            $previousInline = collect($record->gambar_konten_cloudinary ?? []);
            $record->fill($data)->forceFill([
                'gambar_konten_cloudinary' => $previousInline->whereInStrict('secure_url', $sources)->concat($newInline)->values()->all(),
            ]);
            if ($newCover !== null) {
                $cover = [$record->gambar, $record->gambar_cloudinary];
                $record->forceFill(['gambar' => $newCover['secure_url'], 'gambar_cloudinary' => $newCover]);
            }
            $record->save();

            return $previousInline->whereNotInStrict('secure_url', $sources)->all();
        });
        if ($cover !== null) {
            $this->deleteCover(...$cover);
        }
        foreach ($removedInline as $asset) {
            $this->images->deleteAsset($asset);
        }

        return to_route('admin.berita.index')->with('success', 'Berita berhasil disimpan.');
    }

    public function destroy(BeritaKegiatan $berita): RedirectResponse
    {
        abort_if($berita->terjadwal_di_kalender, 404);
        $berita->delete();
        $this->deleteCover($berita->gambar, $berita->gambar_cloudinary);
        foreach ($berita->gambar_konten_cloudinary ?? [] as $asset) {
            $this->images->deleteAsset($asset);
        }

        return to_route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }

    private function deleteCover(?string $path, ?array $asset): void
    {
        if ($asset !== null || ($path && Str::startsWith($path, ['berita/', 'storage/berita/']))) {
            $this->images->deletePublic($path, $asset);
        }
    }
}
