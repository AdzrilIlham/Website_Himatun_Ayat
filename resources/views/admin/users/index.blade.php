@extends('layouts.admin')

@section('title', 'Kelola Administrator — Yayasan Himmatun Ayat')
@section('breadcrumb', 'Kelola Admin')

@section('content')
<div class="space-y-4 sm:space-y-5 pb-12 w-full max-w-full min-w-0">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-brand-900">Manajemen Administrator</h1>
            <p class="text-xs sm:text-sm text-muted-foreground-1 mt-0.5">
                Kelola akun pengelola sistem yayasan. Seluruh akun memiliki satu peran seragam (<span class="font-semibold text-brand-700">admin</span>) dengan kewajiban 2FA dan verifikasi email.
            </p>
        </div>
    </div>

    <!-- Alert Error -->
    @if ($errors->any())
        <div class="p-3.5 rounded-xl bg-danger-soft border border-danger/30 text-danger text-xs sm:text-sm">
            <div class="flex items-center gap-2 mb-1">
                <svg class="w-4 h-4 text-danger shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
                <p class="font-bold">Terjadi kesalahan input:</p>
            </div>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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

                <button type="submit" 
                        data-confirm="Kirimkan undangan dan daftarkan administrator baru ini?"
                        data-confirm-title="Konfirmasi Tambah Administrator"
                        data-confirm-type="success"
                        data-confirm-button="Ya, Kirim Undangan"
                        class="w-full py-2.5 px-4 rounded-xl bg-brand-700 hover:bg-brand-800 text-white text-xs sm:text-sm font-semibold transition cursor-pointer shadow-xs">
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
                <table class="w-full min-w-[760px] text-left text-xs sm:text-sm">
                    <thead class="bg-muted/60 text-muted-foreground text-2xs uppercase tracking-wider font-semibold border-b border-border">
                        <tr>
                            <th class="py-3 px-4">Administrator</th>
                            <th class="py-3 px-4">Status Email</th>
                            <th class="py-3 px-4">Status 2FA (TOTP)</th>
                            <th class="py-3 px-4">Terdaftar</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
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
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-brand-700">
                                            <span class="w-2 h-2 rounded-full bg-brand-600"></span>
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
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.edit', $admin) }}" 
                                           class="rounded-xl border border-border px-3 py-1.5 text-xs font-semibold text-muted-foreground-2 hover:bg-muted transition">Edit</a>
                                        @unless ($admin->email_verified_at)
                                            <form method="POST" action="{{ route('admin.users.resend-invitation', $admin) }}">
                                                @csrf
                                                <button type="submit" 
                                                        data-confirm="Kirim ulang email undangan untuk administrator {{ $admin->name }}?"
                                                        data-confirm-title="Kirim Ulang Undangan"
                                                        data-confirm-type="info"
                                                        data-confirm-button="Ya, Kirim Ulang"
                                                        class="rounded-xl border border-brand-200 bg-brand-50 px-3 py-1.5 text-xs font-semibold text-brand-800 hover:bg-brand-100 transition cursor-pointer">Kirim Ulang</button>
                                            </form>
                                        @endunless
                                        @if (! $admin->is(auth()->user()) && $adminCount > 1)
                                            <form method="POST" action="{{ route('admin.users.destroy', $admin) }}"
                                                  data-confirm="Apakah Anda yakin ingin menghapus akun administrator {{ $admin->name }}? Tindakan ini tidak dapat dibatalkan."
                                                  data-confirm-title="Hapus Administrator"
                                                  data-confirm-type="danger"
                                                  data-confirm-button="Ya, Hapus">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-xl border border-danger/30 px-3 py-1.5 text-xs font-semibold text-danger hover:bg-danger-soft transition cursor-pointer">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
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
