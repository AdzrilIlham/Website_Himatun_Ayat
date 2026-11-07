<?php

namespace App\Console\Commands;

use App\Models\SecurityAuditLog;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateInitialAdmin extends Command
{
    /**
     * The name and signature of the console command.
     * Note: We NEVER accept credentials as command-line arguments or options.
     *
     * @var string
     */
    protected $signature = 'admin:create-initial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inisialisasi akun Administrator pertama secara aman melalui prompt interaktif';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // 1. Check whether an administrator already exists to prevent repeated bootstrap
        $existingAdminCount = User::where('role', 'admin')->count();

        if ($existingAdminCount > 0) {
            error('Akun administrator sudah terdaftar dalam sistem. Perintah ini hanya dapat dijalankan saat belum ada akun administrator.');

            return self::FAILURE;
        }

        info('=== Inisialisasi Akun Administrator Yayasan Himmatun Ayat ===');

        // 2. Interactive prompts with input masking for credentials
        $name = text(
            label: 'Nama Lengkap Administrator',
            placeholder: 'Contoh: Ahmad Fauzi',
            required: true,
            validate: fn (string $value) => strlen(trim($value)) >= 3
                ? null
                : 'Nama minimal 3 karakter.'
        );

        $email = text(
            label: 'Alamat Email',
            placeholder: 'admin@himmatunayat.org',
            required: true,
            validate: function (string $value) {
                $v = Validator::make(['email' => strtolower(trim($value))], [
                    'email' => ['required', 'email', 'unique:users,email'],
                ], [
                    'email.email' => 'Format email tidak valid.',
                    'email.unique' => 'Alamat email ini sudah terdaftar.',
                ]);

                return $v->fails() ? $v->errors()->first('email') : null;
            }
        );

        $password = password(
            label: 'Password Administrator (minimal 8 karakter)',
            required: true,
            validate: function (string $value) {
                $v = Validator::make(['password' => $value], [
                    'password' => ['required', Password::min(8)],
                ], [
                    'password.min' => 'Password minimal harus 8 karakter.',
                ]);

                return $v->fails() ? $v->errors()->first('password') : null;
            }
        );

        $passwordConfirmation = password(
            label: 'Konfirmasi Password',
            required: true,
            validate: fn (string $value) => $value === $password
                ? null
                : 'Konfirmasi password tidak cocok dengan password yang dimasukkan.'
        );

        // 3. Atomic creation exclusively on the server
        $user = DB::transaction(function () use ($name, $email, $password) {
            // Double-check inside transaction with lock
            if (User::where('role', 'admin')->lockForUpdate()->exists()) {
                throw new \RuntimeException('Administrator sudah dibuat oleh proses lain.');
            }

            return User::create([
                'name' => trim($name),
                'email' => strtolower(trim($email)),
                'password' => Hash::make($password),
                'email_verified_at' => null, // Must verify email before accessing dashboard
                'two_factor_secret' => null,  // Must enroll TOTP
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
            ]);
        });

        // Exclusively assign role on the server (not via fillable)
        $user->forceFill(['role' => 'admin'])->save();

        SecurityAuditLog::record('admin.initial_bootstrap_created', $user, [
            'email' => $user->email,
        ]);

        info('Berhasil! Akun administrator utama telah dibuat.');
        $this->line('Catatan Keamanan:');
        $this->line('1. Anda wajib memverifikasi email saat pertama kali masuk.');
        $this->line('2. Anda wajib mengaktifkan autentikasi dua faktor (TOTP) sebelum dapat mengakses dashboard admin.');

        return self::SUCCESS;
    }
}
