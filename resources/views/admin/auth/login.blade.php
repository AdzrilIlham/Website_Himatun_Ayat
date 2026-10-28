<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login Admin — Yayasan Himmatun Ayat Bandung</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-white flex flex-col justify-center items-center py-8 px-4 select-none">

    <!-- ─── Main Content Container ───────────────────────────────────────────── -->
    <div class="w-full max-w-[430px] select-text">

        <!-- Card Login -->
        <div class="bg-white rounded-[28px] sm:rounded-[32px] shadow-[0_10px_40px_-10px_rgba(0,0,0,0.07),0_2px_10px_-2px_rgba(0,0,0,0.03)] border border-slate-200/90 p-7 sm:p-9 transition-all">

            <!-- Logo & Brand Header -->
            <div class="text-center mb-6">
                <!-- White Square Logo Box -->
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white border border-slate-100 shadow-[0_4px_12px_rgba(0,0,0,0.04)] p-2 mx-auto mb-3.5 transition-transform hover:scale-105">
                    <img src="{{ asset('images/logo-himmatun-ayat.png') }}" 
                         alt="Logo Himmatun Ayat" 
                         class="w-11 h-11 object-contain">
                </div>

                <div>
                    <h1 class="text-[22px] font-bold text-slate-800 tracking-tight">Yayasan Himmatun Ayat</h1>
                    <p class="text-[13px] text-slate-500 font-normal mt-1">Masuk untuk mengakses dashboard manajemen</p>
                </div>
            </div>

            <!-- Alert: Session Success -->
            @if (session('success'))
                <div class="mb-4 flex items-start gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-xl px-3.5 py-2.5 text-xs">
                    <svg class="w-4 h-4 mt-0.5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Alert: Error Login -->
            @if ($errors->any())
                <div class="mb-4 flex items-start gap-2.5 bg-red-50 border border-red-200 text-red-800 rounded-xl px-3.5 py-2.5 text-xs">
                    <svg class="w-4 h-4 mt-0.5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <span>{{ $errors->first('email') }}</span>
                </div>
            @endif

            <!-- Form Login -->
            <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5 text-left">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="admin@himmatunayat.org"
                            class="w-full pl-10 pr-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-[#2D6A4F]/20 focus:border-[#2D6A4F] transition outline-none"
                        >
                    </div>
                </div>

                <!-- Password Input -->
                <div class="mt-4">
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5 text-left">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full pl-10 pr-11 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-[#2D6A4F]/20 focus:border-[#2D6A4F] transition outline-none"
                        >
                        <!-- Toggle show/hide password -->
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-3 flex items-center text-slate-400 hover:text-slate-600 transition cursor-pointer"
                            tabindex="-1"
                            title="Tampilkan / Sembunyikan Password"
                        >
                            <svg id="icon-eye" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg id="icon-eye-slash" class="w-4 h-4 hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center gap-2 pt-1 pb-1">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="w-4 h-4 rounded border-slate-300 text-[#2D6A4F] focus:ring-[#2D6A4F] cursor-pointer"
                    >
                    <label for="remember" class="text-xs text-slate-600 font-normal select-none cursor-pointer">
                        Ingat saya di perangkat ini
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-[#2D6A4F] hover:bg-[#245840] active:bg-[#1e4733] text-white font-semibold py-3 px-4 rounded-xl text-sm transition duration-150 flex items-center justify-center gap-2 shadow-sm shadow-[#2D6A4F]/20 cursor-pointer"
                >
                    <span>Masuk ke Dashboard</span>
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

    <!-- ─── Interactive Script ────────────────────────────────────────────────── -->
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const iconEye = document.getElementById('icon-eye');
            const iconSlash = document.getElementById('icon-eye-slash');

            if (input.type === 'password') {
                input.type = 'text';
                iconEye.classList.add('hidden');
                iconSlash.classList.remove('hidden');
            } else {
                input.type = 'password';
                iconEye.classList.remove('hidden');
                iconSlash.classList.add('hidden');
            }
        }
    </script>
</body>
</html>