<?php

namespace Tests\Feature;

use App\Models\Kampanye;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminKelolaKampanyeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_access_kelola_kampanye_index_page(): void
    {
        Kampanye::create([
            'judul'          => 'Program Kafalah Guru Ngaji',
            'slug'          => 'program-kafalah-guru-ngaji',
            'kategori'       => 'Dakwah & Syiar',
            'deskripsi'      => 'Deskripsi program kafalah guru ngaji.',
            'target_dana'    => 25000000,
            'dana_terkumpul' => 15000000,
            'tanggal_mulai'  => '2025-02-01',
            'tenggat_waktu'  => '2025-02-28',
            'status'         => 'aktif',
        ]);

        $response = $this->get('/admin/kelola-kampanye');

        $response->assertStatus(200);
        $response->assertSee('Kelola Kampanye');
        $response->assertSee('+ Buat Kampanye Baru');
        $response->assertSee('Program Kafalah Guru Ngaji');
        $response->assertSee('Dakwah &amp; Syiar', false);
    }

    public function test_can_search_kampanye(): void
    {
        Kampanye::create([
            'judul'          => 'Kampanye Khusus Searching',
            'slug'          => 'kampanye-khusus-searching',
            'kategori'       => 'Pendidikan & Tahfidz',
            'deskripsi'      => 'Testing pencarian kata kunci.',
            'target_dana'    => 50000000,
            'dana_terkumpul' => 0,
            'status'         => 'aktif',
        ]);

        $response = $this->get('/admin/kelola-kampanye?search=Searching');

        $response->assertStatus(200);
        $response->assertSee('Kampanye Khusus Searching');
    }

    public function test_can_access_create_kampanye_page(): void
    {
        $response = $this->get('/admin/kelola-kampanye/create');

        $response->assertStatus(200);
        $response->assertSee('Buat Kampanye Baru');
        $response->assertSee('Preview Kampanye');
        $response->assertSee('Informasi Dasar');
        $response->assertSee('Detail Kampanye');
        $response->assertSee('Media & Konten', false);
    }

    public function test_can_store_new_kampanye(): void
    {
        $response = $this->post('/admin/kelola-kampanye', [
            'judul'          => 'Beasiswa Tahfidz Yatim Baru',
            'kategori'       => 'Pendidikan & Tahfidz',
            'deskripsi'      => 'Program bantuan biaya sekolah dan asrama santri tahfidz.',
            'target_dana'    => 60000000,
            'tanggal_mulai'  => '2025-03-01',
            'tenggat_waktu'  => '2025-06-30',
            'status'         => 'aktif',
        ]);

        $response->assertRedirect('/admin/kelola-kampanye');
        $this->assertDatabaseHas('kampanye', [
            'judul'    => 'Beasiswa Tahfidz Yatim Baru',
            'kategori' => 'Pendidikan & Tahfidz',
            'status'   => 'aktif',
        ]);
    }

    public function test_can_access_edit_and_update_kampanye(): void
    {
        $kampanye = Kampanye::create([
            'judul'          => 'Kampanye Lama',
            'slug'          => 'kampanye-lama',
            'kategori'       => 'Operasional',
            'deskripsi'      => 'Deskripsi lama.',
            'target_dana'    => 20000000,
            'dana_terkumpul' => 0,
            'status'         => 'draft',
        ]);

        $editResponse = $this->get("/admin/kelola-kampanye/{$kampanye->id}/edit");
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Edit Kampanye');
        $editResponse->assertSee('Kampanye Lama');

        $updateResponse = $this->put("/admin/kelola-kampanye/{$kampanye->id}", [
            'judul'          => 'Kampanye Sudah Diperbarui',
            'kategori'       => 'Kemanusiaan',
            'deskripsi'      => 'Deskripsi telah diperbarui.',
            'target_dana'    => 35000000,
            'tanggal_mulai'  => '2025-04-01',
            'tenggat_waktu'  => '2025-05-01',
            'status'         => 'aktif',
        ]);

        $updateResponse->assertRedirect('/admin/kelola-kampanye');
        $this->assertDatabaseHas('kampanye', [
            'id'       => $kampanye->id,
            'judul'    => 'Kampanye Sudah Diperbarui',
            'kategori' => 'Kemanusiaan',
            'status'   => 'aktif',
        ]);
    }

    public function test_can_delete_kampanye(): void
    {
        $kampanye = Kampanye::create([
            'judul'          => 'Kampanye Akan Dihapus',
            'slug'          => 'kampanye-akan-dihapus',
            'kategori'       => 'Operasional',
            'deskripsi'      => 'Akan dihapus.',
            'target_dana'    => 10000000,
            'dana_terkumpul' => 0,
            'status'         => 'draft',
        ]);

        $response = $this->delete("/admin/kelola-kampanye/{$kampanye->id}");

        $response->assertRedirect('/admin/kelola-kampanye');
        $this->assertDatabaseMissing('kampanye', [
            'id' => $kampanye->id,
        ]);
    }

    public function test_validation_errors_when_storing_invalid_data(): void
    {
        $response = $this->post('/admin/kelola-kampanye', [
            'judul'       => '',
            'target_dana' => -500,
        ]);

        $response->assertSessionHasErrors(['judul', 'kategori', 'deskripsi', 'target_dana', 'status']);
    }
}
