<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Lupa Password: Yayasan Himmatun Ayat Bandung</title>

    <!-- Fonts: Plus Jakarta Sans sesuai DESIGN_SYSTEM.md -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-background text-foreground font-sans antialiased flex flex-col justify-between py-6 px-4 sm:py-10">

    <!-- Top Branding Header -->
    <header class="mx-auto w-full max-w-[440px] flex items-center justify-center gap-2.5">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-border bg-card p-1.5 shadow-2xs">
            <img src="{{ asset('images/logo-himmatun-ayat.png') }}"
                 alt="Logo Yayasan Himmatun Ayat"
                 width="32"
                 height="32"
                 fetchpriority="high"
                 class="h-7 w-7 object-contain">
        </div>
        <div>
            <p class="text-xs font-bold text-brand-900 leading-tight">Yayasan Himmatun Ayat</p>
            <p class="text-2xs text-muted-foreground-1">Keamanan Administrator</p>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="mx-auto my-6 w-full max-w-[440px]">
        <div class="card p-6 sm:p-8">

            <!-- Title & Information -->
            <div class="text-center mb-6">
                <h1 class="text-xl sm:text-2xl font-bold text-brand-900 tracking-tight">Atur Ulang Password</h1>
                <p class="text-xs sm:text-sm text-muted-foreground-1 mt-1.5 leading-relaxed">
                    Masukkan alamat email administrator Anda. Kami akan mengirimkan tautan untuk mengatur ulang password akun Anda.
                </p>
            </div>

            <!-- Prominent Success Message -->
            @if (session('status'))
                <div class="mb-5 rounded-2xl border border-brand-200 bg-brand-50 p-4 sm:p-5" role="status">
                    <div class="flex items-start gap-3.5">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-700 text-white shadow-2xs">
                            <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h2 class="text-sm font-bold text-brand-900">Tautan Berhasil Dikirim!</h2>
                            <p class="mt-1 text-xs text-brand-800 leading-relaxed">
                                {{ session('status') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Alert Error -->
            @if ($errors->any())
                <div class="mb-5 alert alert-danger" role="alert">
                    <svg aria-hidden="true" class="h-4 w-4 mt-0.5 shrink-0 text-danger" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <div class="text-xs leading-relaxed">
                        <p class="font-semibold">Gagal mengirim tautan</p>
                        <p class="mt-0.5">{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif

            <!-- Form Forgot Password -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="form-label">
                        Alamat Email Administrator
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-muted-foreground-1">
                            <svg aria-hidden="true" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
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
                            class="form-input pl-10 pr-3.5 py-2.5 text-xs sm:text-sm {{ $errors->has('email') ? 'border-danger focus:border-danger focus:ring-danger/20' : '' }}"
                        >
                    </div>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="btn btn-primary w-full min-h-11 py-3 cursor-pointer text-sm"
                >
                    <span>Kirim Tautan Reset Password</span>
                    <svg aria-hidden="true" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </form>

            <div class="mt-5 pt-4 border-t border-border text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-muted-foreground-1 hover:text-brand-700 transition cursor-pointer">
                    <svg aria-hidden="true" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    <span>Kembali ke halaman login</span>
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mx-auto w-full max-w-[440px] text-center text-xs text-muted-foreground-1 space-y-0.5">
        <p>&copy; {{ date('Y') }} Yayasan Himmatun Ayat Bandung.</p>
        <p class="text-2xs text-muted-foreground-1">Sistem Informasi Pengelolaan Yayasan Terpadu</p>
    </footer>

</body>
</html>
