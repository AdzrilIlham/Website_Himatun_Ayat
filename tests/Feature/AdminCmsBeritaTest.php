<?php

namespace Tests\Feature;

use App\Models\BeritaKegiatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCmsBeritaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->withTwoFactor()->create();
        $this->actingAs($admin);
    }

    /**
     * Test halaman index CMS Berita dapat diakses dengan sukses.
     */
    public function test_admin_cms_berita_index_rendered_successfully(): void
    {
        BeritaKegiatan::factory()->create(['judul' => 'Kafalah Guru Ngaji']);
        $response = $this->get('/admin/cms-berita');

        $response->assertStatus(200);
        $response->assertSee('CMS Berita');
        $response->assertSee('TOTAL BERITA');
        $response->assertSee('DI PUBLIKASIKAN');
        $response->assertSee('DRAFT');
        $response->assertSee('ARSIP');
        $response->assertSee('Kafalah Guru Ngaji');
        $response->assertSee('Tambah Berita');
        $response->assertSee('Navigasi Halaman');
        $response->assertSee('aria-current="page"', false);
    }

    /**
     * Test halaman form Tambah Berita dapat diakses dengan sukses.
     */
    public function test_admin_cms_berita_create_rendered_successfully(): void
    {
        $response = $this->get('/admin/cms-berita/create');

        $response->assertStatus(200);
        $response->assertSee('Tambah Berita');
        $response->assertSee('Informasi Utama');
        $response->assertSee('Gambar Utama');
        $response->assertSee('Ringkasan Berita');
        $response->assertSee('Isi Berita');
        $response->assertSee('Kembali ke Daftar');
        $response->assertSee('Simpan Berita');
    }

    /**
     * Test pagination berfungsi pada daftar berita.
     */
    public function test_admin_cms_berita_pagination_works(): void
    {
        BeritaKegiatan::factory()->count(15)->create([
            'terjadwal_di_kalender' => false,
        ]);

        $response = $this->get(route('admin.berita.index'));
        $response->assertStatus(200);
        $response->assertSee('Menampilkan 1 - 10 dari 15 Berita');
        $response->assertSee('page=2');

        $responsePage2 = $this->get(route('admin.berita.index', ['page' => 2]));
        $responsePage2->assertStatus(200);
        $responsePage2->assertSee('Menampilkan 11 - 15 dari 15 Berita');
    }

    /**
     * Test pagination mempertahankan query pencarian dan pengurutan.
     */
    public function test_admin_cms_berita_pagination_retains_search_and_sort(): void
    {
        BeritaKegiatan::factory()->count(12)->create([
            'judul' => 'Santunan Khusus Dhuafa',
            'terjadwal_di_kalender' => false,
        ]);
        BeritaKegiatan::factory()->count(5)->create([
            'judul' => 'Agenda Lain',
            'terjadwal_di_kalender' => false,
        ]);

        $response = $this->get(route('admin.berita.index', [
            'search' => 'Santunan',
            'sort' => 'kategori',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Menampilkan 1 - 10 dari 12 Berita');
        $response->assertSee('search=Santunan');
        $response->assertSee('sort=kategori');
        $response->assertSee('page=2');

        $responsePage2 = $this->get(route('admin.berita.index', [
            'search' => 'Santunan',
            'sort' => 'kategori',
            'page' => 2,
        ]));
        $responsePage2->assertStatus(200);
        $responsePage2->assertSee('Menampilkan 11 - 12 dari 12 Berita');
    }
}
