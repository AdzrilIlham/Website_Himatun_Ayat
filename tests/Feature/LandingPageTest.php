<?php

namespace Tests\Feature;

use App\Models\AnakAsuh;
use App\Models\BeritaKegiatan;
use App\Models\Donasi;
use App\Models\Kampanye;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_displays_impact_statistics_from_database(): void
    {
        $this->travelTo('2026-11-23 09:00:00');

        $aktifCampaigns = collect(range(1, 5))->map(fn (int $index): Kampanye => $this->createKampanye([
            'judul' => 'Program Aktif '.$index,
            'slug' => 'program-aktif-'.$index,
            'status' => 'aktif',
        ]));

        $this->createKampanye([
            'judul' => 'Program Selesai',
            'slug' => 'program-selesai',
            'status' => 'selesai',
        ]);

        $this->createKampanye([
            'judul' => 'Program Draft',
            'slug' => 'program-draft',
            'status' => 'draft',
        ]);

        Donasi::create([
            'kampanye_id' => $aktifCampaigns[0]->id,
            'nama_donatur' => 'Donatur Pertama',
            'nominal' => 250000000,
            'metode_pembayaran' => 'Transfer Bank (BSI)',
            'status' => 'verified',
        ]);

        Donasi::create([
            'kampanye_id' => $aktifCampaigns[1]->id,
            'nama_donatur' => 'Donatur Kedua',
            'nominal' => 175200000,
            'metode_pembayaran' => 'QRIS',
            'status' => 'verified',
        ]);

        Donasi::create([
            'kampanye_id' => $aktifCampaigns[0]->id,
            'nama_donatur' => 'Donatur Pending',
            'nominal' => 50000000,
            'metode_pembayaran' => 'QRIS',
            'status' => 'pending',
        ]);

        Donasi::create([
            'kampanye_id' => $aktifCampaigns[0]->id,
            'nama_donatur' => 'Donatur Ditolak',
            'nominal' => 25000000,
            'metode_pembayaran' => 'QRIS',
            'status' => 'rejected',
        ]);

        foreach (range(1, 3) as $index) {
            AnakAsuh::create([
                'nama_lengkap' => 'Santri Aktif '.$index,
                'jenis_kelamin' => $index % 2 === 0 ? 'P' : 'L',
                'status_asuhan' => 'Aktif',
            ]);
        }

        AnakAsuh::create([
            'nama_lengkap' => 'Santri Alumni',
            'jenis_kelamin' => 'L',
            'status_asuhan' => 'Alumni',
        ]);

        AnakAsuh::create([
            'nama_lengkap' => 'Santri Non Aktif',
            'jenis_kelamin' => 'P',
            'status_asuhan' => 'Non-Aktif',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertViewHas('impactStats', [
                'tahun_akumulasi' => 2026,
                'total_dana_nominal' => 425200000.0,
                'total_dana_formatted' => 'Rp 425,2 Juta',
                'anak_asuh_aktif' => 3,
                'anak_asuh_formatted' => '3',
                'program_aktif' => 5,
                'program_aktif_formatted' => '5',
            ])
            ->assertSee('Akumulasi s.d. 2026')
            ->assertSee('Total Dana Amanah Terhimpun')
            ->assertSee('data-impact-stat="total-dana">Rp 425,2 Juta</h3>', false)
            ->assertSee('data-impact-stat="anak-asuh">3</h3>', false)
            ->assertSee('data-impact-stat="program-aktif">5</h3>', false)
            ->assertDontSee('Rp. 400 Juta+')
            ->assertDontSee('250+')
            ->assertDontSee('Akumulasi 2025');
    }

    public function test_landing_page_formats_compact_currency_across_thresholds(): void
    {
        $kampanye = $this->createKampanye();

        Donasi::create([
            'kampanye_id' => $kampanye->id,
            'nama_donatur' => 'Donatur Ribuan',
            'nominal' => 750000,
            'metode_pembayaran' => 'QRIS',
            'status' => 'verified',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('data-impact-stat="total-dana">Rp 750.000</h3>', false);

        Donasi::create([
            'kampanye_id' => $kampanye->id,
            'nama_donatur' => 'Donatur Jutaan Bulat',
            'nominal' => 1250000,
            'metode_pembayaran' => 'QRIS',
            'status' => 'verified',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('data-impact-stat="total-dana">Rp 2 Juta</h3>', false);

        Donasi::create([
            'kampanye_id' => $kampanye->id,
            'nama_donatur' => 'Donatur Miliar',
            'nominal' => 1498000000,
            'metode_pembayaran' => 'Transfer Bank (BSI)',
            'status' => 'verified',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('data-impact-stat="total-dana">Rp 1,5 Miliar</h3>', false);
    }

    public function test_landing_page_shows_only_verified_donations_in_the_ticker(): void
    {
        $kampanye = $this->createKampanye();

        Donasi::create([
            'kampanye_id' => $kampanye->id,
            'nama_donatur' => 'Donatur Terverifikasi',
            'nominal' => 50000,
            'metode_pembayaran' => 'QRIS',
            'status' => 'verified',
        ]);

        Donasi::create([
            'kampanye_id' => $kampanye->id,
            'nama_donatur' => 'Donatur Pending',
            'nominal' => 25000,
            'metode_pembayaran' => 'QRIS',
            'status' => 'pending',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Donatur Terverifikasi')
            ->assertSee('Rp50.000')
            ->assertDontSee('Donatur Pending');
    }

    public function test_landing_page_renders_active_campaigns_with_real_progress_and_links(): void
    {
        $kampanye = $this->createKampanye([
            'judul' => 'Renovasi Ruang Belajar',
            'slug' => 'renovasi-ruang-belajar',
            'target_dana' => 1000000,
            'dana_terkumpul' => 250000,
        ]);

        Donasi::create([
            'kampanye_id' => $kampanye->id,
            'nama_donatur' => 'Hamba Allah',
            'nominal' => 250000,
            'metode_pembayaran' => 'QRIS',
            'status' => 'verified',
        ]);

        $draft = $this->createKampanye([
            'judul' => 'Kampanye Masih Draft',
            'slug' => 'kampanye-masih-draft',
            'status' => 'draft',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Renovasi Ruang Belajar')
            ->assertSee('Rp 250.000')
            ->assertSee('Rp 1.000.000')
            ->assertSee('25%')
            ->assertSee('1 Donatur')
            ->assertSee(route('kampanye.index', ['program' => 'renovasi-ruang-belajar']))
            ->assertDontSee($draft->judul);
    }

    public function test_landing_page_shows_latest_four_published_news_with_their_own_links(): void
    {
        $terbaru = collect(range(0, 4))->map(fn (int $index): BeritaKegiatan => BeritaKegiatan::factory()->create([
            'judul' => 'Kabar Nomor '.$index,
            'tanggal' => today()->subDays($index),
        ]));

        $draft = BeritaKegiatan::factory()->create([
            'judul' => 'Kabar Draft',
            'status' => 'draft',
        ]);

        $mendatang = BeritaKegiatan::factory()->create([
            'judul' => 'Kabar Mendatang',
            'tanggal' => today()->addDay(),
        ]);

        $response = $this->get(route('home'))->assertOk();

        foreach ($terbaru->take(4) as $berita) {
            $response->assertSee($berita->judul);
            $response->assertSee(route('berita-kegiatan.show', $berita->slug));
        }

        $response
            ->assertDontSee($terbaru->last()->judul)
            ->assertDontSee($draft->judul)
            ->assertDontSee($mendatang->judul);
    }

    public function test_landing_page_handles_empty_data_without_errors(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Belum ada kabar terbaru')
            ->assertDontSee('Paket Donasi Cepat Terarah')
            ->assertSee('data-impact-stat="total-dana">Rp 0</h3>', false)
            ->assertSee('data-impact-stat="anak-asuh">0</h3>', false)
            ->assertSee('data-impact-stat="program-aktif">0</h3>', false)
            ->assertDontSee('Rp. 400 Juta+')
            ->assertDontSee('250+');
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function createKampanye(array $overrides = []): Kampanye
    {
        return Kampanye::create(array_replace([
            'judul' => 'Paket Makan & Nutrisi Harian',
            'slug' => 'paket-makan-nutrisi-harian',
            'kategori' => 'Nutrisi',
            'deskripsi' => 'Membantu makan siang bergizi untuk santri binaan.',
            'target_dana' => 15000000,
            'dana_terkumpul' => 0,
            'status' => 'aktif',
        ], $overrides));
    }
}
