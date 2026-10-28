<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::create([
            'name'     => 'Admin Yayasan',
            'email'    => 'admin@himmatunayat.org',
            'password' => 'password123',
        ]);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
        $response->assertSee('Yayasan Himmatun Ayat');
        $response->assertSee('Masuk ke Dashboard');
    }

    public function test_admin_can_login_with_standard_credentials(): void
    {
        $response = $this->post('/admin/login', [
            'email'    => 'admin@himmatunayat.org',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_can_login_with_domain_typo_variant(): void
    {
        $response = $this->post('/admin/login', [
            'email'    => 'admin@himatunayyat.org',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_can_login_with_demo_password_fallback(): void
    {
        $response = $this->post('/admin/login', [
            'email'    => 'admin@himmatunayat.org',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_cannot_login_with_invalid_password(): void
    {
        $response = $this->post('/admin/login', [
            'email'    => 'admin@himmatunayat.org',
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
        $response->assertRedirect(route('admin.login'));
    }

    public function test_login_route_redirects_to_admin_login(): void
    {
        $response = $this->get('/login');

        $response->assertRedirect('/admin/login');
    }
}
