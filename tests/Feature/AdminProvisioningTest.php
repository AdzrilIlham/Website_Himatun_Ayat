<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AdminProvisioningTest extends TestCase
{
    use RefreshDatabase;

    public function test_initial_admin_command_creates_first_admin_interactively(): void
    {
        $this->assertDatabaseCount('users', 0);

        // Run Artisan command admin:create-initial with interactive prompt responses
        $this->artisan('admin:create-initial')
            ->expectsQuestion('Nama Lengkap Administrator', 'Ahmad Fauzi')
            ->expectsQuestion('Alamat Email', 'ahmad@himmatunayat.org')
            ->expectsQuestion('Password Administrator (minimal 8 karakter)', 'Secret12345')
            ->expectsQuestion('Konfirmasi Password', 'Secret12345')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'name' => 'Ahmad Fauzi',
            'email' => 'ahmad@himmatunayat.org',
            'role' => 'admin',
            'email_verified_at' => null, // Must verify before accessing dashboard
            'two_factor_confirmed_at' => null, // Must enroll TOTP
        ]);
    }

    public function test_initial_admin_command_fails_if_an_admin_already_exists(): void
    {
        // Seed first admin
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@himmatunayat.org',
            'password' => Hash::make('password123'),
        ]);
        $admin->forceFill(['role' => 'admin'])->save();

        // Repeated bootstrap command must be blocked immediately
        $this->artisan('admin:create-initial')
            ->assertExitCode(1);

        $this->assertDatabaseCount('users', 1);
    }

    public function test_authenticated_admin_can_provision_additional_admin_account(): void
    {
        Notification::fake();

        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@himmatunayat.org',
            'password' => Hash::make('password123'),
        ]);
        $admin->forceFill([
            'role' => 'admin',
            'email_verified_at' => now(),
            'two_factor_secret' => Crypt::encrypt('JBSWY3DPEHPK3PXP'),
            'two_factor_confirmed_at' => now(),
        ])->save();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Admin Baru',
            'email' => 'baru@himmatunayat.org',
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'Admin Baru',
            'email' => 'baru@himmatunayat.org',
            'role' => 'admin',
            'email_verified_at' => null,
            'two_factor_confirmed_at' => null,
        ]);

        // Password reset link notification was sent
        $newAdmin = User::where('email', 'baru@himmatunayat.org')->first();
        Notification::assertSentTo(
            $newAdmin,
            ResetPassword::class
        );
    }

    public function test_role_cannot_be_manipulated_via_mass_assignment(): void
    {
        $user = User::create([
            'name' => 'Attacker',
            'email' => 'attacker@example.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin', // Attempt unauthorized role injection
        ]);

        // Role attribute is excluded from $fillable to protect server-side authority
        $this->assertNull($user->role);
        $this->assertFalse($user->isAdmin());
    }

    public function test_guest_cannot_provision_admin_accounts(): void
    {
        $response = $this->post(route('admin.users.store'), [
            'name' => 'Unauthorized Admin',
            'email' => 'unauthorized@example.com',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('users', ['email' => 'unauthorized@example.com']);
    }

    public function test_non_admin_cannot_access_protected_routes(): void
    {
        $normalUser = User::create([
            'name' => 'Regular User',
            'email' => 'regular@example.com',
            'password' => Hash::make('password123'),
        ]);
        // Do not assign admin role

        $response = $this->actingAs($normalUser)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_unverified_admin_cannot_access_dashboard(): void
    {
        $unverifiedAdmin = User::create([
            'name' => 'Unverified Admin',
            'email' => 'unverified@himmatunayat.org',
            'password' => Hash::make('password123'),
        ]);
        $unverifiedAdmin->forceFill([
            'role' => 'admin',
            'email_verified_at' => null,
            'two_factor_confirmed_at' => now(),
        ])->save();

        $response = $this->actingAs($unverifiedAdmin)->get(route('admin.dashboard'));
        $response->assertRedirect(route('verification.notice'));
    }
}
