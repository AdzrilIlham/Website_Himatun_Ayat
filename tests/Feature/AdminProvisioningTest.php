<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
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

    public function test_admin_can_resend_password_setup_invitation_to_unverified_admin(): void
    {
        Notification::fake();
        $admin = User::factory()->create();
        $invitee = User::factory()->unverified()->create();

        $response = $this->actingAs($admin)->post(route('admin.users.resend-invitation', $invitee));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', 'Undangan administrator berhasil dikirim ulang.');
        Notification::assertSentTo($invitee, ResetPassword::class);
    }

    public function test_resending_invitation_is_rate_limited(): void
    {
        Notification::fake();
        $admin = User::factory()->create();
        $invitee = User::factory()->unverified()->create();

        $this->actingAs($admin)->post(route('admin.users.resend-invitation', $invitee))
            ->assertRedirect(route('admin.users.index'));
        $secondResponse = $this->post(route('admin.users.resend-invitation', $invitee));

        $secondResponse->assertSessionHas('error');
        Notification::assertSentToTimes($invitee, ResetPassword::class, 1);
    }

    public function test_admin_cannot_resend_invitation_after_email_verification(): void
    {
        Notification::fake();
        $admin = User::factory()->create();
        $verifiedAdmin = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.users.resend-invitation', $verifiedAdmin));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('error', 'Undangan tidak dapat dikirim ulang karena email administrator sudah terverifikasi.');
        Notification::assertNothingSent();
    }

    public function test_failed_invitation_delivery_does_not_report_success(): void
    {
        Notification::fake();
        $admin = User::factory()->create();
        $invitee = User::factory()->unverified()->create();
        DB::table('password_reset_tokens')->insert([
            'email' => $invitee->email,
            'token' => app('hash')->make('existing-reset-token'),
            'created_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.resend-invitation', $invitee));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('error', 'Undangan gagal dikirim. Silakan coba lagi nanti.');
        Notification::assertNothingSent();
    }

    public function test_guest_cannot_resend_administrator_invitation(): void
    {
        $invitee = User::factory()->unverified()->create();

        $response = $this->post(route('admin.users.resend-invitation', $invitee));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_update_another_admin_and_reset_security_when_email_changes(): void
    {
        $admin = User::factory()->create();
        $target = User::factory()->withTwoFactor()->create([
            'email' => 'old-admin@example.com',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $target), [
            'name' => 'Updated Admin',
            'email' => ' NEW-ADMIN@example.com ',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'name' => 'Updated Admin',
            'email' => 'new-admin@example.com',
            'email_verified_at' => null,
            'two_factor_secret' => null,
            'two_factor_confirmed_at' => null,
        ]);
    }

    public function test_admin_email_change_invalidates_existing_password_setup_link(): void
    {
        $admin = User::factory()->create();
        $target = User::factory()->unverified()->create(['email' => 'old-admin@example.com']);
        DB::table('password_reset_tokens')->insert([
            'email' => $target->email,
            'token' => app('hash')->make('existing-reset-token'),
            'created_at' => now(),
        ]);

        $this->actingAs($admin)->put(route('admin.users.update', $target), [
            'name' => $target->name,
            'email' => 'new-admin@example.com',
        ]);

        $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'old-admin@example.com']);
    }

    public function test_admin_cannot_change_email_to_an_address_already_in_use(): void
    {
        $admin = User::factory()->create();
        $target = User::factory()->create();
        $existing = User::factory()->create();

        $response = $this->actingAs($admin)->put(route('admin.users.update', $target), [
            'name' => 'Target Admin',
            'email' => $existing->email,
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseHas('users', ['id' => $target->id, 'email' => $target->email]);
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        $admin = User::factory()->create();

        $selfResponse = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));
        $selfResponse->assertSessionHas('error', 'Anda tidak dapat menghapus akun sendiri.');
        $this->assertModelExists($admin);
    }

    public function test_admin_can_delete_another_admin_account(): void
    {
        $admin = User::factory()->create();
        $target = User::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $target));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }

    public function test_non_admin_user_cannot_be_edited_or_deleted_through_admin_management(): void
    {
        $admin = User::factory()->create();
        $regularUser = User::factory()->create(['role' => 'user']);

        $editResponse = $this->actingAs($admin)->get(route('admin.users.edit', $regularUser));
        $editResponse->assertNotFound();

        $deleteResponse = $this->actingAs($admin)->delete(route('admin.users.destroy', $regularUser));
        $deleteResponse->assertNotFound();
        $this->assertModelExists($regularUser);
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
