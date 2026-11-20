<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityAuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Display a list of all administrators.
     */
    public function index(): View
    {
        $admins = User::where('role', 'admin')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $adminCount = User::where('role', 'admin')->count();

        return view('admin.users.index', compact('admins', 'adminCount'));
    }

    /**
     * Provision a new administrator account (server-side only, authenticated admin required).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        ], [
            'name.required' => 'Nama administrator wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
        ]);

        $newAdmin = DB::transaction(function () use ($validated) {
            // Create user with unverified email and null TOTP
            $user = User::create([
                'name' => trim($validated['name']),
                'email' => strtolower(trim($validated['email'])),
                'password' => Hash::make(Str::random(32)), // Random secure placeholder until password set
                'email_verified_at' => null,
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
            ]);

            // Server-side role assignment only
            $user->forceFill(['role' => 'admin'])->save();

            return $user;
        });

        SecurityAuditLog::record('admin.account_provisioned', auth()->user(), [
            'target_admin_id' => $newAdmin->id,
            'target_email' => $newAdmin->email,
        ]);

        $inviteSent = $this->sendInvitation($newAdmin);

        return redirect()->route('admin.users.index')
            ->with($inviteSent ? 'success' : 'warning', $inviteSent
                ? 'Akun administrator baru berhasil dibuat. Tautan untuk menyetel password telah dikirim ke email bersangkutan.'
                : 'Akun administrator dibuat, tetapi email undangan gagal dikirim. Silakan kirim ulang undangan.');
    }

    public function edit(User $admin): View
    {
        abort_unless($admin->isAdmin(), 404);

        return view('admin.users.edit', compact('admin'));
    }

    public function update(Request $request, User $admin): RedirectResponse
    {
        abort_unless($admin->isAdmin(), 404);
        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($admin->id)],
        ], [
            'name.required' => 'Nama administrator wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
        ]);

        $newEmail = strtolower(trim($validated['email']));
        $emailChanged = $newEmail !== strtolower($admin->email);
        $oldEmail = $admin->email;

        DB::transaction(function () use ($admin, $validated, $newEmail, $oldEmail, $emailChanged): void {
            $admin->forceFill([
                'name' => trim($validated['name']),
                'email' => $newEmail,
                ...($emailChanged ? [
                    'email_verified_at' => null,
                    'two_factor_secret' => null,
                    'two_factor_recovery_codes' => null,
                    'two_factor_confirmed_at' => null,
                ] : []),
            ])->save();

            if ($emailChanged) {
                $admin->trustedDevices()->delete();
                $admin->emailOtps()->delete();
                DB::table('password_reset_tokens')->where('email', $oldEmail)->delete();
            }

            SecurityAuditLog::record('admin.account_updated', auth()->user(), [
                'target_admin_id' => $admin->id,
                'target_email' => $admin->email,
                'email_changed' => $emailChanged,
            ]);
        });

        return redirect()->route('admin.users.index')->with('success', $emailChanged
            ? 'Data administrator diperbarui. Karena email berubah, verifikasi email dan aktivasi 2FA perlu dilakukan ulang.'
            : 'Data administrator berhasil diperbarui.');
    }

    public function destroy(User $admin): RedirectResponse
    {
        abort_unless($admin->isAdmin(), 404);

        $targetAdminId = $admin->id;
        $targetEmail = $admin->email;
        $deletionError = DB::transaction(function () use ($admin, $targetAdminId): ?string {
            $adminIds = User::query()
                ->where('role', 'admin')
                ->orderBy('id')
                ->lockForUpdate()
                ->pluck('id');

            if (! $adminIds->contains($targetAdminId)) {
                return 'Akun administrator tidak ditemukan.';
            }

            if ($admin->is(auth()->user())) {
                return 'Anda tidak dapat menghapus akun sendiri.';
            }

            if ($adminIds->count() <= 1) {
                return 'Administrator terakhir tidak dapat dihapus.';
            }

            DB::table('password_reset_tokens')->where('email', $admin->email)->delete();
            DB::table('sessions')->where('user_id', $admin->id)->delete();
            User::whereKey($targetAdminId)->delete();

            return null;
        });

        if ($deletionError !== null) {
            return redirect()->route('admin.users.index')->with('error', $deletionError);
        }

        SecurityAuditLog::record('admin.account_deleted', auth()->user(), [
            'target_admin_id' => $targetAdminId,
            'target_email' => $targetEmail,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun administrator berhasil dihapus.');
    }

    public function resendInvitation(User $admin): RedirectResponse
    {
        abort_unless($admin->isAdmin(), 404);

        if ($admin->email_verified_at) {
            return redirect()->route('admin.users.index')->with('error', 'Undangan tidak dapat dikirim ulang karena email administrator sudah terverifikasi.');
        }

        $throttleKey = 'admin-invitation:'.$admin->id;
        if (RateLimiter::tooManyAttempts($throttleKey, 1)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return redirect()->route('admin.users.index')->with('error', "Mohon tunggu {$seconds} detik sebelum mengirim ulang undangan.");
        }

        RateLimiter::hit($throttleKey, 60);

        $sent = $this->sendInvitation($admin);

        SecurityAuditLog::record('admin.invitation_resent', auth()->user(), [
            'target_admin_id' => $admin->id,
            'target_email' => $admin->email,
            'delivery_status' => $sent ? 'sent' : 'failed',
        ]);

        return redirect()->route('admin.users.index')->with($sent ? 'success' : 'error', $sent
            ? 'Undangan administrator berhasil dikirim ulang.'
            : 'Undangan gagal dikirim. Silakan coba lagi nanti.');
    }

    private function sendInvitation(User $admin): bool
    {
        try {
            $status = Password::broker('users')->sendResetLink(['email' => $admin->email]);
        } catch (\Throwable $exception) {
            DB::table('password_reset_tokens')->where('email', $admin->email)->delete();

            Log::warning('Admin invitation delivery failed.', [
                'admin_id' => $admin->id,
                'exception' => $exception::class,
            ]);

            return false;
        }

        return $status === Password::RESET_LINK_SENT;
    }
}
