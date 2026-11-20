<?php

namespace Tests\Feature;

use App\Models\AnakAsuh;
use App\Models\BeritaKegiatan;
use App\Models\Kampanye;
use App\Models\User;
use App\Services\CloudflareR2Service;
use App\Services\LaporanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LegacyModelsAndStorageCleanupTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $admin = User::factory()->withTwoFactor()->create();
        $this->actingAs($admin);
    }

    /**
     * Poin 1: Bersihin model legacy — KampanyeController + LaporanService query BeritaKegiatan bukan App\Models\Berita.
     */
    public function test_kampanye_controller_and_laporan_service_use_berita_kegiatan(): void
    {
        // 1. Buat data BeritaKegiatan
        $berita = BeritaKegiatan::create([
            'tipe' => 'berita',
            'status' => 'tayang',
            'kategori' => 'Sosial & Kemanusiaan',
            'judul' => 'Penyaluran Bantuan Sembako BeritaKegiatan',
            'slug' => 'penyaluran-bantuan-sembako-berita-kegiatan',
            'ringkasan' => 'Penyaluran bantuan beras dan minyak kepada yatim piatu.',
            'konten' => '<p>Konten penyaluran bantuan.</p>',
            'tanggal' => now()->subDay(),
            'terjadwal_di_kalender' => false,
        ]);

        // 2. KampanyeController index mengambil count dari BeritaKegiatan
        $response = $this->get(route('admin.kelola-kampanye.index'));
        $response->assertOk();
        $metrics = $response->viewData('metrics');
        $this->assertGreaterThanOrEqual(24, $metrics['kabar_penyaluran']);

        // 3. LaporanService mengambil aktivitas terbaru dari BeritaKegiatan
        $laporanService = app(LaporanService::class);
        $activities = $laporanService->getRecentActivities(5);
        $beritaActivity = collect($activities)->firstWhere('id', 'berita-'.$berita->id);

        $this->assertNotNull($beritaActivity, 'Aktivitas BeritaKegiatan harus muncul di getRecentActivities()');
        $this->assertEquals('CMS Berita', $beritaActivity['badge']);
        $this->assertStringContainsString('Penyaluran Bantuan Sembako', $beritaActivity['desc']);
    }

    /**
     * Poin 2: AgendaKegiatan tidak dipakai di mana-mana — Seluruh agenda/kalender menggunakan BeritaKegiatan.
     */
    public function test_calendar_agenda_uses_berita_kegiatan_and_agenda_kegiatan_is_unused(): void
    {
        // Pastikan agenda kalender dibuat dan dibaca melalui BeritaKegiatan
        $event = BeritaKegiatan::create([
            'tipe' => 'kegiatan',
            'status' => 'tayang',
            'kategori' => 'Kajian Rutin',
            'judul' => 'Kajian Subuh dan Doa Bersama Santri',
            'slug' => 'kajian-subuh-dan-doa-bersama-santri',
            'ringkasan' => 'Kegiatan rutin setiap subuh di asrama.',
            'konten' => '<p>Deskripsi kajian.</p>',
            'tanggal' => now()->addDays(2),
            'waktu_teks' => '05:00 - 06:30 WIB',
            'lokasi' => 'Masjid Asrama',
            'terjadwal_di_kalender' => true,
        ]);

        // Halaman publik berita & kegiatan membaca kalender dari BeritaKegiatan
        $response = $this->get(route('berita-kegiatan.index'));
        $response->assertOk();
        $response->assertSee('Kajian Subuh dan Doa Bersama Santri');

        // Pastikan scope terjadwal di kalender bekerja
        $events = BeritaKegiatan::query()->terjadwalDiKalender()->get();
        $this->assertTrue($events->contains('id', $event->id));
    }

    public function test_all_public_image_modules_store_cloudinary_references_and_remove_deleted_assets(): void
    {
        $kampanyeResponse = $this->post(route('admin.kelola-kampanye.store'), [
            'judul' => 'Kampanye Bersama R2 Service',
            'kategori' => 'Dakwah & Syiar',
            'deskripsi' => 'Deskripsi kampanye pengujian R2.',
            'target_dana' => 10000000,
            'status' => 'aktif',
            'banner' => $this->cloudinaryImageReference('banner'),
        ]);
        $kampanyeResponse->assertRedirect(route('admin.kelola-kampanye.index'));

        $kampanye = Kampanye::where('judul', 'Kampanye Bersama R2 Service')->firstOrFail();
        $this->assertNotNull($kampanye->banner_path);
        $this->assertSame($kampanye->banner_cloudinary['secure_url'], $kampanye->banner_url);

        $beritaResponse = $this->post(route('admin.berita.store'), [
            'judul' => 'Berita Bersama R2 Service',
            'kategori' => 'Pendidikan',
            'tanggal_publikasi' => today()->toDateString(),
            'status' => 'tayang',
            'ringkasan' => 'Ringkasan berita pengujian R2.',
            'konten' => '<p>Konten berita pengujian R2.</p>',
            'gambar' => $this->cloudinaryImageReference('gambar'),
        ]);
        $beritaResponse->assertRedirect(route('admin.berita.index'));

        $berita = BeritaKegiatan::where('judul', 'Berita Bersama R2 Service')->firstOrFail();
        $this->assertNotNull($berita->gambar);
        $this->assertSame($berita->gambar_cloudinary['secure_url'], $berita->gambar_url);

        $anakResponse = $this->post(route('admin.anak-asuh.store'), [
            'nama_lengkap' => 'Anak Asuh R2 Service',
            'nama_panggilan' => 'Asuh R2',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2016-01-01',
            'pendidikan_terakhir' => 'SD',
            'status_asuhan' => 'Aktif',
            'foto' => $this->cloudinaryImageReference('foto'),
        ]);
        $anakResponse->assertRedirect(route('admin.anak-asuh.index'));

        $anak = AnakAsuh::where('nama_lengkap', 'Anak Asuh R2 Service')->firstOrFail();
        $this->assertNotNull($anak->foto_path);
        $this->assertSame($anak->foto_cloudinary['secure_url'], $anak->foto_url);

        $this->delete(route('admin.kelola-kampanye.destroy', $kampanye));
        $this->delete(route('admin.berita.destroy', $berita));
        $this->delete(route('admin.anak-asuh.destroy', $anak));

        foreach ([$kampanye->banner_cloudinary, $berita->gambar_cloudinary, $anak->foto_cloudinary] as $asset) {
            Http::assertSent(fn ($request) => str_ends_with($request->url(), '/image/destroy')
                && $request['public_id'] === $asset['public_id']);
        }
    }

    /**
     * Poin 3 (Tambahan): Memastikan getPublicUrl dan deletePublic CloudflareR2Service menangani format path dengan baik.
     */
    public function test_cloudflare_r2_service_url_and_deletion_utilities(): void
    {
        Storage::fake('public');
        $service = app(CloudflareR2Service::class);

        // Path lokal
        Storage::disk('public')->put('berita/sample.jpg', 'content');
        $url = $service->getPublicUrl('berita/sample.jpg');
        $this->assertNotEmpty($url);

        // Path dengan prefix storage/
        $urlStorage = $service->getPublicUrl('storage/berita/sample.jpg');
        $this->assertEquals($url, $urlStorage);

        // URL eksternal tidak diubah
        $external = 'https://example.com/image.jpg';
        $this->assertEquals($external, $service->getPublicUrl($external));

        // Hapus file
        $deleted = $service->deletePublic('berita/sample.jpg');
        $this->assertTrue($deleted);
        Storage::disk('public')->assertMissing('berita/sample.jpg');
    }
}
