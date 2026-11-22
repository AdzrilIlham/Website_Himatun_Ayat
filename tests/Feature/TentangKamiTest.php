<?php

namespace Tests\Feature;

use Tests\TestCase;

class TentangKamiTest extends TestCase
{
    public function test_tentang_kami_page_renders_successfully_with_complete_organization_structure(): void
    {
        $response = $this->get(route('tentang-kami'));

        $response->assertOk();

        // Verifikasi data struktur organisasi lengkap sesuai bagan resmi yayasan
        $response->assertSee('Imran Rosadi, S.Ag.');
        $response->assertSee('Ust. Atep Kaokabadin, S.Pd.I.');
        $response->assertSee('H. Lily Koswara'); // Pengawas ke-2 yang sebelumnya kurang
        $response->assertSee('Ust. Yusuf Hamdani, S.Pd.I.');
        $response->assertSee('Asep Dedi, A.Ma.');
        $response->assertSee('Ust. Cical Irmansyah, Lc.');
        $response->assertSee('Euis Nuraeni, S.Pd.I.');
        $response->assertSee('DIVISI HUMAS');
        $response->assertSee('Apid');
        $response->assertSee('Hj. Lilis');
        $response->assertSee('Pak Harli');
        $response->assertSee('DIVISI WIRAUSAHA');
        $response->assertSee('Herdianto');
        $response->assertSee('Rian Setiawan');

        // Verifikasi elemen interaktif dan animasi pipa air bercabang (scroll-scrub pipeline)
        $response->assertSee('data-org-tree', false);
        $response->assertSee('data-pipe-connector="1"', false);
        $response->assertSee('data-pipe-connector="2"', false);
        $response->assertSee('data-pipe-connector="3"', false);
        $response->assertSee('data-pipe-connector="4"', false);
        $response->assertSee('pipe-track', false);
        $response->assertSee('pipe-water', false);
        $response->assertSee('pipeWaterGrad', false);
    }
}
