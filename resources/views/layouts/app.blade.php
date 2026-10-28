<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Yayasan Mitra - Portal Informasi & Donasi' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-background font-sans antialiased text-foreground">
    <!-- Navbar Publik -->
    <header class="sticky top-0 z-40 w-full border-b border-border bg-card/95 backdrop-blur">
        <div class="max-w-7xl mx-auto flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="/" class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-lg bg-brand-700 flex items-center justify-center text-white font-bold">
                    YM
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-base leading-tight text-foreground">Yayasan Mitra</span>
                    <span class="text-xs text-muted-foreground-1">Cibiru Wetan, Bandung</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-muted-foreground-2">
                <a href="/" class="hover:text-brand-700 transition">Beranda</a>
                <a href="/profile" class="hover:text-brand-700 transition">Profil & Legalitas</a>
                <a href="/kampanye" class="hover:text-brand-700 transition">Program Donasi</a>
                <a href="/berita" class="hover:text-brand-700 transition">Berita & Kegiatan</a>
            </nav>

            <div class="flex items-center gap-2 sm:gap-3">
                <a href="/kampanye" class="btn btn-primary btn-sm px-3 sm:px-4 py-2 shadow-sm">
                    Donasi Sekarang
                </a>
                <button type="button"
                        data-hs-overlay="#public-nav"
                        aria-haspopup="dialog"
                        aria-controls="public-nav"
                        class="md:hidden p-2 -mr-2 rounded-xl text-muted-foreground-2 hover:text-foreground hover:bg-muted transition"
                        aria-label="Buka menu navigasi">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Menu Navigasi Mobile (Preline Offcanvas) -->
    <div id="public-nav"
         class="hs-overlay [--auto-close:md] hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform fixed top-0 inset-s-0 bottom-0 z-80 w-72 max-w-[85vw] bg-sidebar border-e border-sidebar-line shadow-xl md:hidden"
         role="dialog"
         tabindex="-1"
         aria-labelledby="public-nav-label">
        <div class="flex h-full flex-col">
            <div class="flex items-center justify-between border-b border-border-line-1 px-5 py-4">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-700 text-sm font-bold text-white">YM</div>
                    <div>
                        <p id="public-nav-label" class="text-sm font-bold text-foreground">Yayasan Mitra</p>
                        <p class="text-2xs text-muted-foreground-1">Cibiru Wetan, Bandung</p>
                    </div>
                </div>
                <button type="button"
                        data-hs-overlay="#public-nav"
                        class="flex size-8 items-center justify-center rounded-full border border-border bg-card text-muted-foreground-2 hover:bg-muted"
                        aria-label="Tutup menu">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto p-4 text-sm font-medium" aria-label="Navigasi utama mobile">
                <a href="/" class="block rounded-xl px-3.5 py-2.5 text-muted-foreground-2 hover:bg-muted hover:text-foreground">Beranda</a>
                <a href="/profile" class="block rounded-xl px-3.5 py-2.5 text-muted-foreground-2 hover:bg-muted hover:text-foreground">Profil &amp; Legalitas</a>
                <a href="/kampanye" class="block rounded-xl px-3.5 py-2.5 text-muted-foreground-2 hover:bg-muted hover:text-foreground">Program Donasi</a>
                <a href="/berita" class="block rounded-xl px-3.5 py-2.5 text-muted-foreground-2 hover:bg-muted hover:text-foreground">Berita &amp; Kegiatan</a>
            </nav>

            <div class="border-t border-border-line-1 p-4">
                <a href="/kampanye" class="btn btn-primary w-full">Donasi Sekarang</a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer Publik -->
    <footer class="mt-20 border-t border-border bg-card py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-muted-foreground-1">
            <p class="font-semibold text-muted-foreground-2">Yayasan Mitra</p>
            <p class="mt-1">Jl. Cibiru Indah 7 RT/RW 04/14 Des. Cibiru Wetan Kec. Cileunyi Kab. Bandung</p>
            <p class="mt-4 text-xs text-muted-foreground">&copy; {{ date('Y') }} Yayasan Mitra. Seluruh hak cipta dilindungi.</p>
        </div>
    </footer>
</body>
</html>
