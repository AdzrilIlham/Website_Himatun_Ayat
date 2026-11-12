<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Yayasan Himatun Ayat Bandung - Portal Donasi & Program Kebaikan' }}</title>

    <!-- Fonts: Plus Jakarta Sans via Bunny CDN sesuai Design System -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-background text-foreground font-sans antialiased selection:bg-brand-200 selection:text-brand-950">
    <!-- Navbar Publik Sesuai Mockup & Design System -->
    <header class="sticky top-0 z-40 w-full bg-white/95 backdrop-blur-md border-b border-border transition-all">
        <div class="max-w-7xl mx-auto flex h-20 items-center justify-between px-4 sm:px-6 lg:px-8">
            <!-- Brand / Logo dengan Aset Resmi -->
            <a href="{{ route('kampanye.index') }}" class="flex items-center gap-2.5 group">
                @if(file_exists(public_path('images/logo-himmatun-ayat.png')))
                    <img src="{{ asset('images/logo-himmatun-ayat.png') }}" alt="Logo Himmatun Ayat" class="w-8 h-8 object-contain">
                @endif
                <span class="font-bold text-xl sm:text-2xl tracking-tight text-foreground group-hover:text-brand-700 transition">
                    Yayasan Himatun
                </span>
            </a>

            <!-- Menu Navigasi (Preline Dropdown Sesuai Design System) -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                <a href="{{ route('home') }}" class="hover:text-brand-700 transition">Beranda</a>
                <a href="#tentang-kami" class="hover:text-brand-700 transition">Tentang Kami</a>
                
                <div class="hs-dropdown relative inline-flex [--trigger:hover]">
                    <button id="hs-nav-layanan" type="button" class="hs-dropdown-toggle inline-flex items-center gap-1.5 text-foreground font-semibold border-b-2 border-brand-700 pb-1 pt-1 transition focus:outline-hidden" aria-haspopup="menu" aria-expanded="false" aria-label="Layanan">
                        <span>Layanan</span>
                        <svg class="hs-dropdown-open:rotate-180 w-4 h-4 text-slate-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <!-- Dropdown Items -->
                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-dropdown p-1.5 shadow-xl rounded-2xl border border-dropdown-line mt-2 z-50" role="menu" aria-orientation="vertical" aria-labelledby="hs-nav-layanan">
                        <a href="{{ route('kampanye.index') }}" class="flex items-center gap-x-2 px-3 py-2 text-xs font-semibold rounded-xl text-brand-800 bg-brand-50 hover:bg-brand-100 transition">
                            Program Donasi
                        </a>
                        <a href="#berita" class="flex items-center gap-x-2 px-3 py-2 text-xs font-medium rounded-xl text-slate-700 hover:bg-slate-50 transition">
                            Berita & Kegiatan
                        </a>
                        <a href="#laporan" class="flex items-center gap-x-2 px-3 py-2 text-xs font-medium rounded-xl text-slate-700 hover:bg-slate-50 transition">
                            Laporan Penyaluran
                        </a>
                    </div>
                </div>
            </nav>

            <!-- CTA Button Donasi Menggunakan .btn .btn-primary -->
            <div class="flex items-center gap-3">
                <a href="#formulir-donatur" class="btn btn-primary rounded-full px-6 py-2.5 shadow-sm hover:shadow-md">
                    Donasi
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Footer Publik Sesuai Mockup & Design System -->
    <footer id="tentang-kami" class="mt-24 bg-card border-t border-border pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 lg:gap-14 pb-12">
                <!-- Kolom 1: Profil Yayasan -->
                <div class="md:col-span-5 lg:col-span-5 space-y-4">
                    <div class="flex items-center gap-2.5">
                        @if(file_exists(public_path('images/logo-himmatun-ayat.png')))
                            <img src="{{ asset('images/logo-himmatun-ayat.png') }}" alt="Logo Himmatun Ayat" class="w-7 h-7 object-contain">
                        @endif
                        <h3 class="text-base font-bold text-foreground tracking-tight">
                            Yayasan Himatun Ayat Bandung
                        </h3>
                    </div>
                    <p class="text-xs sm:text-sm text-muted-foreground-1 leading-relaxed pr-4">
                        Lembaga pengasuhan, pembinaan akhlak, serta pemenuhan pendidikan dan kesejahteraan bagi para santri yatim, piatu, dan dhuafa di wilayah Cibiru Wetan, Bandung yang berorientasi pada transparansi dan keberkahan.
                    </p>
                </div>

                <!-- Kolom 2: Lokasi & Kontak -->
                <div class="md:col-span-4 lg:col-span-4 space-y-4">
                    <h3 class="text-base font-bold text-foreground tracking-tight">
                        Lokasi & Kontak
                    </h3>
                    <div class="space-y-3 text-xs sm:text-sm text-slate-600">
                        <div class="flex items-start gap-2.5">
                            <svg class="w-5 h-5 text-brand-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="leading-relaxed">
                                Jalan Cibiru Indah VII RT/RW 04/14, Cibiru Wetan, Kec. Cileunyi, Kabupaten Bandung, Jawa Barat 40625
                            </span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-brand-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <a href="https://wa.me/6281268765420" target="_blank" class="hover:text-brand-700 transition font-medium">
                                WhatsApp: +6281268765420
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kolom 3: Cuplikan Maps -->
                <div class="md:col-span-3 lg:col-span-3">
                    <a href="https://maps.google.com/?q=Jalan+Cibiru+Indah+VII+RT+04+RW+14+Cibiru+Wetan+Cileunyi+Bandung" target="_blank" class="block group rounded-2xl overflow-hidden border border-border shadow-xs hover:shadow-md transition">
                        <div class="relative bg-slate-100 aspect-4/3 w-full flex items-center justify-center overflow-hidden">
                            <!-- Visual Stylized Map Illustration -->
                            <svg viewBox="0 0 300 200" class="w-full h-full object-cover">
                                <rect width="300" height="200" fill="#f1f5f9"/>
                                <path d="M 0 40 Q 150 70 300 30" stroke="#ffffff" stroke-width="12" fill="none"/>
                                <path d="M 0 140 Q 120 110 300 160" stroke="#ffffff" stroke-width="14" fill="none"/>
                                <path d="M 70 0 L 110 200" stroke="#ffffff" stroke-width="10" fill="none"/>
                                <path d="M 220 0 L 190 200" stroke="#ffffff" stroke-width="12" fill="none"/>
                                <path d="M 140 30 L 170 170" stroke="#e2e8f0" stroke-width="6" fill="none"/>
                                <!-- Pin Location -->
                                <circle cx="170" cy="95" r="14" fill="#38bdf8" opacity="0.3"/>
                                <circle cx="170" cy="95" r="7" fill="#0284c7"/>
                                <path d="M 170 102 L 170 112" stroke="#0284c7" stroke-width="2.5" stroke-linecap="round"/>
                            </svg>
                            <div class="absolute inset-0 bg-slate-900/5 group-hover:bg-slate-900/0 transition"></div>
                            <span class="absolute bottom-2 right-2 px-2.5 py-1 rounded-md bg-white/90 backdrop-blur-xs text-2xs font-semibold text-slate-700 shadow-xs">
                                Buka di Google Maps ↗
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Copyright Line -->
            <div class="pt-8 border-t border-border flex flex-col sm:flex-row items-center justify-between text-xs text-muted-foreground gap-2">
                <p class="font-medium">
                    &copy; {{ date('Y') }} Yayasan Himatun Ayat Bandung. Seluruh hak cipta dilindungi.
                </p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-700 transition">Portal Admin</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
