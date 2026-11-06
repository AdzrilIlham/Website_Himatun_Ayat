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

    public function test_payment_modal_matches_mockup_and_can_proceed_to_payment_step(): void
    {
        $test = Livewire::test(KampanyeDonasi::class)
            ->set('namaDonatur', 'Siti Aminah')
            ->set('noTelepon', '081234567890')
            ->set('selectedNominal', 50000)
            ->call('confirmDonasi')
            ->assertSet('showPaymentModal', true)
            ->assertSet('paymentStep', 1)
            ->assertSee('Yayasan Himatun')
            ->assertSee('Selesaikan pembayaran anda')
            ->assertSee('ID Pembayaran:')
            ->assertSee('QRIS')
            ->assertSee('Transfer Bank BRI')
            ->assertSee('Transfer Bank BCA')
            ->assertSee('Rincian Pembayaran')
            ->assertSee('Biaya Donasi')
            ->assertSee('Biaya Admin')
            ->assertSee('Bayar Sekarang');

        // Select Transfer Bank BRI
        $test->call('selectMetodePembayaran', 'Transfer Bank BRI')
            ->assertSet('metodePembayaran', 'Transfer Bank BRI');

        // Click Bayar Sekarang -> Step 2
        $test->call('payNow')
            ->assertSet('paymentStep', 2)
            ->assertSee('Instruksi Pembayaran')
            ->assertSee('Nomor Rekening Resmi:')
            ->assertSee('Kembali');

        // Back to step 1
        $test->call('backToStepOne')
            ->assertSet('paymentStep', 1);

        // Close modal
        $test->call('closePaymentModal')
            ->assertSet('showPaymentModal', false);

        $firstPaymentId = $test->get('paymentId');
        $this->assertMatchesRegularExpression('/^DNS-\d{8}-\d{3}$/', $firstPaymentId);

        // Second transaction generates a different payment ID
        $test->call('confirmDonasi')
            ->assertSet('showPaymentModal', true);

        $secondPaymentId = $test->get('paymentId');
        $this->assertMatchesRegularExpression('/^DNS-\d{8}-\d{3}$/', $secondPaymentId);
        $this->assertNotSame($firstPaymentId, $secondPaymentId);
    }
}
