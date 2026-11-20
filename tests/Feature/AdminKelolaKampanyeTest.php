<?php

namespace Tests\Feature;

use App\Models\BeritaKegiatan;
use App\Models\Kampanye;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminKelolaKampanyeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->withTwoFactor()->create();
        $this->actingAs($admin);
    }

    public function test_can_access_kelola_kampanye_index_page(): void
    {
        Kampanye::create([
            'judul' => 'Program Kafalah Guru Ngaji',
            'slug' => 'program-kafalah-guru-ngaji',
            'kategori' => 'Dakwah & Syiar',
            'deskripsi' => 'Deskripsi program kafalah guru ngaji.',
            'target_dana' => 25000000,
            'dana_terkumpul' => 15000000,
            'tanggal_mulai' => '2025-02-01',
            'tenggat_waktu' => '2025-02-28',
            'status' => 'aktif',
        ]);

        $response = $this->get('/admin/kelola-kampanye');

        $response->assertStatus(200);
        $response->assertSee('Kelola Kampanye');
        $response->assertSee('Buat Kampanye Baru');
        $response->assertSee('Program Kafalah Guru Ngaji');
        $response->assertSee('Dakwah &amp; Syiar', false);
    }

    public function test_can_search_kampanye(): void
    {
        Kampanye::create([
            'judul' => 'Kampanye Khusus Searching',
            'slug' => 'kampanye-khusus-searching',
            'kategori' => 'Pendidikan & Tahfidz',
            'deskripsi' => 'Testing pencarian kata kunci.',
            'target_dana' => 50000000,
            'dana_terkumpul' => 0,
            'status' => 'aktif',
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
            'judul' => 'Beasiswa Tahfidz Yatim Baru',
            'kategori' => 'Pendidikan & Tahfidz',
            'deskripsi' => 'Program bantuan biaya sekolah dan asrama santri tahfidz.',
            'target_dana' => 60000000,
            'tanggal_mulai' => '2025-03-01',
            'tenggat_waktu' => '2025-06-30',
            'status' => 'aktif',
        ]);

        $response->assertRedirect('/admin/kelola-kampanye');
        $this->assertDatabaseHas('kampanye', [
            'judul' => 'Beasiswa Tahfidz Yatim Baru',
            'kategori' => 'Pendidikan & Tahfidz',
            'status' => 'aktif',
        ]);
    }

    public function test_can_access_edit_and_update_kampanye(): void
    {
        $kampanye = Kampanye::create([
            'judul' => 'Kampanye Lama',
            'slug' => 'kampanye-lama',
            'kategori' => 'Operasional',
            'deskripsi' => 'Deskripsi lama.',
            'target_dana' => 20000000,
            'dana_terkumpul' => 0,
            'status' => 'draft',
        ]);

        $editResponse = $this->get("/admin/kelola-kampanye/{$kampanye->id}/edit");
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Edit Kampanye');
        $editResponse->assertSee('Kampanye Lama');

        $updateResponse = $this->put("/admin/kelola-kampanye/{$kampanye->id}", [
            'judul' => 'Kampanye Sudah Diperbarui',
            'kategori' => 'Kemanusiaan',
            'deskripsi' => 'Deskripsi telah diperbarui.',
            'target_dana' => 35000000,
            'tanggal_mulai' => '2025-04-01',
            'tenggat_waktu' => '2025-05-01',
            'status' => 'aktif',
        ]);

        $updateResponse->assertRedirect('/admin/kelola-kampanye');
        $this->assertDatabaseHas('kampanye', [
            'id' => $kampanye->id,
            'judul' => 'Kampanye Sudah Diperbarui',
            'kategori' => 'Kemanusiaan',
            'status' => 'aktif',
        ]);
    }

    public function test_can_delete_kampanye(): void
    {
        $kampanye = Kampanye::create([
            'judul' => 'Kampanye Akan Dihapus',
            'slug' => 'kampanye-akan-dihapus',
            'kategori' => 'Operasional',
            'deskripsi' => 'Akan dihapus.',
            'target_dana' => 10000000,
            'dana_terkumpul' => 0,
            'status' => 'draft',
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
            'judul' => '',
            'target_dana' => -500,
        ]);

        $response->assertSessionHasErrors(['judul', 'kategori', 'deskripsi', 'target_dana', 'status']);
    }

    public function test_kelola_kampanye_kabar_penyaluran_metric_uses_berita_kegiatan(): void
    {
        // Fallback default is at least 24
        $response = $this->get('/admin/kelola-kampanye');
        $response->assertStatus(200);
        $metrics = $response->viewData('metrics');
        $this->assertEquals(24, $metrics['kabar_penyaluran']);

        // Create 26 published BeritaKegiatan
        for ($i = 1; $i <= 26; $i++) {
            BeritaKegiatan::create([
                'tipe' => 'berita',
                'status' => 'tayang',
                'kategori' => 'Sosial & Kemanusiaan',
                'judul' => "Berita Penyaluran Ke {$i}",
                'slug' => "berita-penyaluran-ke-{$i}",
                'ringkasan' => "Ringkasan {$i}",
                'konten' => "<p>Konten {$i}</p>",
                'tanggal' => now()->subDays(1),
                'terjadwal_di_kalender' => false,
            ]);
        }

        // Draft BeritaKegiatan should not count
        BeritaKegiatan::create([
            'tipe' => 'berita',
            'status' => 'draft',
            'kategori' => 'Sosial & Kemanusiaan',
            'judul' => 'Berita Penyaluran Draft',
            'slug' => 'berita-penyaluran-draft',
            'ringkasan' => 'Draft',
            'konten' => '<p>Draft</p>',
            'tanggal' => now()->subDays(1),
            'terjadwal_di_kalender' => false,
        ]);

        $responseUpdated = $this->get('/admin/kelola-kampanye');
        $responseUpdated->assertStatus(200);
        $updatedMetrics = $responseUpdated->viewData('metrics');
        $this->assertEquals(26, $updatedMetrics['kabar_penyaluran']);
    }

    public function test_store_kampanye_with_banner_uploads_via_r2_service_and_sets_banner_url(): void
    {
        Storage::fake('public');

        $response = $this->post('/admin/kelola-kampanye', [
            'judul' => 'Kampanye Berbanner Baru',
            'kategori' => 'Pendidikan & Tahfidz',
            'deskripsi' => 'Deskripsi kampanye dengan banner.',
            'target_dana' => 15000000,
            'status' => 'aktif',
            'banner' => UploadedFile::fake()->image('banner.jpg'),
        ]);

        $response->assertRedirect('/admin/kelola-kampanye');
        $kampanye = Kampanye::where('judul', 'Kampanye Berbanner Baru')->firstOrFail();
        $this->assertNotNull($kampanye->banner_path);
        $this->assertStringStartsWith('kampanye/', $kampanye->banner_path);
        Storage::disk('public')->assertExists($kampanye->banner_path);

        $this->assertNotNull($kampanye->banner_url);
        $this->assertStringContainsString($kampanye->banner_path, $kampanye->banner_url);
    }

    public function test_update_kampanye_with_new_banner_replaces_old_banner(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('kampanye/old_banner.jpg', 'old banner content');
        $kampanye = Kampanye::create([
            'judul' => 'Kampanye Update Banner',
            'slug' => 'kampanye-update-banner',
            'kategori' => 'Kemanusiaan',
            'deskripsi' => 'Deskripsi.',
            'target_dana' => 20000000,
            'status' => 'aktif',
            'banner_path' => 'kampanye/old_banner.jpg',
        ]);

        $response = $this->put("/admin/kelola-kampanye/{$kampanye->id}", [
            'judul' => 'Kampanye Update Banner Diperbarui',
            'kategori' => 'Kemanusiaan',
            'deskripsi' => 'Deskripsi.',
            'target_dana' => 20000000,
            'status' => 'aktif',
            'banner' => UploadedFile::fake()->image('new_banner.jpg'),
        ]);

        $response->assertRedirect('/admin/kelola-kampanye');
        Storage::disk('public')->assertMissing('kampanye/old_banner.jpg');

        $kampanye->refresh();
        $this->assertNotEquals('kampanye/old_banner.jpg', $kampanye->banner_path);
        Storage::disk('public')->assertExists($kampanye->banner_path);
    }

    public function test_delete_kampanye_deletes_banner(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('kampanye/delete_banner.jpg', 'content');
        $kampanye = Kampanye::create([
            'judul' => 'Kampanye Hapus Banner',
            'slug' => 'kampanye-hapus-banner',
            'kategori' => 'Operasional',
            'deskripsi' => 'Deskripsi.',
            'target_dana' => 10000000,
            'status' => 'draft',
            'banner_path' => 'kampanye/delete_banner.jpg',
        ]);

        $response = $this->delete("/admin/kelola-kampanye/{$kampanye->id}");
        $response->assertRedirect('/admin/kelola-kampanye');

        $this->assertDatabaseMissing('kampanye', ['id' => $kampanye->id]);
        Storage::disk('public')->assertMissing('kampanye/delete_banner.jpg');
    }
}
