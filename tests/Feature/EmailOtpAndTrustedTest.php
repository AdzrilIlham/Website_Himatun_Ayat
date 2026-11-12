<?php

namespace Tests\Feature;

use App\Models\EmailOtp;
use App\Models\TrustedDevice;
use App\Models\User;
use App\Notifications\EmailOtpNotification;
use App\Services\EmailOtpService;
use App\Services\TrustedDeviceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class EmailOtpAndTrustedTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected EmailOtpService $otpService;

    protected TrustedDeviceService $deviceService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->otpService = app(EmailOtpService::class);
        $this->deviceService = app(TrustedDeviceService::class);

        $this->admin = User::create([
            'name' => 'Admin Yayasan',
            'email' => 'admin@himmatunayat.org',
            'password' => Hash::make('password123'),
        ]);

        $this->admin->forceFill([
            'role' => 'admin',
            'email_verified_at' => now(),
            'two_factor_secret' => Crypt::encrypt('JBSWY3DPEHPK3PXP'),
            'two_factor_confirmed_at' => now(),
        ])->save();
    }

    public function test_email_otp_is_stored_hashed_never_plaintext(): void
    {
        Notification::fake();

        $otp = $this->otpService->generateAndSend($this->admin, 'login_new_device');

        $this->assertDatabaseHas('email_otps', [
            'id' => $otp->id,
            'user_id' => $this->admin->id,
            'purpose' => 'login_new_device',
        ]);

        // The database record must NOT contain any raw 6-digit number; only 64-char hex hash
        $this->assertEquals(64, strlen($otp->code_hash));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $otp->code_hash);

        // Notification must have been dispatched
        Notification::assertSentTo($this->admin, EmailOtpNotification::class);
    }

    public function test_email_otp_expires_after_5_minutes(): void
    {
        Notification::fake();

        $otp = $this->otpService->generateAndSend($this->admin, 'login_new_device');

        // Travel 6 minutes into the future
        $this->travel(6)->minutes();

        $this->expectException(ValidationException::class);
        $this->otpService->verifyAndConsume($this->admin, 'login_new_device', '123456');
    }

    public function test_issuing_new_otp_invalidates_previous_active_otp(): void
    {
        Notification::fake();

        $otp1 = $this->otpService->generateAndSend($this->admin, 'login_new_device');
        $this->assertNull($otp1->fresh()->consumed_at);

        $otp2 = $this->otpService->generateAndSend($this->admin, 'login_new_device');

        // First OTP must now be marked consumed/invalidated
        $this->assertNotNull($otp1->fresh()->consumed_at);
        $this->assertNull($otp2->fresh()->consumed_at);
    }

    public function test_email_otp_enforces_maximum_5_attempts(): void
    {
        Notification::fake();

        $this->otpService->generateAndSend($this->admin, 'login_new_device');

        for ($i = 0; $i < 4; $i++) {
            try {
                $this->otpService->verifyAndConsume($this->admin, 'login_new_device', '000000');
            } catch (ValidationException $e) {
                // Expected invalid attempt
            }
        }

        $otp = EmailOtp::where('user_id', $this->admin->id)->latest()->first();
        $this->assertEquals(4, $otp->attempts);
        $this->assertNull($otp->consumed_at);

        // 5th failed attempt must invalidate and lock the OTP
        try {
            $this->otpService->verifyAndConsume($this->admin, 'login_new_device', '000000');
        } catch (ValidationException $e) {
            // Expected
        }

        $this->assertEquals(5, $otp->fresh()->attempts);
        $this->assertNotNull($otp->fresh()->consumed_at);
    }

    public function test_successful_otp_verification_creates_trusted_device(): void
    {
        Notification::fake();

        $capturedOtp = null;
        Notification::assertNothingSent();

        $this->otpService->generateAndSend($this->admin, 'login_new_device');

        Notification::assertSentTo($this->admin, EmailOtpNotification::class, function ($notification) use (&$capturedOtp) {
            $capturedOtp = $notification->otpCode;

            return true;
        });

        $this->assertNotNull($capturedOtp);

        $response = $this->withSession([
            'login.totp_passed' => true,
            'login.id' => $this->admin->id,
        ])->post(route('admin.email-otp.verify'), [
            'code' => $capturedOtp,
        ]);

        $this->assertAuthenticatedAs($this->admin);
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertCookie(TrustedDeviceService::COOKIE_NAME);

        // Verify trusted device was stored in database
        $this->assertDatabaseHas('trusted_devices', [
            'user_id' => $this->admin->id,
            'revoked_at' => null,
        ]);
    }

    public function test_trusted_device_never_bypasses_totp_or_password(): void
    {
        // 1. Create a trusted device for this user
        $rawValidator = 'some-secret-validator-token-12345';
        $deviceId = 'device-selector-xyz';

        TrustedDevice::create([
            'user_id' => $this->admin->id,
            'device_id' => $deviceId,
            'token_hash' => hash('sha256', $rawValidator),
            'device_name' => 'Chrome on Windows',
            'expires_at' => now()->addDays(30),
        ]);

        $cookieVal = $deviceId.':'.$rawValidator;

        // 2. Attempt to login with password
        $loginResponse = $this->withCookie(TrustedDeviceService::COOKIE_NAME, $cookieVal)
            ->post('/admin/login', [
                'email' => 'admin@himmatunayat.org',
                'password' => 'password123',
            ]);

        // CRITICAL: Even on a trusted device, user is STILL redirected to TOTP challenge!
        $loginResponse->assertRedirect(route('two-factor.login'));
        $this->assertGuest();

        // 3. Now solve the TOTP challenge
        $secret = Crypt::decrypt($this->admin->two_factor_secret);
        $validCode = app(Google2FA::class)->getCurrentOtp($secret);

        $totpResponse = $this->withSession(['login.id' => $this->admin->id])
            ->withCookie(TrustedDeviceService::COOKIE_NAME, $cookieVal)
            ->post('/admin/two-factor-challenge', [
                'code' => $validCode,
            ]);

        // Since device IS trusted, Email OTP is skipped and login completes to dashboard!
        $this->assertAuthenticatedAs($this->admin);
        $totpResponse->assertRedirect(route('admin.dashboard'));
    }

    public function test_revoked_device_triggers_email_otp_again(): void
    {
        Notification::fake();

        $rawValidator = 'some-secret-validator-token-12345';
        $deviceId = 'device-selector-xyz';

        $device = TrustedDevice::create([
            'user_id' => $this->admin->id,
            'device_id' => $deviceId,
            'token_hash' => hash('sha256', $rawValidator),
            'device_name' => 'Chrome on Windows',
            'expires_at' => now()->addDays(30),
            'revoked_at' => now(), // Revoked!
        ]);

        $cookieVal = $deviceId.':'.$rawValidator;

        $secret = Crypt::decrypt($this->admin->two_factor_secret);
        $validCode = app(Google2FA::class)->getCurrentOtp($secret);

        $totpResponse = $this->withSession(['login.id' => $this->admin->id])
            ->withCookie(TrustedDeviceService::COOKIE_NAME, $cookieVal)
            ->post('/admin/two-factor-challenge', [
                'code' => $validCode,
            ]);

        // Revoked device must NOT skip Email OTP!
        $totpResponse->assertRedirect(route('admin.email-otp.show'));
        $this->assertGuest();
    }

    public function test_admin_can_revoke_trusted_device_from_dashboard(): void
    {
        $device = TrustedDevice::create([
            'user_id' => $this->admin->id,
            'device_id' => 'device-123',
            'token_hash' => hash('sha256', 'token-val'),
            'device_name' => 'Firefox on macOS',
            'expires_at' => now()->addDays(30),
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.security.revoke-device', $device));

        $response->assertRedirect();
        $this->assertNotNull($device->fresh()->revoked_at);
    }
}
