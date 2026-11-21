<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\PengajuanKegiatanController;
use App\Models\BeritaKegiatan;
use App\Models\PengajuanKegiatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengajuanKegiatanTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->withTwoFactor()->create();
    }

    /**
     * Halaman publik Berita & Kegiatan dapat diakses dan menampilkan agenda terjadwal.
     */
    public function test_public_berita_kegiatan_page_renders_scheduled_calendar_events(): void
    {
        $tanggalAgenda = now()->startOfMonth()->addDays(10)->toDateString();

        BeritaKegiatan::create([
            'tipe' => 'kegiatan',
            'kategori' => 'Sosial',
            'judul' => 'Santunan Akbar Anak Asuh',
            'slug' => 'santunan-akbar-anak-asuh',
            'ringkasan' => 'Santunan bersama komunitas.',
            'konten' => 'Santunan bersama komunitas.',
            'gambar' => 'potopalingatas.png',
            'lokasi' => 'Asrama Cibiru, Bandung',
            'penyelenggara' => 'Komunitas Berbagi',
            'tanggal' => $tanggalAgenda,
            'waktu_teks' => '08.00 - 11.00 WIB',
            'terjadwal_di_kalender' => true,
            'unggulan' => false,
        ]);

        $response = $this->get(route('berita-kegiatan.index'));

        $response->assertStatus(200);
        $response->assertSee('Kalender Kegiatan');
        $response->assertSee('Santunan Akbar Anak Asuh');
    }

    /**
     * User publik dapat mengirimkan pengajuan jadwal kegiatan yang valid.
     */
    public function test_public_user_can_submit_pengajuan_kegiatan(): void
    {
        $tanggal = now()->addDays(5)->toDateString();

        $response = $this->postJson(route('pengajuan-kegiatan.store'), [
            'nama_pemohon' => 'Komunitas Peduli Bandung',
            'nomor_whatsapp' => '081234567890',
            'tanggal' => $tanggal,
            'waktu' => '09.00 - 12.00 WIB',
            'jenis_kegiatan' => 'Santunan & Bakti Sosial',
            'rencana_kegiatan' => 'Berbagi makan siang dan doa bersama santri asuh.',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('pengajuan_kegiatans', [
            'nama_pemohon' => 'Komunitas Peduli Bandung',
            'nomor_whatsapp' => '081234567890',
            'waktu' => '09.00 - 12.00 WIB',
            'status' => 'menunggu',
        ]);
    }

    /**
     * Pengajuan dengan data wajib yang kosong ditolak oleh validasi.
     */
    public function test_public_submission_validates_required_fields(): void
    {
        $response = $this->postJson(route('pengajuan-kegiatan.store'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'nama_pemohon',
                'nomor_whatsapp',
                'tanggal',
                'waktu',
                'jenis_kegiatan',
                'rencana_kegiatan',
            ]);
    }

    /**
     * Pengunjung tanpa autentikasi admin tidak dapat mengakses kelola pengajuan kegiatan.
     */
    public function test_guest_cannot_access_admin_pengajuan_kegiatan_routes(): void
    {
        $pengajuan = PengajuanKegiatan::create([
            'nama_pemohon' => 'Tamu Uji',
            'nomor_whatsapp' => '081200001111',
            'tanggal' => now()->addDays(3)->toDateString(),
            'waktu' => '08.00 - 10.00 WIB',
            'jenis_kegiatan' => 'Kunjungan / Silaturahmi Komunitas',
            'rencana_kegiatan' => 'Silaturahmi rutin.',
            'status' => 'menunggu',
        ]);

        $this->get(route('admin.pengajuan-kegiatan.index'))->assertRedirect();
        $this->post(route('admin.pengajuan-kegiatan.setujui', $pengajuan))->assertRedirect();
        $this->post(route('admin.pengajuan-kegiatan.tolak', $pengajuan))->assertRedirect();
        $this->put(route('admin.pengajuan-kegiatan.update', $pengajuan), [])->assertRedirect();
        $this->delete(route('admin.pengajuan-kegiatan.destroy', $pengajuan))->assertRedirect();
    }

    /**
     * Admin dapat melihat daftar pengajuan serta menggunakan filter status dan pencarian.
     */
    public function test_admin_can_view_filter_and_search_pengajuan_kegiatan(): void
    {
        $this->actingAs($this->admin);

        PengajuanKegiatan::create([
            'nama_pemohon' => 'HIMA RPL UPI',
            'nomor_whatsapp' => '081211112222',
            'tanggal' => now()->addDays(4)->toDateString(),
            'waktu' => '08.00 - 10.00 WIB',
            'jenis_kegiatan' => 'Edukasi / Pelatihan / Workshop',
            'rencana_kegiatan' => 'Pelatihan komputer dasar.',
            'status' => 'menunggu',
        ]);

        PengajuanKegiatan::create([
            'nama_pemohon' => 'Majelis Taklim Al-Ikhlas',
            'nomor_whatsapp' => '081322223333',
            'tanggal' => now()->addDays(6)->toDateString(),
            'waktu' => '13.00 - 15.00 WIB',
            'jenis_kegiatan' => 'Peringatan Hari Besar Islam (PHBI)',
            'rencana_kegiatan' => 'Pengajian bersama.',
            'status' => 'ditolak',
        ]);

        $this->get(route('admin.pengajuan-kegiatan.index'))
            ->assertStatus(200)
            ->assertSee('Pengajuan Jadwal & Kunjungan', false)
            ->assertSee('HIMA RPL UPI')
            ->assertSee('Majelis Taklim Al-Ikhlas');

        $this->get(route('admin.pengajuan-kegiatan.index', ['status' => 'menunggu']))
            ->assertStatus(200)
            ->assertSee('HIMA RPL UPI')
            ->assertDontSee('Majelis Taklim Al-Ikhlas');

        $this->get(route('admin.pengajuan-kegiatan.index', ['search' => 'Al-Ikhlas']))
            ->assertStatus(200)
            ->assertSee('Majelis Taklim Al-Ikhlas')
            ->assertDontSee('HIMA RPL UPI');
    }

    /**
     * Admin dapat menyetujui (ACC) pengajuan dan agenda otomatis terbit di kalender publik.
     */
    public function test_admin_can_approve_pengajuan_and_publish_to_calendar(): void
    {
        $this->actingAs($this->admin);

        $tanggal = now()->addDays(7)->toDateString();
        $pengajuan = PengajuanKegiatan::create([
            'nama_pemohon' => 'Yayasan Sahabat Anak',
            'nomor_whatsapp' => '081299998888',
            'tanggal' => $tanggal,
            'waktu' => '09.00 - 11.00 WIB',
            'jenis_kegiatan' => 'Santunan & Bakti Sosial',
            'rencana_kegiatan' => 'Penyaluran paket sembako dan alat tulis.',
            'status' => 'menunggu',
        ]);

        $response = $this->post(route('admin.pengajuan-kegiatan.setujui', $pengajuan));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $pengajuan->refresh();
        $this->assertSame('disetujui', $pengajuan->status);
        $this->assertNotNull($pengajuan->berita_kegiatan_id);

        $this->assertDatabaseHas('berita_kegiatans', [
            'id' => $pengajuan->berita_kegiatan_id,
            'penyelenggara' => 'Yayasan Sahabat Anak',
            'waktu_teks' => '09.00 - 11.00 WIB',
            'terjadwal_di_kalender' => true,
        ]);
    }

    /**
     * Admin dapat menyetujui lebih dari 1 kegiatan di hari yang sama selama jeda minimal 1 jam terpenuhi.
     */
    public function test_admin_can_approve_multiple_events_on_same_date_with_one_hour_gap(): void
    {
        $this->actingAs($this->admin);

        $tanggal = now()->addDays(8)->toDateString();

        BeritaKegiatan::create([
            'tipe' => 'kegiatan',
            'kategori' => 'Sosial',
            'judul' => 'Agenda Pagi Terjadwal',
            'slug' => 'agenda-pagi-terjadwal',
            'ringkasan' => 'Kegiatan pagi.',
            'konten' => 'Kegiatan pagi.',
            'gambar' => 'potopalingatas.png',
            'lokasi' => 'Asrama Cibiru, Bandung',
            'penyelenggara' => 'Donatur Pagi',
            'tanggal' => $tanggal,
            'waktu_teks' => '08.00 - 11.00 WIB',
            'terjadwal_di_kalender' => true,
            'unggulan' => false,
        ]);

        // Jeda tepat 1 jam setelah pukul 11.00 => mulai pukul 12.00 (valid)
        $pengajuanSiang = PengajuanKegiatan::create([
            'nama_pemohon' => 'Komunitas Siang',
            'nomor_whatsapp' => '081277776666',
            'tanggal' => $tanggal,
            'waktu' => '12.00 - 15.00 WIB',
            'jenis_kegiatan' => 'Perlombaan / Fun Games',
            'rencana_kegiatan' => 'Fun games bersama santri.',
            'status' => 'menunggu',
        ]);

        $response = $this->post(route('admin.pengajuan-kegiatan.setujui', $pengajuanSiang));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $pengajuanSiang->refresh();
        $this->assertSame('disetujui', $pengajuanSiang->status);
        $this->assertEquals(2, BeritaKegiatan::whereDate('tanggal', $tanggal)->where('terjadwal_di_kalender', true)->count());
    }

    /**
     * Admin tidak dapat menyetujui pengajuan jika jam bentrok atau jeda kurang dari 1 jam.
     */
    public function test_admin_cannot_approve_overlapping_or_insufficient_gap_schedule(): void
    {
        $this->actingAs($this->admin);

        $tanggal = now()->addDays(9)->toDateString();

        BeritaKegiatan::create([
            'tipe' => 'kegiatan',
            'kategori' => 'Sosial',
            'judul' => 'Agenda Eksisting',
            'slug' => 'agenda-eksisting',
            'ringkasan' => 'Agenda eksisting.',
            'konten' => 'Agenda eksisting.',
            'gambar' => 'potopalingatas.png',
            'lokasi' => 'Asrama Cibiru, Bandung',
            'penyelenggara' => 'Komunitas A',
            'tanggal' => $tanggal,
            'waktu_teks' => '08.00 - 11.00 WIB',
            'terjadwal_di_kalender' => true,
            'unggulan' => false,
        ]);

        // Kasus 1: Beririsan (10.00 - 13.00)
        $pengajuanBentrok = PengajuanKegiatan::create([
            'nama_pemohon' => 'Komunitas Bentrok',
            'nomor_whatsapp' => '081233334444',
            'tanggal' => $tanggal,
            'waktu' => '10.00 - 13.00 WIB',
            'jenis_kegiatan' => 'Santunan & Bakti Sosial',
            'rencana_kegiatan' => 'Santunan.',
            'status' => 'menunggu',
        ]);

        $resBentrok = $this->post(route('admin.pengajuan-kegiatan.setujui', $pengajuanBentrok));
        $resBentrok->assertRedirect();
        $resBentrok->assertSessionHas('warning');
        $this->assertSame('menunggu', $pengajuanBentrok->fresh()->status);

        // Kasus 2: Jeda 0 jam / kurang dari 1 jam (11.00 - 13.00)
        $pengajuanKurangJeda = PengajuanKegiatan::create([
            'nama_pemohon' => 'Komunitas Mepet',
            'nomor_whatsapp' => '081244445555',
            'tanggal' => $tanggal,
            'waktu' => '11.00 - 13.00 WIB',
            'jenis_kegiatan' => 'Santunan & Bakti Sosial',
            'rencana_kegiatan' => 'Santunan mepet.',
            'status' => 'menunggu',
        ]);

        $resMepet = $this->post(route('admin.pengajuan-kegiatan.setujui', $pengajuanKurangJeda));
        $resMepet->assertRedirect();
        $resMepet->assertSessionHas('warning');
        $this->assertSame('menunggu', $pengajuanKurangJeda->fresh()->status);
    }

    /**
     * Admin dapat menolak pengajuan dan menghapus agenda terkait jika sebelumnya pernah disetujui.
     */
    public function test_admin_can_reject_pengajuan_and_remove_linked_calendar_event(): void
    {
        $this->actingAs($this->admin);

        $tanggal = now()->addDays(11)->toDateString();
        $kegiatan = BeritaKegiatan::create([
            'tipe' => 'kegiatan',
            'kategori' => 'Sosial',
            'judul' => 'Agenda Akan Ditolak',
            'slug' => 'agenda-akan-ditolak',
            'ringkasan' => 'Ringkasan.',
            'konten' => 'Konten.',
            'gambar' => 'potopalingatas.png',
            'lokasi' => 'Asrama Cibiru, Bandung',
            'penyelenggara' => 'Pemohon X',
            'tanggal' => $tanggal,
            'waktu_teks' => '09.00 - 11.00 WIB',
            'terjadwal_di_kalender' => true,
            'unggulan' => false,
        ]);

        $pengajuan = PengajuanKegiatan::create([
            'nama_pemohon' => 'Pemohon X',
            'nomor_whatsapp' => '081255556666',
            'tanggal' => $tanggal,
            'waktu' => '09.00 - 11.00 WIB',
            'jenis_kegiatan' => 'Santunan & Bakti Sosial',
            'rencana_kegiatan' => 'Rencana X.',
            'status' => 'disetujui',
            'berita_kegiatan_id' => $kegiatan->id,
        ]);

        $response = $this->post(route('admin.pengajuan-kegiatan.tolak', $pengajuan), [
            'catatan_admin' => 'Dibatalkan atas permintaan panitia.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('warning');

        $pengajuan->refresh();
        $this->assertSame('ditolak', $pengajuan->status);
        $this->assertSame('Dibatalkan atas permintaan panitia.', $pengajuan->catatan_admin);
        $this->assertNull($pengajuan->berita_kegiatan_id);
        $this->assertDatabaseMissing('berita_kegiatans', ['id' => $kegiatan->id]);
    }

    /**
     * Admin dapat memperbarui pengajuan yang sudah disetujui dan otomatis menyinkronkan ke kalender.
     */
    public function test_admin_can_update_approved_pengajuan_and_sync_calendar(): void
    {
        $this->actingAs($this->admin);

        $tanggal = now()->addDays(12)->toDateString();
        $kegiatan = BeritaKegiatan::create([
            'tipe' => 'kegiatan',
            'kategori' => 'Pendidikan',
            'judul' => 'Edukasi / Pelatihan / Workshop bersama Pemohon Lama',
            'slug' => 'edukasi-pemohon-lama',
            'ringkasan' => 'Rencana lama.',
            'konten' => 'Rencana lama.',
            'gambar' => 'potopalingatas.png',
            'lokasi' => 'Asrama Cibiru, Bandung',
            'penyelenggara' => 'Pemohon Lama',
            'tanggal' => $tanggal,
            'waktu_teks' => '08.00 - 10.00 WIB',
            'terjadwal_di_kalender' => true,
            'unggulan' => false,
        ]);

        $pengajuan = PengajuanKegiatan::create([
            'nama_pemohon' => 'Pemohon Lama',
            'nomor_whatsapp' => '081212121212',
            'tanggal' => $tanggal,
            'waktu' => '08.00 - 10.00 WIB',
            'jenis_kegiatan' => 'Edukasi / Pelatihan / Workshop',
            'rencana_kegiatan' => 'Rencana lama.',
            'status' => 'disetujui',
            'berita_kegiatan_id' => $kegiatan->id,
        ]);

        $response = $this->put(route('admin.pengajuan-kegiatan.update', $pengajuan), [
            'nama_pemohon' => 'Pemohon Baru',
            'nomor_whatsapp' => '081313131313',
            'tanggal' => $tanggal,
            'waktu' => '13.00 - 16.00 WIB',
            'jenis_kegiatan' => 'Edukasi / Pelatihan / Workshop',
            'rencana_kegiatan' => 'Rencana diperbarui.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pengajuan_kegiatans', [
            'id' => $pengajuan->id,
            'nama_pemohon' => 'Pemohon Baru',
            'waktu' => '13.00 - 16.00 WIB',
        ]);

        $this->assertDatabaseHas('berita_kegiatans', [
            'id' => $kegiatan->id,
            'penyelenggara' => 'Pemohon Baru',
            'waktu_teks' => '13.00 - 16.00 WIB',
            'ringkasan' => 'Rencana diperbarui.',
        ]);
    }

    /**
     * Admin dapat menghapus pengajuan beserta agenda kalender yang tertaut.
     */
    public function test_admin_can_delete_pengajuan_and_linked_calendar_event(): void
    {
        $this->actingAs($this->admin);

        $tanggal = now()->addDays(14)->toDateString();
        $kegiatan = BeritaKegiatan::create([
            'tipe' => 'kegiatan',
            'kategori' => 'Sosial',
            'judul' => 'Agenda Hapus',
            'slug' => 'agenda-hapus',
            'ringkasan' => 'Hapus.',
            'konten' => 'Hapus.',
            'gambar' => 'potopalingatas.png',
            'lokasi' => 'Asrama Cibiru, Bandung',
            'penyelenggara' => 'Pemohon Hapus',
            'tanggal' => $tanggal,
            'waktu_teks' => '09.00 - 11.00 WIB',
            'terjadwal_di_kalender' => true,
            'unggulan' => false,
        ]);

        $pengajuan = PengajuanKegiatan::create([
            'nama_pemohon' => 'Pemohon Hapus',
            'nomor_whatsapp' => '081266667777',
            'tanggal' => $tanggal,
            'waktu' => '09.00 - 11.00 WIB',
            'jenis_kegiatan' => 'Santunan & Bakti Sosial',
            'rencana_kegiatan' => 'Hapus.',
            'status' => 'disetujui',
            'berita_kegiatan_id' => $kegiatan->id,
        ]);

        $response = $this->delete(route('admin.pengajuan-kegiatan.destroy', $pengajuan));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('pengajuan_kegiatans', ['id' => $pengajuan->id]);
        $this->assertDatabaseMissing('berita_kegiatans', ['id' => $kegiatan->id]);
    }

    /**
     * Helper parseTimeRange membaca berbagai format jam dengan akurat dan menolak format tidak valid.
     */
    public function test_parse_time_range_parses_valid_and_rejects_invalid_formats(): void
    {
        $this->assertEquals(['start' => 8.0, 'end' => 11.0], PengajuanKegiatanController::parseTimeRange('08.00 - 11.00 WIB'));
        $this->assertEquals(['start' => 13.5, 'end' => 15.0], PengajuanKegiatanController::parseTimeRange('13.30–15.00 WIB'));
        $this->assertEquals(['start' => 9.0, 'end' => 12.0], PengajuanKegiatanController::parseTimeRange('09:00 s/d 12:00'));

        $this->assertNull(PengajuanKegiatanController::parseTimeRange('14.00 - 12.00 WIB'));
        $this->assertNull(PengajuanKegiatanController::parseTimeRange('pagi sampai siang'));
        $this->assertNull(PengajuanKegiatanController::parseTimeRange(null));
    }
}
