<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityAuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;

class TwoFactorSetupController extends Controller
{
    /**
     * Show mandatory two factor enrollment screen for an administrator.
     */
    public function show(Request $request, EnableTwoFactorAuthentication $enable): View|RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        // If user already completed enrollment, redirect to dashboard
        if (! is_null($user->two_factor_confirmed_at)) {
            return redirect()->route('admin.dashboard');
        }

        // Generate secret key & initial recovery codes if not already present
        if (empty($user->two_factor_secret)) {
            $enable($user);
            $user->refresh();
        }

        $qrCodeSvg = $user->twoFactorQrCodeSvg();
        $secretKey = Crypt::decrypt($user->two_factor_secret);
        $recoveryCodes = $user->recoveryCodes();

        return view('admin.auth.two-factor-setup', compact('qrCodeSvg', 'secretKey', 'recoveryCodes'));
    }

    /**
     * Confirm two factor code and finalize enrollment.
     */
    public function confirm(Request $request, ConfirmTwoFactorAuthentication $confirm): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $request->validate([
            'code' => ['required', 'string'],
        ], [
            'code.required' => 'Kode autentikator wajib diisi.',
        ]);

        $confirm($user, $request->input('code'));

        SecurityAuditLog::record('mfa.enrollment_completed', $user);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Autentikasi Dua Faktor (TOTP) berhasil diaktifkan!');
    }
}
