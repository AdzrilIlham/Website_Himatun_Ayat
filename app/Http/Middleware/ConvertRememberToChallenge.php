<?php

namespace App\Http\Middleware;

use App\Models\SecurityAuditLog;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ConvertRememberToChallenge
{
    public const MAX_REMEMBER_DAYS = 7;

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $guard = Auth::guard('web');

        if ($guard->check() && $guard->viaRemember()) {
            /** @var User $user */
            $user = $guard->user();

            // Validate that the remember token was issued within the 7-day policy window
            $issuedAt = $user->remember_token_issued_at;
            if ($issuedAt && $issuedAt->diffInDays(now()) > self::MAX_REMEMBER_DAYS) {
                SecurityAuditLog::record('remember_token.expired', $user);
                $guard->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Masa berlaku "Ingat Saya" (7 hari) telah habis. Silakan masuk kembali.']);
            }

            // If user has 2FA confirmed, Remember Me must NEVER bypass TOTP.
            if ($user->two_factor_confirmed_at) {
                SecurityAuditLog::record('remember_token.challenge_required', $user);

                $userId = $user->id;
                $guard->logout();

                $request->session()->put('login.id', $userId);
                $request->session()->put('login.remember', true);

                return redirect()->route('two-factor.login')
                    ->with('info', 'Sesi Anda telah berakhir. Masukkan kode 2FA untuk melanjutkan.');
            }
        }

        return $next($request);
    }
}
