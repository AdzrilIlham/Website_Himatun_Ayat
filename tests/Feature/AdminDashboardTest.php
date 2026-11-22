<?php

namespace Tests\Feature;

use App\Models\BeritaKegiatan;
use App\Models\Donasi;
use App\Models\Kampanye;
use App\Models\User;
use App\Services\LaporanService;
use Database\Seeders\DashboardDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DashboardDemoSeeder::class);

        $admin = User::first();
        $this->actingAs($admin);
    }

    public function test_dashboard_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Ringkasan Dashboard');
        $response->assertSee('TOTAL DONASI BULAN INI');
        $response->assertSee('TOTAL ANAK ASUH AKTIF');
        $response->assertSee('KAMPANYE AKTIF');
        $response->assertSee('Ekspor PDF');
        $response->assertSee('Ekspor Excel');
        $response->assertSee('Tren Pertumbuhan Donasi');
        $response->assertSee('Aktivitas & Log Masuk', false);
        $response->assertSee('Sebaran Donasi');
        $response->assertDontSee('Menampilkan antrian konfirmasi donasi langsung rekening operasional BSI, BCA & Mandiri.');
    }

    public function test_filter_by_metode_bank_works(): void
    {
        $response = $this->get('/admin/dashboard?metode=transfer_bank');

        $response->assertStatus(200);
        $response->assertViewHas('metode', 'transfer_bank');
    }

    public function test_filter_by_search_works(): void
    {
        $response = $this->get('/admin/dashboard?search=Hendra');

        $response->assertStatus(200);
        $response->assertViewHas('search', 'Hendra');
    }

    public function test_filter_by_tahun_works(): void
    {
        $response = $this->get('/admin/dashboard?tahun=2026');

        $response->assertStatus(200);
        $response->assertViewHas('tahun', '2026');
        $response->assertViewHas('chartData');
    }

    public function test_verifikasi_donasi_updates_status_and_kampanye(): void
    {
        $kampanye = Kampanye::first();
        $danaAwal = (float) $kampanye->dana_terkumpul;

        $donasi = Donasi::where('status', 'pending')
            ->where('kampanye_id', $kampanye->id)
            ->first();

        $response = $this->post("/admin/donasi/{$donasi->id}/verifikasi");

        $response->assertSessionHas('success');
        $donasi->refresh();
        $this->assertEquals('verified', $donasi->status);
        $this->assertNotNull($donasi->diverifikasi_pada);

        $kampanye->refresh();
        $this->assertEquals($danaAwal + (float) $donasi->nominal, (float) $kampanye->dana_terkumpul);
    }

    public function test_tolak_donasi_updates_status(): void
    {
        $donasi = Donasi::where('status', 'pending')->first();

        $response = $this->post("/admin/donasi/{$donasi->id}/tolak");

        $response->assertSessionHas('warning');
        $donasi->refresh();
        $this->assertEquals('rejected', $donasi->status);
    }

    public function test_export_excel_can_be_downloaded(): void
    {
        $response = $this->get('/admin/dashboard/export/excel');

        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
    }

    public function test_export_pdf_can_be_downloaded(): void
    {
        $response = $this->get('/admin/dashboard/export/pdf');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_laporan_service_recent_activities_queries_berita_kegiatan(): void
    {
        $berita = BeritaKegiatan::create([
            'tipe' => 'berita',
            'status' => 'tayang',
            'kategori' => 'Pendidikan',
            'judul' => 'Santri Mengikuti Ujian Tahfidz Nasional',
            'slug' => 'santri-mengikuti-ujian-tahfidz-nasional',
            'ringkasan' => 'Ujian tahfidz berjalan lancar.',
            'konten' => '<p>Konten artikel.</p>',
            'tanggal' => now(),
            'terjadwal_di_kalender' => false,
        ]);

        $service = app(LaporanService::class);
        $activities = $service->getRecentActivities(10);

        $activityTitles = array_column($activities, 'title');
        $this->assertContains('Aktivitas Berita', $activityTitles);

        $beritaActivity = collect($activities)->firstWhere('id', 'berita-'.$berita->id);
        $this->assertNotNull($beritaActivity);
        $this->assertEquals('CMS Berita', $beritaActivity['badge']);
        $this->assertStringContainsString('Santri Mengikuti Ujian Tahfidz', $beritaActivity['desc']);
    }
}
