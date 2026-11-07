<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Verifikasi Email — Yayasan Himmatun Ayat Bandung</title>

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
                    <h1 class="text-[22px] font-bold text-slate-800 tracking-tight">Verifikasi Email Anda</h1>
                    <p class="text-[13px] text-slate-500 font-normal mt-1">
                        Terima kasih telah bergabung. Sebelum memulai, silakan klik tautan verifikasi yang baru saja kami kirimkan ke alamat email Anda.
                    </p>
                </div>
            </div>

            <!-- Status Message -->
            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 flex items-start gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-xl px-3.5 py-2.5 text-xs">
                    <svg class="w-4 h-4 mt-0.5 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                    </svg>
                    <span>Tautan verifikasi baru telah berhasil dikirim ke alamat email Anda.</span>
                </div>
            @endif

            <!-- Resend Verification Link -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full bg-[#2D6A4F] hover:bg-[#245840] active:bg-[#1e4733] text-white font-semibold py-3 px-4 rounded-xl text-sm transition duration-150 flex items-center justify-center gap-2 shadow-sm shadow-[#2D6A4F]/20 cursor-pointer"
                >
                    <span>Kirim Ulang Email Verifikasi</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                </button>
            </form>

            <div class="mt-4 pt-4 border-t border-slate-100 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-slate-400 hover:text-slate-600 font-medium transition cursor-pointer">
                        Keluar dari Akun
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center text-xs text-slate-400 space-y-0.5">
            <p>&copy; {{ date('Y') }} Yayasan Himmatun Ayat Bandung.</p>
            <p class="text-[11px] text-slate-400/90">Sistem Informasi Pengelolaan Yayasan Terpadu</p>
        </div>
    </div>
</body>
</html>
