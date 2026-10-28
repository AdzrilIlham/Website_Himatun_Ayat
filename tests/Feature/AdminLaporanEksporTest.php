<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLaporanEksporTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test halaman Laporan & Ekspor dapat diakses dengan sukses.
     */
    public function test_laporan_ekspor_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/laporan-ekspor');

        $response->assertStatus(200);
        $response->assertSee('Laporan & Ekspor', false);
        $response->assertSee('Pilih Jenis Laporan');
        $response->assertSee('Total Anak Asuh');
        $response->assertSee('Aktif Pendidikan');
        $response->assertSee('Aktif di Asrama');
        $response->assertSee('Perlu Perhatian');
        $response->assertSee('Preview Laporan');
        $response->assertSee('Ekspor Laporan');
        $response->assertSee('Ekspor Excel');
        $response->assertSee('Ekspor PDF');
        $response->assertSee('AA-0021');
        $response->assertSee('Nur Aisyah');
    }

    /**
     * Test download ekspor Excel.
     */
    public function test_export_excel_can_be_downloaded(): void
    {
        $response = $this->get('/admin/laporan-ekspor/export/excel');

        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
    }

    /**
     * Test download ekspor PDF.
     */
    public function test_export_pdf_can_be_downloaded(): void
    {
        $response = $this->get('/admin/laporan-ekspor/export/pdf');

        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
    }
}
