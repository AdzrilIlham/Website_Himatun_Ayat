<?php

namespace Tests\Feature;

use App\Models\Donasi;
use App\Models\Kampanye;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLaporanEksporTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->withTwoFactor()->create();
        $this->actingAs($admin);
    }

    /**
     * Test halaman Laporan & Ekspor dapat diakses dengan sukses.
     */
    public function test_laporan_ekspor_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/laporan-ekspor');

        $response->assertStatus(200);
        $response->assertSee('Laporan & Ekspor', false);
        $response->assertSee('Filter Laporan');
        $response->assertDontSee('Tampilkan Preview');
        $response->assertDontSee('Pilih filter di atas, lalu klik tombol untuk memperbarui preview', false);
        $response->assertSee('bg-brand-100', false);
        $response->assertSee('text-2xs', false);
        $response->assertSee('whitespace-nowrap', false);
        $response->assertSee('tabular', false);
        $response->assertDontSee('bg-purple-100', false);
        $response->assertDontSee('bg-[#107C41]', false);
        $response->assertDontSee('dark:bg-brand-900', false);
        $response->assertDontSee('dark:bg-amber-950', false);
        $response->assertDontSee('dark:bg-purple-950', false);
        $this->assertStringNotContainsString('dark:', file_get_contents(resource_path('views/admin/laporan-ekspor/index.blade.php')));
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
        $kampanye = Kampanye::create([
            'judul' => 'Santunan Yatim Cibiru',
            'slug' => 'santunan-yatim-cibiru',
            'kategori' => 'Kemanusiaan',
            'deskripsi' => 'Program santunan kemanusiaan.',
            'target_dana' => 10000000,
            'dana_terkumpul' => 2500000,
            'status' => 'aktif',
        ]);

        Donasi::create([
            'nama_donatur' => 'Donatur Terverifikasi',
            'nominal' => 1000000,
            'kampanye_id' => $kampanye->id,
            'metode_pembayaran' => 'QRIS',
            'status' => 'verified',
        ]);

        Donasi::create([
            'nama_donatur' => 'Donatur Menunggu',
            'nominal' => 500000,
            'kampanye_id' => $kampanye->id,
            'metode_pembayaran' => 'QRIS',
            'status' => 'pending',
        ]);

        Donasi::create([
            'nama_donatur' => '',
            'nominal' => 250000,
            'kampanye_id' => $kampanye->id,
            'metode_pembayaran' => 'QRIS',
            'status' => 'rejected',
        ]);

        $response = $this->getJson('/admin/laporan-ekspor?json=1&jenis_laporan=Kelola+Donasi');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'jenisLaporan' => 'Kelola Donasi',
        ]);

        $rows = collect($response->json('previewData'));
        $verifiedRow = $rows->firstWhere('nama', 'Donatur Terverifikasi');
        $pendingRow = $rows->firstWhere('nama', 'Donatur Menunggu');
        $rejectedRow = $rows->firstWhere('nama', 'Hamba Allah');

        $this->assertNotNull($verifiedRow);
        $this->assertSame('Terverifikasi', $verifiedRow['status']);
        $this->assertSame('Rp 1.000.000', $verifiedRow['nilai_utama']);
        $this->assertNotEmpty($verifiedRow['nilai_sub']);
        $this->assertSame('Terverifikasi', $verifiedRow['detail']['Status']);

        $this->assertNotNull($pendingRow);
        $this->assertSame('Menunggu', $pendingRow['status']);

        $this->assertNotNull($rejectedRow);
        $this->assertSame('Ditolak', $rejectedRow['status']);
    }

    /**
     * Test endpoint JSON untuk preview AJAX Kelola Kampanye.
     */
    public function test_ajax_preview_kampanye_returns_proper_data(): void
    {
        Kampanye::create([
            'judul' => 'Program Tahfidz Aktif',
            'slug' => 'program-tahfidz-aktif',
            'kategori' => 'Pendidikan & Tahfidz',
            'deskripsi' => 'Program pendidikan santri.',
            'target_dana' => 50000000,
            'dana_terkumpul' => 25000000,
            'status' => 'aktif',
        ]);

        Kampanye::create([
            'judul' => 'Program Bantuan Selesai',
            'slug' => 'program-bantuan-selesai',
            'kategori' => 'Kemanusiaan',
            'deskripsi' => 'Program bantuan sosial.',
            'target_dana' => 20000000,
            'dana_terkumpul' => 20000000,
            'status' => 'selesai',
        ]);

        Kampanye::create([
            'judul' => 'Program Rencana Draft',
            'slug' => 'program-rencana-draft',
            'kategori' => 'Operasional',
            'deskripsi' => 'Program rencana draft.',
            'target_dana' => 15000000,
            'dana_terkumpul' => 0,
            'status' => 'draft',
        ]);

        $response = $this->getJson('/admin/laporan-ekspor?json=1&jenis_laporan=Kelola+Kampanye');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'jenisLaporan' => 'Kelola Kampanye',
        ]);

        $rows = collect($response->json('previewData'));
        $aktifRow = $rows->firstWhere('nama', 'Program Tahfidz Aktif');
        $selesaiRow = $rows->firstWhere('nama', 'Program Bantuan Selesai');
        $draftRow = $rows->firstWhere('nama', 'Program Rencana Draft');

        $this->assertNotNull($aktifRow);
        $this->assertSame('Aktif', $aktifRow['status']);
        $this->assertSame('Rp 25.000.000', $aktifRow['nilai_utama']);
        $this->assertSame('Target Rp 50.000.000', $aktifRow['nilai_sub']);

        $this->assertNotNull($selesaiRow);
        $this->assertSame('Selesai', $selesaiRow['status']);

        $this->assertNotNull($draftRow);
        $this->assertSame('Draft', $draftRow['status']);
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
