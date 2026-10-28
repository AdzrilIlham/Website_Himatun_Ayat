<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminCmsBeritaTest extends TestCase
{
    /**
     * Test halaman index CMS Berita dapat diakses dengan sukses.
     */
    public function test_admin_cms_berita_index_rendered_successfully(): void
    {
        $response = $this->get('/admin/cms-berita');

        $response->assertStatus(200);
        $response->assertSee('CMS Berita');
        $response->assertSee('TOTAL BERITA');
        $response->assertSee('BERITA DI PUBLIKASIKAN');
        $response->assertSee('DRAFT');
        $response->assertSee('ARSIP');
        $response->assertSee('Kafalah Guru Ngaji');
        $response->assertSee('+ Tambah Berita', false);
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
}
