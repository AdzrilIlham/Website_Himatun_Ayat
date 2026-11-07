<?php

namespace Tests\Feature;

use App\Http\Middleware\ConvertRememberToChallenge;
use App\Http\Middleware\EnforceIdleTimeout;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SessionSecurityTest extends TestCase
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
            'two_factor_secret' => Crypt::encrypt('JBSWY3DPEHPK3PXP'),
            'two_factor_confirmed_at' => now(),
            'remember_token_issued_at' => now(),
        ])->save();
    }

    public function test_session_id_is_regenerated_after_authentication(): void
    {
        $response = $this->get('/admin/login');
        $initialSessionId = session()->getId();

        $loginResponse = $this->withSession(['_token' => csrf_token()])
            ->post('/admin/login', [
                'email' => 'admin@himmatunayat.org',
                'password' => 'password123',
            ]);

        $newSessionId = session()->getId();

        // Session ID must change to protect against session fixation attacks
        $this->assertNotEquals($initialSessionId, $newSessionId);
    }

    public function test_idle_timeout_terminates_session_after_30_minutes_of_inactivity(): void
    {
        // Set last activity to 31 minutes ago
        $thirtyOneMinutesAgo = time() - (EnforceIdleTimeout::TIMEOUT_SECONDS + 60);

        $response = $this->actingAs($this->admin)
            ->withSession(['last_activity_timestamp' => $thirtyOneMinutesAgo])
            ->get(route('admin.dashboard'));

        // User must be logged out and redirected to login with error
        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
    }

    public function test_active_session_within_30_minutes_is_maintained(): void
    {
        // Activity 5 minutes ago
        $fiveMinutesAgo = time() - 300;

        $response = $this->actingAs($this->admin)
            ->withSession(['last_activity_timestamp' => $fiveMinutesAgo])
            ->get(route('admin.dashboard'));

        $this->assertAuthenticatedAs($this->admin);
        $response->assertStatus(200);
        $this->assertGreaterThanOrEqual(time() - 2, session('last_activity_timestamp'));
    }

    public function test_remember_me_within_7_days_restores_identity_but_strictly_enforces_totp(): void
    {
        $this->admin->forceFill([
            'remember_token_issued_at' => now()->subDays(3),
        ])->save();

        $guard = Auth::guard('web');
        $guard->login($this->admin, true);

        // Simulate that user was logged in via recaller cookie
        $ref = new \ReflectionProperty($guard, 'viaRemember');
        $ref->setValue($guard, true);

        $middleware = new ConvertRememberToChallenge;
        $request = Request::create('/admin/dashboard', 'GET');
        $request->setLaravelSession(session()->driver());

        $response = $middleware->handle($request, function () {
            return response('OK');
        });

        // Must redirect to TOTP challenge; full authenticated session is dropped
        $this->assertEquals(route('two-factor.login'), $response->headers->get('Location'));
        $this->assertEquals($this->admin->id, session('login.id'));
    }

    public function test_remember_token_older_than_7_days_is_completely_invalidated(): void
    {
        $this->admin->forceFill([
            'remember_token_issued_at' => now()->subDays(8), // Expired policy (> 7 days)
        ])->save();

        $guard = Auth::guard('web');
        $guard->login($this->admin, true);

        // Simulate that user was logged in via recaller cookie
        $ref = new \ReflectionProperty($guard, 'viaRemember');
        $ref->setValue($guard, true);

        $middleware = new ConvertRememberToChallenge;
        $request = Request::create('/admin/dashboard', 'GET');
        $request->setLaravelSession(session()->driver());

        $response = $middleware->handle($request, function () {
            return response('OK');
        });

        $this->assertEquals(route('login'), $response->headers->get('Location'));
        $this->assertFalse($guard->check());
    }

    public function test_admin_can_revoke_single_session_server_side(): void
    {
        $appKey = config('app.key') ?: 'key';
        $otherSessionId = 'other-client-session-12345';
        $hashedId = hash_hmac('sha256', $otherSessionId, $appKey);

        DB::table('sessions')->insert([
            'id' => $otherSessionId,
            'user_id' => $this->admin->id,
            'ip_address' => '192.168.1.100',
            'user_agent' => 'Safari on iOS',
            'payload' => 'dummy',
            'last_activity' => time(),
        ]);

        $this->assertDatabaseHas('sessions', ['id' => $otherSessionId]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.security.revoke-session'), [
                'session_hash' => $hashedId,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('sessions', ['id' => $otherSessionId]);
    }

    public function test_admin_can_logout_all_other_devices(): void
    {
        $currentSessionId = 'current-session-abc';
        $otherSession1 = 'other-session-1';
        $otherSession2 = 'other-session-2';

        DB::table('sessions')->insert([
            ['id' => $currentSessionId, 'user_id' => $this->admin->id, 'ip_address' => '127.0.0.1', 'user_agent' => 'Chrome', 'payload' => 'd', 'last_activity' => time()],
            ['id' => $otherSession1, 'user_id' => $this->admin->id, 'ip_address' => '10.0.0.1', 'user_agent' => 'Firefox', 'payload' => 'd', 'last_activity' => time()],
            ['id' => $otherSession2, 'user_id' => $this->admin->id, 'ip_address' => '10.0.0.2', 'user_agent' => 'Safari', 'payload' => 'd', 'last_activity' => time()],
        ]);

        $response = $this->actingAs($this->admin)
            ->withSession(['_id' => $currentSessionId])
            ->post(route('admin.security.logout-all-others'), [
                'password' => 'password123',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('sessions', ['id' => $otherSession1]);
        $this->assertDatabaseMissing('sessions', ['id' => $otherSession2]);
    }
}
