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

    /**
     * Test endpoint JSON untuk preview AJAX Data Anak Asuh.
     */
    public function test_ajax_preview_json_returns_proper_structure(): void
    {
        $response = $this->getJson('/admin/laporan-ekspor?json=1&jenis_laporan=Data+Anak+Asuh');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'jenisLaporan',
            'bulan',
            'tahun',
            'kategori',
            'stats',
            'columns',
            'previewData',
            'pagination',
            'kategoriOptions',
        ]);
    }

    /**
     * Test endpoint JSON untuk preview AJAX Kelola Donasi.
     */
    public function test_ajax_preview_donasi_returns_proper_data(): void
    {
        $response = $this->getJson('/admin/laporan-ekspor?json=1&jenis_laporan=Kelola+Donasi');

        $response->assertStatus(200);
        $response->assertJson([
            'success'      => true,
            'jenisLaporan' => 'Kelola Donasi',
        ]);
    }

    /**
     * Test endpoint JSON untuk preview AJAX Kelola Kampanye.
     */
    public function test_ajax_preview_kampanye_returns_proper_data(): void
    {
        $response = $this->getJson('/admin/laporan-ekspor?json=1&jenis_laporan=Kelola+Kampanye');

        $response->assertStatus(200);
        $response->assertJson([
            'success'      => true,
            'jenisLaporan' => 'Kelola Kampanye',
        ]);
    }

    /**
     * Test ekspor Excel untuk Kelola Donasi dan Kelola Kampanye.
     */
    public function test_export_excel_donasi_and_kampanye(): void
    {
        $resDonasi = $this->get('/admin/laporan-ekspor/export/excel?jenis_laporan=Kelola+Donasi');
        $resDonasi->assertStatus(200);
        $resDonasi->assertHeader('content-disposition');

        $resKampanye = $this->get('/admin/laporan-ekspor/export/excel?jenis_laporan=Kelola+Kampanye');
        $resKampanye->assertStatus(200);
        $resKampanye->assertHeader('content-disposition');
    }

    /**
     * Test ekspor PDF untuk Kelola Donasi dan Kelola Kampanye.
     */
    public function test_export_pdf_donasi_and_kampanye(): void
    {
        $resDonasi = $this->get('/admin/laporan-ekspor/export/pdf?jenis_laporan=Kelola+Donasi');
        $resDonasi->assertStatus(200);
        $resDonasi->assertHeader('content-disposition');

        $resKampanye = $this->get('/admin/laporan-ekspor/export/pdf?jenis_laporan=Kelola+Kampanye');
        $resKampanye->assertStatus(200);
        $resKampanye->assertHeader('content-disposition');
    }

    /**
     * Test stream preview PDF (inline) untuk ditampilkan di browser viewer.
     */
    public function test_export_pdf_stream_inline_preview(): void
    {
        $res = $this->get('/admin/laporan-ekspor/export/pdf?jenis_laporan=Data+Anak+Asuh&preview=1');
        $res->assertStatus(200);
        $res->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('inline', (string) $res->headers->get('content-disposition'));
    }
}
