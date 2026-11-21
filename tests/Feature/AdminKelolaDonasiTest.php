<?php

namespace Tests\Feature;

use App\Models\Donasi;
use App\Models\Kampanye;
use App\Models\PengeluaranOperasional;
use App\Models\PenyaluranDana;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminKelolaDonasiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->withTwoFactor()->create();
        $this->actingAs($admin);
    }

    public function test_can_access_kelola_donasi_page(): void
    {
        $response = $this->get('/admin/kelola-donasi');

        $response->assertStatus(200);
        $response->assertSee('Kelola Donasi');
        $response->assertSee('TOTAL PENERIMAAN ZIS');
        $response->assertSee('PENYALURAN PROGRAM');
        $response->assertSee('OPERASIONAL HAK MILIK');
        $response->assertSee('SALDO KAS & CADANGAN', false);
        $response->assertSee('Alokasi Penyaluran Program per Klaster Asnaf');
    }

    public function test_can_search_and_sort_donasi(): void
    {
        $kampanye = Kampanye::first() ?? Kampanye::create([
            'judul' => 'Program Uji',
            'slug' => 'program-uji',
            'kategori' => 'Pendidikan',
            'deskripsi' => 'Deskripsi program uji untuk pengujian sistem',
            'target_dana' => 10000000,
            'dana_terkumpul' => 5000000,
            'status' => 'aktif',
        ]);

        Donasi::create([
            'nama_donatur' => 'Donatur Khusus Testing',
            'email' => 'khusus.testing@gmail.com',
            'no_whatsapp' => '+62 899-0000-1111',
            'nominal' => 9999000,
            'kampanye_id' => $kampanye->id,
            'metode_pembayaran' => 'Transfer Bank (BSI)',
            'bukti_transfer_path' => 'images/receipt-placeholder.svg',
            'status' => 'verified',
        ]);

        $response = $this->get('/admin/kelola-donasi?search=Khusus+Testing');
        $response->assertStatus(200);
        $response->assertSee('Donatur Khusus Testing');
        $response->assertSee('khusus.testing@gmail.com');
        $response->assertSee('showBuktiModal', false);
        $response->assertSee('openEditModal', false);
    }

    public function test_can_update_donasi(): void
    {
        $kampanye = Kampanye::create([
            'judul' => 'Kampanye Edit',
            'slug' => 'kampanye-edit',
            'kategori' => 'Pendidikan',
            'deskripsi' => 'Deskripsi kampanye untuk pengujian edit donasi',
            'target_dana' => 10000000,
            'dana_terkumpul' => 200000,
            'status' => 'aktif',
        ]);

        $donasi = Donasi::create([
            'nama_donatur' => 'Donatur Lama',
            'email' => 'lama@example.com',
            'no_whatsapp' => '08111111111',
            'kampanye_id' => $kampanye->id,
            'nominal' => 200000,
            'metode_pembayaran' => 'Transfer Bank (BSI)',
            'bukti_transfer_path' => 'images/receipt-placeholder.svg',
            'status' => 'verified',
            'diverifikasi_pada' => now(),
        ]);

        $response = $this->put("/admin/kelola-donasi/{$donasi->id}", [
            'nama_donatur' => 'Donatur Diperbarui',
            'email' => 'baru@example.com',
            'no_whatsapp' => '08222222222',
            'kampanye_id' => $kampanye->id,
            'nominal' => 500000,
            'metode_pembayaran' => 'QRIS Manual',
            'pesan_doa' => 'Semoga berkah',
            'status' => 'verified',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('donasi', [
            'id' => $donasi->id,
            'nama_donatur' => 'Donatur Diperbarui',
            'email' => 'baru@example.com',
            'nominal' => 500000,
        ]);
        $this->assertEquals(500000, (float) $kampanye->fresh()->dana_terkumpul);
    }

    public function test_can_store_new_donatur(): void
    {
        $response = $this->post('/admin/kelola-donasi', [
            'nama_donatur' => 'Bambang Baru',
            'email' => 'bambang.baru@example.com',
            'no_whatsapp' => '+62 812-9988-7766',
            'nominal' => 1500000,
            'metode_pembayaran' => 'Transfer Bank (BSI)',
            'status' => 'verified',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('donasi', [
            'nama_donatur' => 'Bambang Baru',
            'email' => 'bambang.baru@example.com',
        ]);
    }

    public function test_can_terima_donasi(): void
    {
        $donasi = Donasi::create([
            'nama_donatur' => 'Donatur Pending',
            'nominal' => 200000,
            'metode_pembayaran' => 'QRIS Manual',
            'bukti_transfer_path' => 'images/receipt-placeholder.svg',
            'status' => 'pending',
        ]);

        $response = $this->post("/admin/kelola-donasi/{$donasi->id}/terima");
        $response->assertRedirect();

        $this->assertDatabaseHas('donasi', [
            'id' => $donasi->id,
            'status' => 'verified',
        ]);
    }

    public function test_can_delete_donasi(): void
    {
        $donasi = Donasi::create([
            'nama_donatur' => 'Donatur Dihapus',
            'nominal' => 100000,
            'metode_pembayaran' => 'QRIS Manual',
            'bukti_transfer_path' => 'images/receipt-placeholder.svg',
            'status' => 'pending',
        ]);

        $response = $this->delete("/admin/kelola-donasi/{$donasi->id}");
        $response->assertRedirect();

        $this->assertDatabaseMissing('donasi', [
            'id' => $donasi->id,
        ]);
    }

    public function test_can_export_donasi_excel(): void
    {
        $response = $this->get('/admin/kelola-donasi/export');
        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
    }

    public function test_can_crud_penyaluran_dana_and_operasional(): void
    {
        // Store Penyaluran Dana
        $this->post('/admin/penyaluran-dana', [
            'klaster_asnaf' => 'pendidikan',
            'keterangan' => 'Beasiswa Anak Asuh',
            'nominal' => 5000000,
            'tanggal_penyaluran' => '2026-11-20',
            'penerima_manfaat' => '20 Anak Asuh',
        ])->assertRedirect();

        $penyaluran = PenyaluranDana::firstOrFail();
        $this->assertEquals(5000000, (float) $penyaluran->nominal);

        // Update Penyaluran Dana
        $this->put("/admin/penyaluran-dana/{$penyaluran->id}", [
            'klaster_asnaf' => 'nutrisi',
            'keterangan' => 'Program Gizi Anak Asuh',
            'nominal' => 6000000,
            'tanggal_penyaluran' => '2026-11-21',
            'penerima_manfaat' => '30 Anak Asuh',
        ])->assertRedirect();

        $this->assertDatabaseHas('penyaluran_dana', [
            'id' => $penyaluran->id,
            'klaster_asnaf' => 'nutrisi',
            'nominal' => 6000000,
        ]);

        // Store Pengeluaran Operasional
        $this->post('/admin/pengeluaran-operasional', [
            'kategori' => 'Utilitas',
            'keterangan' => 'Listrik dan Air Asrama',
            'nominal' => 1500000,
            'tanggal_pengeluaran' => '2026-11-20',
        ])->assertRedirect();

        $operasional = PengeluaranOperasional::firstOrFail();

        // Update Pengeluaran Operasional
        $this->put("/admin/pengeluaran-operasional/{$operasional->id}", [
            'kategori' => 'Administrasi',
            'keterangan' => 'Listrik, Air, dan Internet Asrama',
            'nominal' => 2000000,
            'tanggal_pengeluaran' => '2026-11-21',
        ])->assertRedirect();

        $this->assertDatabaseHas('pengeluaran_operasional', [
            'id' => $operasional->id,
            'kategori' => 'Administrasi',
            'nominal' => 2000000,
        ]);

        // Delete both
        $this->delete("/admin/penyaluran-dana/{$penyaluran->id}")->assertRedirect();
        $this->delete("/admin/pengeluaran-operasional/{$operasional->id}")->assertRedirect();

        $this->assertDatabaseMissing('penyaluran_dana', ['id' => $penyaluran->id]);
        $this->assertDatabaseMissing('pengeluaran_operasional', ['id' => $operasional->id]);
    }
}
