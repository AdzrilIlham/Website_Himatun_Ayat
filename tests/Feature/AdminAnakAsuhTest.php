<?php

namespace Tests\Feature;

use App\Models\AnakAsuh;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAnakAsuhTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->withTwoFactor()->create();
        $this->actingAs($admin);

        // Seed some sample data for testing
        AnakAsuh::create([
            'nama_lengkap' => 'Ahmad Budi',
            'nama_panggilan' => 'Budi',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => now()->subYears(10),
            'pendidikan_terakhir' => 'SD',
            'status_asuhan' => 'Aktif',
        ]);

        AnakAsuh::create([
            'nama_lengkap' => 'Siti Aisyah',
            'nama_panggilan' => 'Siti',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => now()->subYears(14),
            'pendidikan_terakhir' => 'SMP',
            'status_asuhan' => 'Aktif',
        ]);

        AnakAsuh::create([
            'nama_lengkap' => 'Rizky Firmansyah',
            'nama_panggilan' => 'Rizky',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => now()->subYears(17),
            'pendidikan_terakhir' => 'SMA',
            'status_asuhan' => 'Alumni',
        ]);
    }

    public function test_anak_asuh_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/anak-asuh');

        $response->assertStatus(200);
        $response->assertSee('Data Anak Asuh');
        $response->assertSee('TOTAL ANAK BINAAN');
        $response->assertSee('TINGKAT SD / MI');
        $response->assertSee('SMP / MTS');
        $response->assertSee('SMA / SMK / MA');
        $response->assertSee('PERGURUAN TINGGI');
        $response->assertSee('Ekspor PDF');
        $response->assertSee('Ekspor Excel');
        $response->assertSee('Tambah Anak Asuh');
        $response->assertSee('Ahmad Budi');
        $response->assertSee('Siti Aisyah');
    }

    public function test_filter_by_search_works(): void
    {
        $response = $this->get('/admin/anak-asuh?search=Ahmad');

        $response->assertStatus(200);
        $response->assertSee('Ahmad Budi');
        $response->assertDontSee('Siti Aisyah');
    }

    public function test_filter_by_status_works(): void
    {
        $response = $this->get('/admin/anak-asuh?status=Alumni');

        $response->assertStatus(200);
        $response->assertSee('Rizky Firmansyah');
        $response->assertDontSee('Ahmad Budi');
    }

    public function test_filter_by_pendidikan_status_works(): void
    {
        $response = $this->get('/admin/anak-asuh?status=SD');

        $response->assertStatus(200);
        $response->assertSee('Ahmad Budi');
        $response->assertDontSee('Siti Aisyah');
    }

    public function test_store_anak_asuh_creates_new_record(): void
    {
        $data = [
            'nama_lengkap' => 'Muhammad Fatih',
            'nama_panggilan' => 'Fatih',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2016-05-10',
            'pendidikan_terakhir' => 'SD',
            'status_asuhan' => 'Aktif',
            'keterangan' => 'Santri tahfidz baru',
        ];

        $response = $this->post('/admin/anak-asuh', $data);

        $response->assertRedirect('/admin/anak-asuh');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('anak_asuh', [
            'nama_lengkap' => 'Muhammad Fatih',
            'nama_panggilan' => 'Fatih',
        ]);
    }

    public function test_update_anak_asuh_updates_record(): void
    {
        $anak = AnakAsuh::first();

        $data = [
            'nama_lengkap' => 'Ahmad Budi Santoso',
            'nama_panggilan' => 'Budi',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2014-01-01',
            'pendidikan_terakhir' => 'SMP',
            'status_asuhan' => 'Aktif',
        ];

        $response = $this->put("/admin/anak-asuh/{$anak->id}", $data);

        $response->assertRedirect('/admin/anak-asuh');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('anak_asuh', [
            'id' => $anak->id,
            'nama_lengkap' => 'Ahmad Budi Santoso',
            'pendidikan_terakhir' => 'SMP',
        ]);
    }

    public function test_destroy_anak_asuh_deletes_record(): void
    {
        $anak = AnakAsuh::first();

        $response = $this->delete("/admin/anak-asuh/{$anak->id}");

        $response->assertRedirect('/admin/anak-asuh');
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('anak_asuh', [
            'id' => $anak->id,
        ]);
    }

    public function test_export_excel_can_be_downloaded(): void
    {
        $response = $this->get('/admin/anak-asuh/export/excel');

        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
    }

    public function test_export_pdf_can_be_downloaded(): void
    {
        $response = $this->get('/admin/anak-asuh/export/pdf');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_store_anak_asuh_with_photo_uploads_via_r2_service_and_sets_foto_url(): void
    {
        Storage::fake('public');

        $response = $this->post('/admin/anak-asuh', [
            'nama_lengkap' => 'Anak Foto Baru',
            'nama_panggilan' => 'Foto',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2015-01-01',
            'pendidikan_terakhir' => 'SD',
            'status_asuhan' => 'Aktif',
            'foto' => UploadedFile::fake()->image('anak.jpg'),
        ]);

        $response->assertRedirect('/admin/anak-asuh');
        $anak = AnakAsuh::where('nama_lengkap', 'Anak Foto Baru')->firstOrFail();
        $this->assertNotNull($anak->foto_path);
        $this->assertStringStartsWith('anak-asuh/', $anak->foto_path);
        Storage::disk('public')->assertExists($anak->foto_path);

        $this->assertNotNull($anak->foto_url);
        $this->assertStringContainsString($anak->foto_path, $anak->foto_url);
    }

    public function test_update_anak_asuh_with_new_photo_deletes_old_photo(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('anak-asuh/old_photo.jpg', 'old content');
        $anak = AnakAsuh::create([
            'nama_lengkap' => 'Anak Update Foto',
            'nama_panggilan' => 'Update',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2014-02-02',
            'pendidikan_terakhir' => 'SMP',
            'status_asuhan' => 'Aktif',
            'foto_path' => 'anak-asuh/old_photo.jpg',
        ]);

        $response = $this->put("/admin/anak-asuh/{$anak->id}", [
            'nama_lengkap' => 'Anak Update Foto Renamed',
            'nama_panggilan' => 'Update',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2014-02-02',
            'pendidikan_terakhir' => 'SMP',
            'status_asuhan' => 'Aktif',
            'foto' => UploadedFile::fake()->image('new_photo.jpg'),
        ]);

        $response->assertRedirect('/admin/anak-asuh');
        Storage::disk('public')->assertMissing('anak-asuh/old_photo.jpg');

        $anak->refresh();
        $this->assertNotEquals('anak-asuh/old_photo.jpg', $anak->foto_path);
        Storage::disk('public')->assertExists($anak->foto_path);
    }

    public function test_destroy_anak_asuh_deletes_associated_photo(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('anak-asuh/to_delete.jpg', 'content');
        $anak = AnakAsuh::create([
            'nama_lengkap' => 'Anak Untuk Dihapus',
            'nama_panggilan' => 'Hapus',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2016-03-03',
            'pendidikan_terakhir' => 'SD',
            'status_asuhan' => 'Aktif',
            'foto_path' => 'anak-asuh/to_delete.jpg',
        ]);

        $response = $this->delete("/admin/anak-asuh/{$anak->id}");
        $response->assertRedirect('/admin/anak-asuh');

        $this->assertDatabaseMissing('anak_asuh', ['id' => $anak->id]);
        Storage::disk('public')->assertMissing('anak-asuh/to_delete.jpg');
    }
}
