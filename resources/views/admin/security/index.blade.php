@extends('layouts.admin')

@section('title', 'Keamanan Akun & Manajemen Sesi — Yayasan Himmatun Ayat')
@section('breadcrumb', 'Keamanan Akun')

@section('content')
<div class="space-y-4 sm:space-y-5 pb-12 w-full max-w-full min-w-0">
    <!-- Header -->
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-brand-900">Keamanan Akun & Sesi</h1>
        <p class="text-xs sm:text-sm text-muted-foreground-1 mt-0.5 font-medium">
            Pantau autentikasi dua faktor, kendalikan sesi perangkat aktif, dan periksa riwayat aktivitas keamanan akun Anda.
        </p>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="p-3.5 rounded-xl bg-brand-50 border border-brand-200 text-brand-800 text-xs sm:text-sm flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-brand-600 hover:text-brand-900 text-lg leading-none cursor-pointer">&times;</button>
        </div>
    @endif

    @if (session('info'))
        <div class="p-3.5 rounded-xl bg-info-soft border border-info/30 text-info text-xs sm:text-sm flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-info shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                <span>{{ session('info') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-info hover:opacity-80 text-lg leading-none cursor-pointer">&times;</button>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-3.5 rounded-xl bg-danger-soft border border-danger/30 text-danger text-xs sm:text-sm">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-4 h-4 text-danger shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
                <p class="font-bold">Terjadi kesalahan:</p>
            </div>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 1. Status Autentikasi Dua Faktor (TOTP) -->
    <div class="bg-card border border-border rounded-2xl p-5 sm:p-6 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-11 h-11 rounded-2xl bg-brand-50 text-brand-700 flex items-center justify-center shrink-0 border border-brand-100">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-foreground">Autentikasi Dua Faktor (TOTP)</h2>
                    <p class="text-xs text-muted-foreground mt-0.5">
                        Menambahkan lapisan keamanan kedua saat masuk menggunakan aplikasi seperti Google Authenticator.
                    </p>
                    <div class="mt-2">
                        @if ($user->two_factor_confirmed_at)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Aktif sejak {{ $user->two_factor_confirmed_at->format('d M Y, H:i') }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                Belum Dikonfirmasi
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                @if (! $user->two_factor_confirmed_at)
                    <a href="{{ route('admin.two-factor-setup') }}" class="btn btn-primary text-xs font-semibold">
                        Aktivasi Sekarang
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- 2. Manajemen Sesi Aktif -->
    <div class="bg-card border border-border rounded-2xl overflow-hidden shadow-xs">
        <div class="p-5 sm:p-6 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-base font-bold text-foreground">Sesi Perangkat Aktif</h2>
                <p class="text-xs text-muted-foreground mt-0.5">
                    Daftar browser dan perangkat yang sedang terhubung ke akun Anda. Sesi memiliki batas idle timeout 30 menit.
                </p>
            </div>

            <!-- Form Logout All Other Devices -->
            <div x-data="{ open: false }">
                <button type="button" @click="open = true" class="btn btn-secondary text-xs font-semibold text-danger hover:bg-danger-soft">
                    Keluarkan Sesi Lain
                </button>

                <!-- Modal Konfirmasi Password -->
                <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs">
                    <div class="bg-card border border-border rounded-2xl p-6 max-w-md w-full shadow-xl" @click.away="open = false">
                        <h3 class="text-base font-bold text-foreground">Konfirmasi Pengeluaran Sesi Lain</h3>
                        <p class="text-xs text-muted-foreground mt-1">
                            Masukkan password akun Anda untuk mengakhiri seluruh sesi di perangkat lain secara server-side.
                        </p>
                        <form method="POST" action="{{ route('admin.security.logout-all-others') }}" class="mt-4 space-y-4">
                            @csrf
                            <div>
                                <label for="password_confirm" class="block text-xs font-semibold text-foreground mb-1">Password Anda</label>
                                <input type="password" id="password_confirm" name="password" required placeholder="••••••••"
                                       class="w-full px-3.5 py-2 text-xs rounded-xl border border-border bg-background text-foreground outline-none focus:ring-2 focus:ring-brand-700/20 focus:border-brand-700">
                            </div>
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" @click="open = false" class="btn btn-secondary text-xs">Batal</button>
                                <button type="submit" 
                                        data-confirm="Keluarkan semua sesi login administrator lainnya dari seluruh perangkat?"
                                        data-confirm-title="Konfirmasi Logout Sesi Lainnya"
                                        data-confirm-type="danger"
                                        data-confirm-button="Ya, Logout Semua"
                                        class="btn btn-danger text-xs">Konfirmasi & Logout Lainnya</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="divide-y divide-border">
            @forelse ($sessions as $s)
                <div class="p-4 sm:p-5 flex items-center justify-between gap-4 hover:bg-muted/30 transition">
                    <div class="flex items-start gap-3.5 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-muted text-muted-foreground flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0H3" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="text-xs font-bold text-foreground truncate max-w-xs sm:max-w-md">
                                    {{ $s->user_agent ?: 'Unknown Browser' }}
                                </p>
                                @if ($s->is_current)
                                    <span class="badge badge-success text-[10px] py-0.5">Sesi Ini</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-muted-foreground mt-0.5">
                                IP: <span class="font-mono">{{ $s->ip_address }}</span> &bull; Terakhir aktif: {{ $s->last_active->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    @if (! $s->is_current)
                        <form method="POST" action="{{ route('admin.security.revoke-session') }}">
                            @csrf
                            <input type="hidden" name="session_hash" value="{{ $s->hashed_id }}">
                            <button type="submit" 
                                    data-confirm="Cabut sesi login ini? Pengguna pada sesi tersebut akan langsung dipaksa keluar."
                                    data-confirm-title="Cabut Sesi"
                                    data-confirm-type="danger"
                                    data-confirm-button="Ya, Cabut Sesi"
                                    class="btn btn-secondary text-xs text-danger hover:bg-danger-soft py-1 px-2.5">
                                Cabut Sesi
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="p-6 text-center text-xs text-muted-foreground">
                    Tidak ada catatan sesi database yang ditemukan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- 3. Perangkat Terpercaya (Trusted Devices) -->
    <div class="bg-card border border-border rounded-2xl overflow-hidden shadow-xs">
        <div class="p-5 sm:p-6 border-b border-border">
            <h2 class="text-base font-bold text-foreground">Perangkat Terpercaya</h2>
            <p class="text-xs text-muted-foreground mt-0.5">
                Perangkat ini telah melalui verifikasi TOTP + Email OTP. Perangkat terpercaya tidak memerlukan Email OTP saat login, namun <strong>selalu wajib</strong> memasukkan password dan kode TOTP.
            </p>
        </div>

        <div class="divide-y divide-border">
            @forelse ($trustedDevices as $device)
                <div class="p-4 sm:p-5 flex items-center justify-between gap-4 hover:bg-muted/30 transition">
                    <div class="flex items-start gap-3.5 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center shrink-0 border border-brand-100">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-foreground">{{ $device->device_name }}</p>
                            <p class="text-[11px] text-muted-foreground mt-0.5">
                                IP Terakhir: <span class="font-mono">{{ $device->ip_address }}</span> &bull; 
                                Terdaftar: {{ $device->created_at->format('d M Y') }} &bull;
                                Berlaku s/d: {{ $device->expires_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.security.revoke-device', $device) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                data-confirm="Cabut akses untuk perangkat terpercaya ini? Sesi dari perangkat tersebut akan berakhir."
                                data-confirm-title="Cabut Akses Perangkat"
                                data-confirm-type="danger"
                                data-confirm-button="Ya, Cabut Akses"
                                class="btn btn-secondary text-xs text-danger hover:bg-danger-soft py-1 px-2.5">
                            Cabut Akses
                        </button>
                    </form>
                </div>
            @empty
                <div class="p-6 text-center text-xs text-muted-foreground">
                    Belum ada perangkat terpercaya yang terdaftar. Saat Anda login dari perangkat baru dan memverifikasi Email OTP, perangkat akan otomatis terdaftar di sini.
                </div>
            @endforelse
        </div>
    </div>

    <!-- 4. Riwayat Log Keamanan (Audit Log) -->
    <div class="bg-card border border-border rounded-2xl overflow-hidden shadow-xs">
        <div class="p-5 sm:p-6 border-b border-border">
            <h2 class="text-base font-bold text-foreground">Riwayat Log Keamanan Akun</h2>
            <p class="text-xs text-muted-foreground mt-0.5">
                Pencatatan peristiwa autentikasi akun Anda untuk audit keamanan.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-muted/60 text-muted-foreground text-2xs uppercase tracking-wider font-semibold border-b border-border">
                    <tr>
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Aktivitas Keamanan</th>
                        <th class="py-3 px-4">Alamat IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($auditLogs as $log)
                        <tr class="hover:bg-muted/30 transition">
                            <td class="py-2.5 px-4 font-mono text-muted-foreground whitespace-nowrap">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="py-2.5 px-4 font-medium text-foreground">
                                <span class="inline-block px-2 py-0.5 rounded-md bg-muted text-[11px] font-mono">
                                    {{ $log->event }}
                                </span>
                            </td>
                            <td class="py-2.5 px-4 font-mono text-muted-foreground">
                                {{ $log->ip_address ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-6 text-center text-muted-foreground">
                                Belum ada riwayat aktivitas keamanan tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
