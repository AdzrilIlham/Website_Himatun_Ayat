<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Laravel\Fortify\Features;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::create([
            'name' => 'Admin Yayasan',
            'email' => 'admin@himmatunayat.org',
            'password' => Hash::make('password123'),
        ]);

        $admin->forceFill([
            'role' => 'admin',
            'email_verified_at' => now(),
        ])->save();
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Yayasan Himmatun Ayat');
        $response->assertSee('Masuk ke Dashboard');
    }

    public function test_admin_without_two_factor_is_redirected_to_mandatory_two_factor_setup(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@himmatunayat.org',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        // Since admin has not enrolled in TOTP yet, accessing dashboard redirects to 2FA setup
        $dashboardResponse = $this->get(route('admin.dashboard'));
        $dashboardResponse->assertRedirect(route('admin.two-factor-setup'));
    }

    public function test_admin_with_two_factor_is_challenged_with_totp(): void
    {
        $admin = User::first();
        $admin->forceFill([
            'two_factor_secret' => encrypt('JBSWY3DPEHPK3PXP'),
            'two_factor_confirmed_at' => now(),
        ])->save();

        $response = $this->post('/admin/login', [
            'email' => 'admin@himmatunayat.org',
            'password' => 'password123',
        ]);

        // Fortify redirects to two-factor-challenge before establishing authenticated session
        $response->assertRedirect(route('two-factor.login'));
        $this->assertGuest();
    }

    public function test_typo_domain_backdoor_is_prevented_and_fails_safely(): void
    {
        // Vulnerability F-01 regression test: alternate typo domain MUST NOT authenticate
        $response = $this->post('/admin/login', [
            'email' => 'admin@himatunayyat.org',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_demo_password_fallback_backdoor_is_prevented(): void
    {
        // Vulnerability F-01 regression test: password demo cannot bypass authentication
        $response = $this->post('/admin/login', [
            'email' => 'admin@himmatunayat.org',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_admin_cannot_login_with_invalid_password(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@himmatunayat.org',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_admin_can_logout(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)->post('/admin/logout');

        $this->assertGuest();
        $response->assertRedirect('/admin/login');
    }

    public function test_login_route_redirects_to_admin_login(): void
    {
        $response = $this->get('/login');

        $response->assertRedirect('/admin/login');
    }

    public function test_public_registration_is_disabled(): void
    {
        $this->assertFalse(Features::enabled(Features::registration()));

        $response = $this->get('/register');
        $response->assertStatus(404);

        $postResponse = $this->post('/register', [
            'name' => 'Attacker',
            'email' => 'attacker@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $postResponse->assertStatus(404);
    }

    public function test_login_throttling_rate_limiting(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', [
                'email' => 'admin@himmatunayat.org',
                'password' => 'wrong-pass',
            ]);
        }

        // 6th attempt must be throttled with HTTP 429 Too Many Requests
        $response = $this->post('/admin/login', [
            'email' => 'admin@himmatunayat.org',
            'password' => 'wrong-pass',
        ]);

        $response->assertStatus(429);
        $this->assertGuest();
    }

    public function test_password_reset_request_does_not_enumerate_users(): void
    {
        // Existing user
        $responseExisting = $this->post('/admin/forgot-password', [
            'email' => 'admin@himmatunayat.org',
        ]);
        $responseExisting->assertSessionHas('status');

        // Non-existent user
        $responseNonExistent = $this->post('/admin/forgot-password', [
            'email' => 'nonexistent@example.com',
        ]);
        // Generic response prevents account enumeration: both return the same status message
        $responseNonExistent->assertSessionHas('status');
        $responseNonExistent->assertSessionDoesntHaveErrors();
    }
}
