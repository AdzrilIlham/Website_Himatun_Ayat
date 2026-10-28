<?php

namespace Tests\Feature;

use App\Livewire\User\KampanyeDonasi;
use App\Models\Kampanye;
use Livewire\Livewire;
use Tests\TestCase;

class KampanyeDonasiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_kampanye_page_can_be_rendered(): void
    {
        $response = $this->get('/kampanye');

        $response->assertStatus(200);
        $response->assertSee('Formulir Donatur');
        $response->assertSee('Donasi Anda');
        $response->assertSee('Membantu Kami');
        $response->assertSee('Hasil Donasi Yang Anda Berikan');
    }

    public function test_user_can_submit_donation_through_livewire(): void
    {
        $kampanye = Kampanye::first();

        Livewire::test(KampanyeDonasi::class)
            ->set('selectedKampanyeId', $kampanye?->id)
            ->set('selectedNominal', 50000)
            ->set('namaDonatur', 'Ahmad Sahroni')
            ->set('noTelepon', '081234567890')
            ->set('email', 'ahmad@example.com')
            ->set('doaHarapan', 'Semoga berkah untuk adik-adik santri.')
            ->call('confirmDonasi')
            ->assertSet('showPaymentModal', true)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('donasi', [
            'nama_donatur' => 'Ahmad Sahroni',
            'nominal' => 50000,
            'no_whatsapp' => '081234567890',
        ]);
    }

    public function test_anonymous_donation_saves_as_hamba_allah(): void
    {
        Livewire::test(KampanyeDonasi::class)
            ->set('isAnonim', true)
            ->set('selectedNominal', 100000)
            ->set('noTelepon', '081987654321')
            ->call('confirmDonasi')
            ->assertSet('showPaymentModal', true);

        $this->assertDatabaseHas('donasi', [
            'nama_donatur' => 'Hamba Allah',
            'nominal' => 100000,
        ]);
    }
}
