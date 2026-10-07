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
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-50 font-sans antialiased text-slate-800">
    <!-- Navbar Publik -->
    <header class="sticky top-0 z-40 w-full border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="max-w-7xl mx-auto flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="/" class="flex items-center gap-3">
                <div class="h-9 w-9 rounded-lg bg-emerald-700 flex items-center justify-center text-white font-bold">
                    YM
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-base leading-tight text-slate-900">Yayasan Mitra</span>
                    <span class="text-xs text-slate-500">Cibiru Wetan, Bandung</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600">
                <a href="/" class="hover:text-emerald-700 transition">Beranda</a>
                <a href="/profile" class="hover:text-emerald-700 transition">Profil & Legalitas</a>
                <a href="/kampanye" class="hover:text-emerald-700 transition">Program Donasi</a>
                <a href="/berita" class="hover:text-emerald-700 transition">Berita & Kegiatan</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="/kampanye" class="inline-flex items-center justify-center rounded-lg bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-800 transition">
                    Donasi Sekarang
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer Publik -->
    <footer class="mt-20 border-t border-slate-200 bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-slate-500">
            <p class="font-semibold text-slate-700">Yayasan Mitra</p>
            <p class="mt-1">Jl. Cibiru Indah 7 RT/RW 04/14 Des. Cibiru Wetan Kec. Cileunyi Kab. Bandung</p>
            <p class="mt-4 text-xs text-slate-400">&copy; {{ date('Y') }} Yayasan Mitra. Seluruh hak cipta dilindungi.</p>
        </div>
    </footer>

    @livewireScripts
    @fluxScripts
</body>
</html>
