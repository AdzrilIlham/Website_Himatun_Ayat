<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Setel Password Baru — Yayasan Himmatun Ayat Bandung</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-white flex flex-col justify-center items-center py-8 px-4 select-none">

    <div class="w-full max-w-[430px] select-text">

        <!-- Card Container -->
        <div class="bg-white rounded-[28px] sm:rounded-[32px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.07),0_2px_10px_-2px_rgba(0,0,0,0.03)] border border-slate-200/90 p-7 sm:p-9 transition-all">

            <!-- Logo & Brand Header -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white border border-slate-100 shadow-[0_4px_12px_rgba(0,0,0,0.04)] p-2 mx-auto mb-3.5 transition-transform hover:scale-105">
                    <img src="{{ asset('images/logo-himmatun-ayat.png') }}" 
                         alt="Logo Himmatun Ayat" 
                         class="w-11 h-11 object-contain">
                </div>

                <div>
                    <h1 class="text-[22px] font-bold text-slate-800 tracking-tight">Setel Password Baru</h1>
                    <p class="text-[13px] text-slate-500 font-normal mt-1">
                        Buat password baru yang aman untuk akun Administrator Anda (minimal 8 karakter).
                    </p>
                </div>
            </div>

            <!-- Alert Error -->
            @if ($errors->any())
                <div class="mb-4 flex items-start gap-2.5 bg-red-50 border border-red-200 text-red-800 rounded-xl px-3.5 py-2.5 text-xs">
                    <svg class="w-4 h-4 mt-0.5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Form Reset Password -->
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5 text-left">
                        Alamat Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $request->email) }}"
                        required
                        autofocus
                        autocomplete="email"
                        class="w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 bg-slate-50 text-slate-900 focus:ring-2 focus:ring-[#2D6A4F]/20 focus:border-[#2D6A4F] transition outline-none"
                    >
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5 text-left">
                        Password Baru
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Minimal 8 karakter"
                        class="w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-[#2D6A4F]/20 focus:border-[#2D6A4F] transition outline-none"
                    >
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5 text-left">
                        Konfirmasi Password Baru
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Ulangi password baru"
                        class="w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-[#2D6A4F]/20 focus:border-[#2D6A4F] transition outline-none"
                    >
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-[#2D6A4F] hover:bg-[#245840] active:bg-[#1e4733] text-white font-semibold py-3 px-4 rounded-xl text-sm transition duration-150 flex items-center justify-center gap-2 shadow-sm shadow-[#2D6A4F]/20 cursor-pointer"
                >
                    <span>Simpan Password Baru</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center text-xs text-slate-400 space-y-0.5">
            <p>&copy; {{ date('Y') }} Yayasan Himmatun Ayat Bandung.</p>
            <p class="text-[11px] text-slate-400/90">Sistem Informasi Pengelolaan Yayasan Terpadu</p>
        </div>
    </div>
</body>
</html>
