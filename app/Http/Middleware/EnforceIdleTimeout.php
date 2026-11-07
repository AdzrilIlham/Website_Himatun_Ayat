<?php

namespace App\Http\Middleware;

use App\Models\SecurityAuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforceIdleTimeout
{
    public const TIMEOUT_SECONDS = 1800; // 30 minutes

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('web')->check()) {
            $lastActivity = $request->session()->get('last_activity_timestamp');

            if ($lastActivity && (time() - (int) $lastActivity > self::TIMEOUT_SECONDS)) {
                $user = Auth::guard('web')->user();

                SecurityAuditLog::record('session.idle_timeout', $user, [
                    'idle_seconds' => time() - (int) $lastActivity,
                ]);

                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Sesi Anda telah berakhir karena tidak ada aktivitas selama 30 menit. Silakan masuk kembali.']);
            }

            $request->session()->put('last_activity_timestamp', time());
        }

        return $next($request);
    }
}
