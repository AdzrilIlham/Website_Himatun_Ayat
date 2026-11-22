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
            'pesan_doa' => 'Semoga menjadi amal jariyah',
            'status' => 'verified',
        ]);

        $response = $this->get('/admin/kelola-donasi?search=Khusus+Testing');
        $response->assertStatus(200);
        $response->assertSee('Donatur Khusus Testing');
        $response->assertSee('khusus.testing@gmail.com');
        $response->assertSee('Semoga menjadi amal jariyah');
        $response->assertSee('Program Uji');
        $response->assertDontSee('Menampilkan antrian konfirmasi');
        $response->assertDontSee('<span>Penyaluran Dana</span>', false);
        $response->assertDontSee('✓ Terverifikasi');
        $response->assertDontSee('✕ Ditolak');
        $response->assertDontSee('⏳ Menunggu');
        $response->assertSee('#proof-preview-modal', false);
        $response->assertSee('open-bukti-modal', false);
        $response->assertSee('#modal-edit-status-donasi', false);
        $response->assertSee('open-edit-status-modal', false);
        $response->assertSee('#modal-konfirmasi-aksi', false);
        $response->assertSee('open-confirm-modal', false);
        $response->assertSee('data-hs-select', false);
        $response->assertSee('validatePenyaluranForm', false);
        $response->assertSee('validateOperasionalForm', false);
        $response->assertDontSee('onclick="return confirm(', false);
        $response->assertSee('hs-donasi-filter-dropdown', false);
        $response->assertSee('hs-column-manager-dropdown', false);
        $response->assertSee('Column Manager');
        $response->assertSee('Terapkan Filter');
        $response->assertSee('x-show="cols.id_donasi"', false);
        $response->assertSee('x-show="cols.aksi"', false);
        $response->assertSee('Edit');
        $response->assertDontSee('Semua Metode');
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

    public function test_can_verifikasi_and_tolak_donasi_without_double_counting(): void
    {
        $kampanye = Kampanye::create([
            'judul' => 'Kampanye Verifikasi',
            'slug' => 'kampanye-verifikasi',
            'kategori' => 'Pendidikan',
            'deskripsi' => 'Deskripsi kampanye verifikasi dan tolak donasi',
            'target_dana' => 5000000,
            'dana_terkumpul' => 0,
            'status' => 'aktif',
        ]);

        $donasi = Donasi::create([
            'nama_donatur' => 'Donatur Uji Verif',
            'kampanye_id' => $kampanye->id,
            'nominal' => 250000,
            'metode_pembayaran' => 'Transfer Bank (BSI)',
            'bukti_transfer_path' => 'images/receipt-placeholder.svg',
            'status' => 'pending',
        ]);

        $this->post("/admin/kelola-donasi/{$donasi->id}/verifikasi")->assertRedirect();
        $this->assertDatabaseHas('donasi', ['id' => $donasi->id, 'status' => 'verified']);
        $this->assertEquals(250000, (float) $kampanye->fresh()->dana_terkumpul);

        // Idempotent: verifikasi ulang tidak menggandakan dana_terkumpul
        $this->post("/admin/kelola-donasi/{$donasi->id}/verifikasi")->assertRedirect();
        $this->assertEquals(250000, (float) $kampanye->fresh()->dana_terkumpul);

        // Tolak mengurangi dana_terkumpul tepat satu kali
        $this->post("/admin/kelola-donasi/{$donasi->id}/tolak")->assertRedirect();
        $this->assertDatabaseHas('donasi', ['id' => $donasi->id, 'status' => 'rejected']);
        $this->assertEquals(0, (float) $kampanye->fresh()->dana_terkumpul);

        // Tolak ulang tidak membuat dana_terkumpul negatif
        $this->post("/admin/kelola-donasi/{$donasi->id}/tolak")->assertRedirect();
        $this->assertEquals(0, (float) $kampanye->fresh()->dana_terkumpul);
    }

    public function test_can_sort_donasi_by_terbaru_terlama_nama_asc_and_nama_desc(): void
    {
        $awal = Donasi::create([
            'nama_donatur' => 'Zaki Pertama',
            'nominal' => 100000,
            'metode_pembayaran' => 'QRIS Manual',
            'status' => 'verified',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        $akhir = Donasi::create([
            'nama_donatur' => 'Aisyah Kedua',
            'nominal' => 150000,
            'metode_pembayaran' => 'Transfer Bank (BCA)',
            'status' => 'verified',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get('/admin/kelola-donasi?sort=terbaru')
            ->assertStatus(200)
            ->assertSeeInOrder([$akhir->nama_donatur, $awal->nama_donatur]);

        $this->get('/admin/kelola-donasi?sort=terlama')
            ->assertStatus(200)
            ->assertSeeInOrder([$awal->nama_donatur, $akhir->nama_donatur]);

        $this->get('/admin/kelola-donasi?sort=nama_asc')
            ->assertStatus(200)
            ->assertSeeInOrder([$akhir->nama_donatur, $awal->nama_donatur]);

        $this->get('/admin/kelola-donasi?sort=nama_desc')
            ->assertStatus(200)
            ->assertSeeInOrder([$awal->nama_donatur, $akhir->nama_donatur]);

        $this->get('/admin/kelola-donasi?sort_waktu=terlama')
            ->assertStatus(200)
            ->assertSeeInOrder([$awal->nama_donatur, $akhir->nama_donatur]);

        $this->get('/admin/kelola-donasi?sort_waktu=terbaru&sort_nama=nama_asc')
            ->assertStatus(200)
            ->assertSeeInOrder([$akhir->nama_donatur, $awal->nama_donatur]);

        $this->get('/admin/kelola-donasi?sort_waktu=terbaru&sort_nama=nama_desc')
            ->assertStatus(200)
            ->assertSeeInOrder([$awal->nama_donatur, $akhir->nama_donatur]);

        $kampanyeFilter = Kampanye::create([
            'judul' => 'Program Filter Khusus',
            'slug' => 'program-filter-khusus',
            'kategori' => 'Pendidikan',
            'deskripsi' => 'Deskripsi filter kampanye',
            'target_dana' => 5000000,
            'dana_terkumpul' => 0,
            'status' => 'aktif',
        ]);

        $khususProgram = Donasi::create([
            'nama_donatur' => 'Donatur Program Filter',
            'kampanye_id' => $kampanyeFilter->id,
            'nominal' => 250000,
            'metode_pembayaran' => 'QRIS Manual',
            'status' => 'verified',
        ]);

        $this->get("/admin/kelola-donasi?kampanye_id={$kampanyeFilter->id}")
            ->assertStatus(200)
            ->assertSee($khususProgram->nama_donatur)
            ->assertDontSee($awal->nama_donatur);

        $this->get('/admin/kelola-donasi?kampanye_id=umum')
            ->assertStatus(200)
            ->assertSee($awal->nama_donatur)
            ->assertDontSee($khususProgram->nama_donatur);
    }

    public function test_can_filter_donasi_by_status_and_metode_and_displays_hamba_allah(): void
    {
        Donasi::create([
            'nama_donatur' => '',
            'nominal' => 100000,
            'metode_pembayaran' => 'QRIS Manual',
            'status' => 'pending',
        ]);

        Donasi::create([
            'nama_donatur' => 'Siti Aminah',
            'nominal' => 300000,
            'metode_pembayaran' => 'Transfer Bank (BSI)',
            'status' => 'verified',
        ]);

        $this->get('/admin/kelola-donasi?status=pending&metode=qris')
            ->assertStatus(200)
            ->assertSee('Hamba Allah')
            ->assertSee('Verifikasi')
            ->assertSee('Tolak')
            ->assertDontSee('Siti Aminah');

        $this->get('/admin/kelola-donasi?status=verified&metode=bank')
            ->assertStatus(200)
            ->assertSee('Siti Aminah')
            ->assertSee('open-edit-status-modal', false)
            ->assertDontSee('Hamba Allah');
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
        Donasi::create([
            'nama_donatur' => 'Donatur Saldo Awal',
            'nominal' => 10000000,
            'metode_pembayaran' => 'QRIS Manual',
            'status' => 'verified',
            'diverifikasi_pada' => now(),
        ]);

        // Store Penyaluran Dana
        $this->post('/admin/penyaluran-dana', [
            'klaster_asnaf' => 'pendidikan',
            'keterangan' => 'Beasiswa Anak Asuh',
            'nominal' => 5000000,
            'tanggal_penyaluran' => '2026-11-20',
            'penerima_manfaat' => '20 Anak Asuh',
        ])->assertRedirect()->assertSessionHasNoErrors();

        $penyaluran = PenyaluranDana::firstOrFail();
        $this->assertEquals(5000000, (float) $penyaluran->nominal);

        // Update Penyaluran Dana
        $this->put("/admin/penyaluran-dana/{$penyaluran->id}", [
            'klaster_asnaf' => 'nutrisi',
            'keterangan' => 'Program Gizi Anak Asuh',
            'nominal' => 6000000,
            'tanggal_penyaluran' => '2026-11-21',
            'penerima_manfaat' => '30 Anak Asuh',
        ])->assertRedirect()->assertSessionHasNoErrors();

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
        ])->assertRedirect()->assertSessionHasNoErrors();

        $operasional = PengeluaranOperasional::firstOrFail();

        // Update Pengeluaran Operasional
        $this->put("/admin/pengeluaran-operasional/{$operasional->id}", [
            'kategori' => 'Administrasi',
            'keterangan' => 'Listrik, Air, dan Internet Asrama',
            'nominal' => 2000000,
            'tanggal_pengeluaran' => '2026-11-21',
        ])->assertRedirect()->assertSessionHasNoErrors();

        $this->assertDatabaseHas('pengeluaran_operasional', [
            'id' => $operasional->id,
            'kategori' => 'Administrasi',
            'nominal' => 2000000,
        ]);

        // Sisa saldo kas saat ini = 10.000.000 - 6.000.000 - 2.000.000 = 2.000.000
        // Tidak boleh menyalurkan atau mencatat operasional melebihi sisa saldo kas
        $this->post('/admin/penyaluran-dana', [
            'klaster_asnaf' => 'asrama',
            'keterangan' => 'Renovasi Darurat',
            'nominal' => 2500000,
            'tanggal_penyaluran' => '2026-11-22',
        ])->assertSessionHasErrors('nominal');

        $this->post('/admin/pengeluaran-operasional', [
            'kategori' => 'Lainnya',
            'keterangan' => 'Belanja Melebihi Saldo',
            'nominal' => 2500000,
            'tanggal_pengeluaran' => '2026-11-22',
        ])->assertSessionHasErrors('nominal');

        $this->put("/admin/pengeluaran-operasional/{$operasional->id}", [
            'kategori' => 'Administrasi',
            'keterangan' => 'Melebihi Batas Kas',
            'nominal' => 4500000,
            'tanggal_pengeluaran' => '2026-11-22',
        ])->assertSessionHasErrors('nominal');

        // Delete both
        $this->delete("/admin/penyaluran-dana/{$penyaluran->id}")->assertRedirect();
        $this->delete("/admin/pengeluaran-operasional/{$operasional->id}")->assertRedirect();

        $this->assertDatabaseMissing('penyaluran_dana', ['id' => $penyaluran->id]);
        $this->assertDatabaseMissing('pengeluaran_operasional', ['id' => $operasional->id]);
    }
}
