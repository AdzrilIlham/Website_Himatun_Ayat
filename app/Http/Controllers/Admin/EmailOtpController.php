<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityAuditLog;
use App\Models\User;
use App\Services\EmailOtpService;
use App\Services\TrustedDeviceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EmailOtpController extends Controller
{
    public function __construct(
        protected EmailOtpService $emailOtpService,
        protected TrustedDeviceService $trustedDeviceService
    ) {}

    /**
     * Display the email OTP verification form during new-device login.
     */
    public function show(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('login.totp_passed') || ! $request->session()->has('login.id')) {
            return redirect()->route('login');
        }

        return view('admin.auth.email-otp');
    }

    /**
     * Verify the 6-digit OTP and complete device trust and user authentication.
     */
    public function verify(Request $request): RedirectResponse
    {
        if (! $request->session()->has('login.totp_passed') || ! $request->session()->has('login.id')) {
            return redirect()->route('login');
        }

        $userId = $request->session()->get('login.id');
        $user = User::find($userId);

        if (! $user) {
            $request->session()->flush();

            return redirect()->route('login');
        }

        $rateLimitKey = 'email-otp:'.$user->id.'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            throw ValidationException::withMessages([
                'code' => ["Terlalu banyak percobaan. Silakan coba lagi dalam {$seconds} detik."],
            ]);
        }

        $request->validate([
            'code' => ['required', 'string', 'regex:/^[0-9]{6}$/'],
        ], [
            'code.required' => 'Kode OTP wajib diisi.',
            'code.regex' => 'Format kode OTP harus 6 digit angka.',
        ]);

        try {
            $this->emailOtpService->verifyAndConsume($user, 'login_new_device', $request->input('code'));
            RateLimiter::clear($rateLimitKey);
        } catch (ValidationException $e) {
            RateLimiter::hit($rateLimitKey, 300);
            throw $e;
        }

        // Register the new device as a trusted device
        $deviceCookie = $this->trustedDeviceService->registerCurrentDevice($request, $user);

        // Complete user authentication session
        $remember = (bool) $request->session()->get('login.remember', false);
        Auth::guard('web')->login($user, $remember);

        if ($remember) {
            $user->forceFill(['remember_token_issued_at' => now()])->save();
        }

        $request->session()->forget(['login.totp_passed', 'login.id', 'login.remember']);
        $request->session()->regenerate();

        SecurityAuditLog::record('auth.login_success', $user, [
            'via_email_otp' => true,
            'new_trusted_device' => true,
        ]);

        return redirect()->intended(route('admin.dashboard'))
            ->withCookie($deviceCookie)
            ->with('success', 'Selamat datang kembali, '.$user->name.'!');
    }

    /**
     * Resend a fresh OTP to the user's email address.
     */
    public function resend(Request $request): RedirectResponse
    {
        if (! $request->session()->has('login.totp_passed') || ! $request->session()->has('login.id')) {
            return redirect()->route('login');
        }

        $userId = $request->session()->get('login.id');
        $user = User::find($userId);

        if (! $user) {
            return redirect()->route('login');
        }

        $throttleKey = 'resend-otp:'.$user->id;
        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'code' => ["Mohon tunggu {$seconds} detik sebelum meminta kode baru."],
            ]);
        }

        RateLimiter::hit($throttleKey, 60);

        $this->emailOtpService->generateAndSend(
            $user,
            'login_new_device',
            'verifikasi login dari perangkat baru'
        );

        return back()->with('status', 'Kode OTP baru telah dikirimkan ke email Anda.');
    }
}
