@extends('layouts.admin')

@section('title', 'Kelola Administrator — Yayasan Himmatun Ayat')

@section('content')
<div class="space-y-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-foreground tracking-tight">Manajemen Administrator</h1>
            <p class="text-xs sm:text-sm text-muted-foreground mt-1">
                Kelola akun pengelola sistem yayasan. Seluruh akun memiliki satu peran seragam (<span class="font-semibold text-brand-700">admin</span>) dengan kewajiban 2FA dan verifikasi email.
            </p>
        </div>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
        <div class="flex items-start gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-xl px-4 py-3 text-xs sm:text-sm">
            <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Alert Error -->
    @if ($errors->any())
        <div class="flex items-start gap-2.5 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-xs sm:text-sm">
            <svg class="w-5 h-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
            <div>
                <p class="font-semibold">Terjadi kesalahan input:</p>
                <ul class="list-disc list-inside mt-1 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Admin Baru (Server-side provisioning) -->
        <div class="bg-card border border-border rounded-2xl p-5 sm:p-6 shadow-xs h-fit">
            <h2 class="text-sm sm:text-base font-bold text-foreground mb-1">Tambah Administrator</h2>
            <p class="text-xs text-muted-foreground mb-4">
                Undang admin baru. Sistem akan mengirimkan tautan pembuatan password yang aman ke email yang didaftarkan.
            </p>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-xs font-semibold text-foreground mb-1">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso"
                           class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-border bg-background text-foreground placeholder-muted-foreground focus:ring-2 focus:ring-brand-700/20 focus:border-brand-700 outline-none transition">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-foreground mb-1">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="admin@himmatunayat.org"
                           class="w-full px-3.5 py-2 text-xs sm:text-sm rounded-xl border border-border bg-background text-foreground placeholder-muted-foreground focus:ring-2 focus:ring-brand-700/20 focus:border-brand-700 outline-none transition">
                </div>

                <div class="bg-muted p-3 rounded-xl text-[11px] text-muted-foreground leading-relaxed">
                    <p class="font-semibold text-foreground mb-0.5">Kebijakan Keamanan:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        <li>Peran otomatis ditetapkan sebagai <strong>admin</strong> di sisi server.</li>
                        <li>Admin baru wajib verifikasi email & aktivasi 2FA sebelum dashboard dapat dibuka.</li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary w-full py-2.5 text-xs sm:text-sm font-semibold cursor-pointer">
                    Kirim Undangan Admin
                </button>
            </form>
        </div>

        <!-- Tabel Daftar Administrator -->
        <div class="lg:col-span-2 bg-card border border-border rounded-2xl overflow-hidden shadow-xs">
            <div class="p-4 sm:p-5 border-b border-border flex items-center justify-between">
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-foreground">Daftar Administrator</h2>
                    <p class="text-xs text-muted-foreground">Total {{ $admins->total() }} akun terdaftar</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-muted/60 text-muted-foreground text-2xs uppercase tracking-wider font-semibold border-b border-border">
                        <tr>
                            <th class="py-3 px-4">Administrator</th>
                            <th class="py-3 px-4">Status Email</th>
                            <th class="py-3 px-4">Status 2FA (TOTP)</th>
                            <th class="py-3 px-4">Terdaftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach ($admins as $admin)
                            <tr class="hover:bg-muted/30 transition">
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-foreground">{{ $admin->name }}</div>
                                    <div class="text-xs text-muted-foreground font-mono">{{ $admin->email }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if ($admin->email_verified_at)
                                        <span class="badge badge-success text-[11px]">Terverifikasi</span>
                                    @else
                                        <span class="badge badge-warning text-[11px]">Belum Verifikasi</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @if ($admin->two_factor_confirmed_at)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-700">
                                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                            Belum Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-xs text-muted-foreground">
                                    {{ $admin->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($admins->hasPages())
                <div class="p-4 border-t border-border">
                    {{ $admins->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
