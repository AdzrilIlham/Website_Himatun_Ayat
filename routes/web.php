<?php

use App\Http\Controllers\Admin\AccountSecurityController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AnakAsuhController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailOtpController;
use App\Http\Controllers\Admin\KampanyeController;
use App\Http\Controllers\Admin\KelolaDonasiController;
use App\Http\Controllers\Admin\LaporanEksporController;
use App\Http\Controllers\Admin\PengajuanKegiatanController;
use App\Http\Controllers\Admin\PengeluaranOperasionalController;
use App\Http\Controllers\Admin\PenyaluranDanaController;
use App\Http\Controllers\Admin\TwoFactorSetupController;
use App\Http\Controllers\BeritaKegiatanController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\User\LandingPageController;
use App\Http\Controllers\User\TentangKamiController;
use App\Livewire\User\KampanyeDonasi;
use Illuminate\Support\Facades\Route;

// ─── Halaman Publik Website Yayasan ──────────────────────────────────────────
Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/kampanye', KampanyeDonasi::class)->name('kampanye.index');
Route::get('/donasi', KampanyeDonasi::class)->name('donasi.index');
Route::post('/donasi/{donasi}/bukti-transfer/upload', [ImageUploadController::class, 'donation'])
    ->middleware('throttle:10,1')->name('donasi.bukti-transfer.upload');
Route::get('/tentang-kami', [TentangKamiController::class, 'index'])->name('tentang-kami');
Route::get('/profile', [TentangKamiController::class, 'index'])->name('profile');
Route::get('/berita-kegiatan', [BeritaKegiatanController::class, 'index'])->name('berita-kegiatan.index');
Route::get('/berita-kegiatan/{beritaKegiatan:slug}', [BeritaKegiatanController::class, 'show'])->name('berita-kegiatan.show');
Route::post('/pengajuan-kegiatan/ajukan', [BeritaKegiatanController::class, 'storePengajuan'])->name('pengajuan-kegiatan.store');

// Halaman preview design system (hanya lokal)
if (app()->environment('local')) {
    Route::view('/design-system', 'design-system')->name('design-system');
}

// Redirect URL /login umum ke /admin/login (Fortify) dan sediakan alias kompatibilitas
Route::redirect('/login', '/admin/login');
Route::redirect('/admin/auth/login', '/admin/login')->name('admin.login');

// ─── Alur Pre-Auth & Setup Keamanan Akun ──────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    // Verifikasi Email OTP saat login dari perangkat baru / tidak dikenal
    Route::get('/email-otp', [EmailOtpController::class, 'show'])->name('email-otp.show');
    Route::post('/email-otp', [EmailOtpController::class, 'verify'])->name('email-otp.verify');
    Route::post('/email-otp/resend', [EmailOtpController::class, 'resend'])->name('email-otp.resend');

    // Setup Wajib Autentikasi Dua Faktor (hanya untuk user yang terautentikasi dan belum enroll)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/two-factor-setup', [TwoFactorSetupController::class, 'show'])->name('two-factor-setup');
        Route::post('/two-factor-setup', [TwoFactorSetupController::class, 'confirm'])->name('two-factor-setup.confirm');
    });
});

// ─── Area Administrasi Terproteksi Penuh ─────────────────────────────────────
// Seluruh rute dilindungi verifikasi berlapis: auth, role admin, verified email, MFA enrollment, 30m idle timeout, dan 7-day remember check.
Route::prefix('admin')->name('admin.')->middleware([
    'auth',
    'admin',
    'verified',
    'mfa.enrolled',
    'idle.timeout',
    'remember.challenge',
])->group(function () {
    Route::post('/image-uploads', [ImageUploadController::class, 'admin'])->middleware('throttle:30,1')->name('image-uploads.store');
    Route::get('/donasi/{donasi}/bukti-transfer', [ImageUploadController::class, 'receipt'])->name('donasi.bukti-transfer.show');

    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/donasi/{donasi}/verifikasi', [DashboardController::class, 'verifikasi'])->name('donasi.verifikasi');
    Route::post('/donasi/{donasi}/tolak', [DashboardController::class, 'tolak'])->name('donasi.tolak');
    Route::get('/dashboard/export/excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export.excel');
    Route::get('/dashboard/export/pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.export.pdf');

    // Data Anak Asuh
    Route::get('/anak-asuh/export/excel', [AnakAsuhController::class, 'exportExcel'])->name('anak-asuh.export.excel');
    Route::get('/anak-asuh/export/pdf', [AnakAsuhController::class, 'exportPdf'])->name('anak-asuh.export.pdf');
    Route::resource('/anak-asuh', AnakAsuhController::class)->except(['create', 'show', 'edit']);

    // Kelola Donasi
    Route::get('/kelola-donasi', [KelolaDonasiController::class, 'index'])->name('kelola-donasi.index');
    Route::post('/kelola-donasi', [KelolaDonasiController::class, 'store'])->name('kelola-donasi.store');
    Route::put('/kelola-donasi/{donasi}', [KelolaDonasiController::class, 'update'])->name('kelola-donasi.update');
    Route::post('/kelola-donasi/{donasi}/terima', [KelolaDonasiController::class, 'terima'])->name('kelola-donasi.terima');
    Route::delete('/kelola-donasi/{donasi}', [KelolaDonasiController::class, 'destroy'])->name('kelola-donasi.destroy');
    Route::get('/kelola-donasi/export', [KelolaDonasiController::class, 'export'])->name('kelola-donasi.export');

    // Penyaluran Dana (CRUD)
    Route::post('/penyaluran-dana', [PenyaluranDanaController::class, 'store'])->name('penyaluran-dana.store');
    Route::put('/penyaluran-dana/{penyaluranDana}', [PenyaluranDanaController::class, 'update'])->name('penyaluran-dana.update');
    Route::delete('/penyaluran-dana/{penyaluranDana}', [PenyaluranDanaController::class, 'destroy'])->name('penyaluran-dana.destroy');

    // Pengeluaran Operasional (CRUD)
    Route::post('/pengeluaran-operasional', [PengeluaranOperasionalController::class, 'store'])->name('pengeluaran-operasional.store');
    Route::put('/pengeluaran-operasional/{pengeluaranOperasional}', [PengeluaranOperasionalController::class, 'update'])->name('pengeluaran-operasional.update');
    Route::delete('/pengeluaran-operasional/{pengeluaranOperasional}', [PengeluaranOperasionalController::class, 'destroy'])->name('pengeluaran-operasional.destroy');

    // Kelola Kampanye
    Route::resource('/kelola-kampanye', KampanyeController::class)->except(['show'])->parameters([
        'kelola-kampanye' => 'kampanye',
    ]);

    // CMS Berita
    Route::get('/cms-berita', [BeritaController::class, 'index'])->name('berita.index');
    Route::get('/cms-berita/create', [BeritaController::class, 'create'])->name('berita.create');
    Route::post('/cms-berita', [BeritaController::class, 'store'])->name('berita.store');
    Route::get('/cms-berita/{berita}/edit', [BeritaController::class, 'edit'])->name('berita.edit');
    Route::put('/cms-berita/{berita}', [BeritaController::class, 'update'])->name('berita.update');
    Route::delete('/cms-berita/{berita}', [BeritaController::class, 'destroy'])->name('berita.destroy');

    // Pengajuan Kegiatan
    Route::get('/pengajuan-kegiatan', [PengajuanKegiatanController::class, 'index'])->name('pengajuan-kegiatan.index');
    Route::post('/pengajuan-kegiatan/{pengajuan}/setujui', [PengajuanKegiatanController::class, 'setujui'])->name('pengajuan-kegiatan.setujui');
    Route::post('/pengajuan-kegiatan/{pengajuan}/tolak', [PengajuanKegiatanController::class, 'tolak'])->name('pengajuan-kegiatan.tolak');
    Route::put('/pengajuan-kegiatan/{pengajuan}', [PengajuanKegiatanController::class, 'update'])->name('pengajuan-kegiatan.update');
    Route::delete('/pengajuan-kegiatan/{pengajuan}', [PengajuanKegiatanController::class, 'destroy'])->name('pengajuan-kegiatan.destroy');

    // Laporan & Ekspor
    Route::get('/laporan-ekspor', [LaporanEksporController::class, 'index'])->name('laporan-ekspor.index');
    Route::get('/laporan-ekspor/export/excel', [LaporanEksporController::class, 'exportExcel'])->name('laporan-ekspor.export.excel');
    Route::get('/laporan-ekspor/export/pdf', [LaporanEksporController::class, 'exportPdf'])->name('laporan-ekspor.export.pdf');

    // Manajemen Administrator (Server-side provisioning)
    Route::get('/kelola-admin', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/kelola-admin', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/kelola-admin/{admin}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/kelola-admin/{admin}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/kelola-admin/{admin}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::post('/kelola-admin/{admin}/resend-invitation', [AdminUserController::class, 'resendInvitation'])->middleware('throttle:1,1')->name('users.resend-invitation');

    // Keamanan Akun, Manajemen Sesi, dan Perangkat Terpercaya
    Route::get('/keamanan-akun', [AccountSecurityController::class, 'index'])->name('security.index');
    Route::post('/keamanan-akun/revoke-session', [AccountSecurityController::class, 'revokeSession'])->name('security.revoke-session');
    Route::post('/keamanan-akun/logout-all-others', [AccountSecurityController::class, 'logoutOtherSessions'])->name('security.logout-all-others');
    Route::delete('/keamanan-akun/trusted-devices/{device}', [AccountSecurityController::class, 'revokeDevice'])->name('security.revoke-device');
});
