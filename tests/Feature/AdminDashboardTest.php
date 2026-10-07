<?php

namespace Tests\Feature;

use App\Models\Donasi;
use App\Models\Kampanye;
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
        $response->assertSee('Semua (18)');
        $response->assertSee('Transfer Bank (12)');
        $response->assertSee('QRIS Manual (6)');
        $response->assertSee('Hj. Siti Rahmawati');
        $response->assertSee('Menunggu Verifikasi');
    }

    public function test_filter_by_metode_bank_works(): void
    {
        $response = $this->get('/admin/dashboard?metode=transfer_bank');

        $response->assertStatus(200);
        $response->assertSee('Hj. Siti Rahmawati');
    }

    public function test_filter_by_search_works(): void
    {
        $response = $this->get('/admin/dashboard?search=Hendra');

        $response->assertStatus(200);
        $response->assertSee('Bpk. Hendra Gunawan');
        $response->assertDontSee('dr. Agus Prasetyo');
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
}
