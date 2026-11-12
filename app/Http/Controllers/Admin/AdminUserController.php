<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityAuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
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

        return view('admin.users.index', compact('admins'));
    }

    /**
     * Provision a new administrator account (server-side only, authenticated admin required).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        ], [
            'name.required' => 'Nama administrator wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
        ]);

        $newAdmin = DB::transaction(function () use ($request) {
            // Create user with unverified email and null TOTP
            $user = User::create([
                'name' => trim($request->input('name')),
                'email' => strtolower(trim($request->input('email'))),
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

        // Send password set link via core password broker
        Password::broker('users')->sendResetLink(['email' => $newAdmin->email]);

        SecurityAuditLog::record('admin.account_provisioned', auth()->user(), [
            'target_admin_id' => $newAdmin->id,
            'target_email' => $newAdmin->email,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun administrator baru berhasil dibuat. Tautan untuk menyetel password telah dikirim ke email bersangkutan.');
    }
}
