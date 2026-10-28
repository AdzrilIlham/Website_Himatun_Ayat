<?php

namespace Tests\Feature;

use App\Models\Donasi;
use App\Models\Kampanye;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminKelolaDonasiTest extends TestCase
{
    use RefreshDatabase;

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
            'judul'          => 'Program Uji',
            'slug'           => 'program-uji',
            'kategori'       => 'Pendidikan',
            'deskripsi'      => 'Deskripsi program uji untuk pengujian sistem',
            'target_dana'    => 10000000,
            'dana_terkumpul' => 5000000,
            'status'         => 'aktif',
        ]);

        Donasi::create([
            'nama_donatur'      => 'Donatur Khusus Testing',
            'email'             => 'khusus.testing@gmail.com',
            'no_whatsapp'       => '+62 899-0000-1111',
            'nominal'           => 9999000,
            'kampanye_id'       => $kampanye->id,
            'metode_pembayaran' => 'Transfer Bank (BSI)',
            'bukti_transfer_path' => 'images/receipt-placeholder.svg',
            'status'            => 'verified',
        ]);

        $response = $this->get('/admin/kelola-donasi?search=Khusus+Testing');
        $response->assertStatus(200);
        $response->assertSee('Donatur Khusus Testing');
        $response->assertSee('khusus.testing@gmail.com');
    }

    public function test_can_store_new_donatur(): void
    {
        $response = $this->post('/admin/kelola-donasi', [
            'nama_donatur'      => 'Bambang Baru',
            'email'             => 'bambang.baru@example.com',
            'no_whatsapp'       => '+62 812-9988-7766',
            'nominal'           => 1500000,
            'metode_pembayaran' => 'Transfer Bank (BSI)',
            'status'            => 'verified',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('donasi', [
            'nama_donatur' => 'Bambang Baru',
            'email'        => 'bambang.baru@example.com',
        ]);
    }

    public function test_can_terima_donasi(): void
    {
        $donasi = Donasi::create([
            'nama_donatur'        => 'Donatur Pending',
            'nominal'             => 200000,
            'metode_pembayaran'   => 'QRIS Manual',
            'bukti_transfer_path' => 'images/receipt-placeholder.svg',
            'status'              => 'pending',
        ]);

        $response = $this->post("/admin/kelola-donasi/{$donasi->id}/terima");
        $response->assertRedirect();

        $this->assertDatabaseHas('donasi', [
            'id'     => $donasi->id,
            'status' => 'verified',
        ]);
    }

    public function test_can_delete_donasi(): void
    {
        $donasi = Donasi::create([
            'nama_donatur'        => 'Donatur Dihapus',
            'nominal'             => 100000,
            'metode_pembayaran'   => 'QRIS Manual',
            'bukti_transfer_path' => 'images/receipt-placeholder.svg',
            'status'              => 'pending',
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
}
