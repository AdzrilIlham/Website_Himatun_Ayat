<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorEnrolled
{
    /**
     * Allowed routes when two factor enrollment has not been completed yet.
     *
     * @var list<string>
     */
    protected array $allowedRouteNames = [
        'admin.two-factor-setup',
        'two-factor.enable',
        'two-factor.confirm',
        'two-factor.disable',
        'two-factor.qr-code',
        'two-factor.secret-key',
        'two-factor.recovery-codes',
        'two-factor.regenerate-recovery-codes',
        'password.confirm',
        'password.confirm.store',
        'password.confirmation',
        'logout',
        'verification.notice',
        'verification.verify',
        'verification.send',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && is_null($user->two_factor_confirmed_at)) {
            $currentRouteName = (string) $request->route()?->getName();

            if (! in_array($currentRouteName, $this->allowedRouteNames, true)) {
                return redirect()->route('admin.two-factor-setup')
                    ->with('warning', 'Demi keamanan, Anda wajib mengaktifkan autentikasi dua faktor (TOTP) sebelum dapat mengakses dashboard.');
            }
        }

        return $next($request);
    }
}
