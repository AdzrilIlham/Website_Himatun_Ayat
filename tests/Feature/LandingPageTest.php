<?php

namespace Tests\Feature;

use App\Models\BeritaKegiatan;
use App\Models\Donasi;
use App\Models\Kampanye;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

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
            ->assertDontSee('Paket Donasi Cepat Terarah');
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
