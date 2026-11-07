<?php

namespace App\Http\Controllers\Admin;

use App\Models\SecurityAuditLog;
use App\Services\EmailOtpService;
use App\Services\TrustedDeviceService;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Exceptions\HttpResponseException;
use Laravel\Fortify\Contracts\FailedTwoFactorLoginResponse;
use Laravel\Fortify\Contracts\TwoFactorChallengeViewResponse;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;
use Laravel\Fortify\Events\TwoFactorAuthenticationFailed;
use Laravel\Fortify\Events\ValidTwoFactorAuthenticationCodeProvided;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest;

class TwoFactorChallengeController extends TwoFactorAuthenticatedSessionController
{
    public function __construct(
        StatefulGuard $guard,
        protected TrustedDeviceService $trustedDeviceService,
        protected EmailOtpService $emailOtpService
    ) {
        parent::__construct($guard);
    }

    /**
     * Show the two factor authentication challenge view.
     */
    public function create(TwoFactorLoginRequest $request): TwoFactorChallengeViewResponse
    {
        if (! $request->hasChallengedUser()) {
            throw new HttpResponseException(redirect()->route('login'));
        }

        return app(TwoFactorChallengeViewResponse::class);
    }

    /**
     * Attempt to authenticate a new session using the two factor authentication code.
     */
    public function store(TwoFactorLoginRequest $request)
    {
        $user = $request->challengedUser();

        if ($code = $request->validRecoveryCode()) {
            $user->replaceRecoveryCode($code);
            SecurityAuditLog::record('mfa.recovery_code_used', $user);
        } elseif (! $request->hasValidCode()) {
            event(new TwoFactorAuthenticationFailed($user));
            SecurityAuditLog::record('mfa.failed', $user);

            return app(FailedTwoFactorLoginResponse::class)->toResponse($request);
        }

        event(new ValidTwoFactorAuthenticationCodeProvided($user));
        SecurityAuditLog::record('mfa.verified', $user);

        $remember = $request->remember();

        // Check if the current device is recognized and active as a trusted device
        if ($this->trustedDeviceService->isDeviceTrusted($request, $user)) {
            $this->guard->login($user, $remember);

            if ($remember) {
                $user->forceFill(['remember_token_issued_at' => now()])->save();
            }

            $request->session()->regenerate();
            SecurityAuditLog::record('auth.login_success', $user, ['via_trusted_device' => true]);

            return app(TwoFactorLoginResponse::class);
        }

        // New / untrusted device: Enforce Email OTP as additional verification step
        $request->session()->put('login.totp_passed', true);
        $request->session()->put('login.id', $user->id);
        $request->session()->put('login.remember', $remember);

        // Issue a single-use 6-digit Email OTP valid for 5 minutes
        $this->emailOtpService->generateAndSend(
            $user,
            'login_new_device',
            'verifikasi login dari perangkat baru'
        );

        return redirect()->route('admin.email-otp.show')
            ->with('status', 'Kode verifikasi telah dikirim ke alamat email Anda.');
    }
}
