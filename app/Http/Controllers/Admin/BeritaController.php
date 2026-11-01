<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Menampilkan daftar CMS Berita.
     */
    public function index(Request $request)
    {
        $stats = [
            'total_berita' => 8,
            'reguler' => 6,
            'mendesak' => 2,
            'dipublikasikan' => 24,
            'draft' => 2,
            'arsip' => 2,
        ];

        $daftarBerita = [
            [
                'id' => 1,
                'no' => 1,
                'judul' => 'Kafalah Guru Ngaji',
                'kategori' => 'Dakwah & Syiar',
                'kategori_slug' => 'dakwah-syiar',
                'status' => 'Selesai',
                'tanggal' => '01 Feb 2025 - 28 Feb 2025',
                'thumbnail' => null,
            ],
            [
                'id' => 2,
                'no' => 2,
                'judul' => 'Kafalah Guru Ngaji',
                'kategori' => 'Gizi & Sembako',
                'kategori_slug' => 'gizi-sembako',
                'status' => 'Aktif',
                'tanggal' => '01 Feb 2025 - 28 Feb 2025',
                'thumbnail' => null,
            ],
            [
                'id' => 3,
                'no' => 3,
                'judul' => 'Kafalah Guru Ngaji',
                'kategori' => 'Gizi & Sembako',
                'kategori_slug' => 'gizi-sembako',
                'status' => 'Aktif',
                'tanggal' => '01 Feb 2025 - 28 Feb 2025',
                'thumbnail' => null,
            ],
            [
                'id' => 4,
                'no' => 4,
                'judul' => 'Kafalah Guru Ngaji',
                'kategori' => 'Gizi & Sembako',
                'kategori_slug' => 'gizi-sembako',
                'status' => 'Aktif',
                'tanggal' => '01 Feb 2025 - 28 Feb 2025',
                'thumbnail' => null,
            ],
            [
                'id' => 5,
                'no' => 5,
                'judul' => 'Kafalah Guru Ngaji',
                'kategori' => 'Gizi & Sembako',
                'kategori_slug' => 'gizi-sembako',
                'status' => 'Aktif',
                'tanggal' => '01 Feb 2025 - 28 Feb 2025',
                'thumbnail' => null,
            ],
            [
                'id' => 6,
                'no' => 6,
                'judul' => 'Kafalah Guru Ngaji',
                'kategori' => 'Gizi & Sembako',
                'kategori_slug' => 'gizi-sembako',
                'status' => 'Aktif',
                'tanggal' => '01 Feb 2025 - 28 Feb 2025',
                'thumbnail' => null,
            ],
            [
                'id' => 7,
                'no' => 7,
                'judul' => 'Kafalah Guru Ngaji',
                'kategori' => 'Gizi & Sembako',
                'kategori_slug' => 'gizi-sembako',
                'status' => 'Aktif',
                'tanggal' => '01 Feb 2025 - 28 Feb 2025',
                'thumbnail' => null,
            ],
        ];

        return view('admin.berita.index', compact('stats', 'daftarBerita'));
    }

    /**
     * Menampilkan form penambahan berita baru.
     */
    public function create()
    {
        $kategoriList = [
            'Dakwah & Syiar',
            'Gizi & Sembako',
            'Pendidikan',
            'Kesehatan',
            'Sosial & Kemanusiaan',
            'Kegiatan Yayasan',
        ];

        return view('admin.berita.create', compact('kategoriList'));
    }
}
