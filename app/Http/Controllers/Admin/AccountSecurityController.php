<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityAuditLog;
use App\Models\TrustedDevice;
use App\Models\User;
use App\Services\TrustedDeviceService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AccountSecurityController extends Controller
{
    public function __construct(
        protected TrustedDeviceService $trustedDeviceService
    ) {}

    /**
     * Show account security dashboard: MFA status, active sessions, trusted devices, audit logs.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = Auth::guard('web')->user();
        $currentSessionId = $request->session()->getId();
        $appKey = config('app.key') ?: 'key';

        // Load active sessions from database session driver
        $rawSessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->get();

        $sessions = $rawSessions->map(function ($s) use ($currentSessionId, $appKey) {
            $isCurrent = $s->id === $currentSessionId;

            return (object) [
                'hashed_id' => hash_hmac('sha256', $s->id, $appKey),
                'is_current' => $isCurrent,
                'ip_address' => $s->ip_address,
                'user_agent' => $s->user_agent,
                'last_active' => Carbon::createFromTimestamp($s->last_activity),
            ];
        });

        // Load trusted devices
        $trustedDevices = $user->trustedDevices()
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->orderBy('last_used_at', 'desc')
            ->get();

        // Load recent security audit logs
        $auditLogs = SecurityAuditLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.security.index', compact('user', 'sessions', 'trustedDevices', 'auditLogs'));
    }

    /**
     * Revoke a specific active session by its hashed identifier.
     */
    public function revokeSession(Request $request): RedirectResponse
    {
        $request->validate([
            'session_hash' => ['required', 'string'],
        ]);

        /** @var User $user */
        $user = Auth::guard('web')->user();
        $currentSessionId = $request->session()->getId();
        $targetHash = $request->input('session_hash');
        $appKey = config('app.key') ?: 'key';

        $rawSessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->get();

        $deletedCount = 0;
        foreach ($rawSessions as $session) {
            if (hash_equals(hash_hmac('sha256', $session->id, $appKey), $targetHash)) {
                // If attempting to revoke current session, redirect them to regular logout
                if ($session->id === $currentSessionId) {
                    return back()->with('info', 'Untuk mengakhiri sesi yang sedang aktif ini, silakan gunakan tombol Keluar.');
                }

                DB::table('sessions')->where('id', $session->id)->delete();
                $deletedCount++;
            }
        }

        if ($deletedCount > 0) {
            // Cycle remember token so the revoked device cannot restore session via recaller cookie
            $user->forceFill(['remember_token' => Str::random(60)])->save();
            SecurityAuditLog::record('session.revoked_single', $user);

            return back()->with('success', 'Sesi perangkat berhasil dihentikan.');
        }

        return back()->withErrors(['session_hash' => 'Sesi tidak ditemukan atau sudah tidak aktif.']);
    }

    /**
     * Invalidate all other active sessions across devices.
     */
    public function logoutOtherSessions(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ], [
            'password.required' => 'Password diperlukan untuk mengonfirmasi tindakan ini.',
        ]);

        /** @var User $user */
        $user = Auth::guard('web')->user();

        if (! Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Password yang Anda masukkan salah.'],
            ]);
        }

        $currentSessionId = $request->session()->getId();

        // 1. Delete all other session database records for this user
        DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentSessionId)
            ->delete();

        // 2. Rotate remember token to prevent re-authentication from other recaller cookies
        $user->forceFill(['remember_token' => Str::random(60)])->save();

        SecurityAuditLog::record('session.logout_all_others', $user);

        return back()->with('success', 'Semua sesi lain di perangkat luar telah berhasil dikeluarkan.');
    }

    /**
     * Revoke a specific trusted device.
     */
    public function revokeDevice(Request $request, TrustedDevice $device): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::guard('web')->user();

        if ($device->user_id !== $user->id) {
            abort(403);
        }

        $this->trustedDeviceService->revokeDevice($device, $user);

        return back()->with('success', 'Perangkat terpercaya "'.$device->device_name.'" berhasil dicabut.');
    }
}
