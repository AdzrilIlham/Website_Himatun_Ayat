<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\RecoveryCode;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class TwoFactorAuthTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Yayasan',
            'email' => 'admin@himmatunayat.org',
            'password' => Hash::make('password123'),
        ]);

        $this->admin->forceFill([
            'role' => 'admin',
            'email_verified_at' => now(),
        ])->save();
    }

    public function test_two_factor_setup_screen_can_be_rendered(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.two-factor-setup'));

        $response->assertStatus(200);
        $response->assertSee('Aktivasi Autentikasi Dua Faktor');
        $response->assertSee('Langkah 1: Pindai Kode QR');
        $response->assertSee('Langkah 2: Simpan Kode Pemulihan Darurat');
    }

    public function test_admin_can_confirm_two_factor_with_valid_code(): void
    {
        // Setup initial secret
        $this->actingAs($this->admin)->get(route('admin.two-factor-setup'));
        $this->admin->refresh();

        $provider = app(TwoFactorAuthenticationProvider::class);
        $secret = Crypt::decrypt($this->admin->two_factor_secret);
        $validCode = app(Google2FA::class)->getCurrentOtp($secret);

        $response = $this->actingAs($this->admin)->post(route('admin.two-factor-setup.confirm'), [
            'code' => $validCode,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->admin->refresh();
        $this->assertNotNull($this->admin->two_factor_confirmed_at);
    }

    public function test_admin_cannot_confirm_two_factor_with_invalid_code(): void
    {
        $this->actingAs($this->admin)->get(route('admin.two-factor-setup'));

        $response = $this->actingAs($this->admin)->post(route('admin.two-factor-setup.confirm'), [
            'code' => '999999',
        ]);

        $response->assertSessionHasErrors('code', null, 'confirmTwoFactorAuthentication');
        $this->admin->refresh();
        $this->assertNull($this->admin->two_factor_confirmed_at);
    }

    public function test_unconfirmed_admin_cannot_access_protected_admin_routes(): void
    {
        // Unconfirmed admin attempts to access dashboard
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.two-factor-setup'));
    }

    public function test_admin_can_complete_two_factor_login_challenge_with_valid_code(): void
    {
        $secret = 'JBSWY3DPEHPK3PXP';
        $this->admin->forceFill([
            'two_factor_secret' => Crypt::encrypt($secret),
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => Crypt::encrypt(json_encode([RecoveryCode::generate()])),
        ])->save();

        $validCode = app(Google2FA::class)->getCurrentOtp($secret);

        // Put user in Fortify challenged state
        $response = $this->withSession(['login.id' => $this->admin->id])
            ->post('/admin/two-factor-challenge', [
                'code' => $validCode,
            ]);

        // When device is untrusted, redirected to email-otp
        $response->assertRedirect(route('admin.email-otp.show'));
        $this->assertTrue(session()->has('login.totp_passed'));
    }

    public function test_admin_cannot_solve_two_factor_challenge_with_invalid_code(): void
    {
        $this->admin->forceFill([
            'two_factor_secret' => Crypt::encrypt('JBSWY3DPEHPK3PXP'),
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => Crypt::encrypt(json_encode([RecoveryCode::generate()])),
        ])->save();

        $response = $this->withSession(['login.id' => $this->admin->id])
            ->post('/admin/two-factor-challenge', [
                'code' => '000000',
            ]);

        $response->assertSessionHasErrors('code');
        $this->assertGuest();
    }

    public function test_admin_can_login_using_recovery_code_and_code_is_consumed(): void
    {
        $code1 = RecoveryCode::generate();
        $code2 = RecoveryCode::generate();

        $this->admin->forceFill([
            'two_factor_secret' => Crypt::encrypt('JBSWY3DPEHPK3PXP'),
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => Crypt::encrypt(json_encode([$code1, $code2])),
        ])->save();

        $response = $this->withSession(['login.id' => $this->admin->id])
            ->post('/admin/two-factor-challenge', [
                'recovery_code' => $code1,
            ]);

        $response->assertRedirect(route('admin.email-otp.show'));

        $this->admin->refresh();
        $remainingCodes = $this->admin->recoveryCodes();

        // Used recovery code must be removed/replaced and cannot be reused
        $this->assertNotContains($code1, $remainingCodes);
        $this->assertContains($code2, $remainingCodes);

        // Replay attempt with same recovery code must fail
        $replayResponse = $this->withSession(['login.id' => $this->admin->id])
            ->post('/admin/two-factor-challenge', [
                'recovery_code' => $code1,
            ]);

        $replayResponse->assertSessionHasErrors('recovery_code');
    }
}
