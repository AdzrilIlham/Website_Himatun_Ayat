<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Yayasan Himatun Ayat - Mengukir Senyum dan Masa Depan Anak Yatim' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        /* Skala 125% pada layar desktop/laptop agar pada zoom 100% ukurannya pas dan lega */
        @media (min-width: 1024px) {
            html {
                font-size: 125%; /* 1rem = 20px (skala 125% dari 16px) */
            }
        }
    </style>
</head>
<body class="min-h-screen bg-white font-sans antialiased text-slate-800 selection:bg-[#2D6A4F] selection:text-white">

    <!-- Floating Fixed Navbar (Tetap Melayang Fixed / Mengambang Saat Halaman di-Scroll) -->
    <header class="fixed top-0 inset-x-0 z-50 w-full pt-3 sm:pt-4 px-4 sm:px-6 lg:px-8 pointer-events-none transition-all duration-300">
        <div class="max-w-7xl mx-auto bg-white/92 backdrop-blur-md rounded-full border border-slate-200/90 shadow-md hover:shadow-lg px-6 sm:px-8 py-3 sm:py-3.5 flex items-center justify-between pointer-events-auto transition-all duration-300">
            <!-- Brand / Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                @if(file_exists(public_path('images/logo-himmatun-ayat.png')))
                    <img src="{{ asset('images/logo-himmatun-ayat.png') }}" alt="Logo Himatun" class="w-8 h-8 object-contain">
                @endif
                <span class="font-bold text-base sm:text-lg tracking-tight text-slate-900 group-hover:text-[#2D6A4F] transition">
                    Yayasan Himatun
                </span>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-600">
                <a href="{{ route('home') }}" class="text-slate-900 font-semibold border-b-2 border-slate-900 pb-0.5">
                    Beranda
                </a>
                <a href="#tentang-kami" class="hover:text-[#2D6A4F] transition">
                    Tentang Kami
                </a>

                <!-- Preline Dropdown: Layanan -->
                <div class="hs-dropdown relative inline-flex [--trigger:hover]">
                    <button id="hs-dropdown-layanan" type="button" class="hs-dropdown-toggle inline-flex items-center gap-x-1.5 hover:text-[#2D6A4F] transition font-medium focus:outline-hidden" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown Layanan">
                        <span>Layanan</span>
                        <svg class="hs-dropdown-open:rotate-180 size-3.5 transition" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>

                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-48 bg-white shadow-xl rounded-2xl p-1.5 mt-2 z-50 border border-slate-100" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-layanan">
                        <a class="flex items-center gap-x-2 py-2 px-3 rounded-xl text-xs font-medium text-slate-700 hover:bg-[#E4F1EB] hover:text-[#2D6A4F] transition" href="#paket-donasi">
                            <span>Program Donasi Utama</span>
                        </a>
                        <a class="flex items-center gap-x-2 py-2 px-3 rounded-xl text-xs font-medium text-slate-700 hover:bg-[#E4F1EB] hover:text-[#2D6A4F] transition" href="#paket-sedekah">
                            <span>Paket Sedekah Kilat</span>
                        </a>
                        <a class="flex items-center gap-x-2 py-2 px-3 rounded-xl text-xs font-medium text-slate-700 hover:bg-[#E4F1EB] hover:text-[#2D6A4F] transition" href="#transparansi">
                            <span>Laporan Transparansi</span>
                        </a>
                        <a class="flex items-center gap-x-2 py-2 px-3 rounded-xl text-xs font-medium text-slate-700 hover:bg-[#E4F1EB] hover:text-[#2D6A4F] transition" href="#kabar-berita">
                            <span>Kabar & Dokumentasi</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Right CTA Button (Desktop) & Hamburger (Mobile) -->
            <div class="flex items-center gap-2">
                <a href="#paket-donasi" class="hidden sm:inline-flex py-2.5 px-6 items-center justify-center text-xs sm:text-sm font-semibold rounded-full border border-transparent bg-[#2D6A4F] text-white hover:bg-[#235843] focus:outline-hidden transition shadow-xs">
                    Donasi
                </a>

                <!-- Preline Collapse Toggle Button (Mobile/Tablet) -->
                <button type="button" 
                        class="hs-collapse-toggle md:hidden p-2 inline-flex justify-center items-center gap-x-2 rounded-full border border-slate-200 text-slate-700 hover:bg-slate-100 focus:outline-hidden pointer-events-auto" 
                        id="hs-navbar-collapse" 
                        aria-expanded="false" 
                        aria-controls="hs-navbar-collapse-heading" 
                        aria-label="Toggle navigation" 
                        data-hs-collapse="#hs-navbar-collapse-heading">
                    <svg class="hs-collapse-open:hidden size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg class="hs-collapse-open:block hidden size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Container (Collapse) -->
        <div id="hs-navbar-collapse-heading" class="hs-collapse hidden md:hidden overflow-hidden transition-all duration-300 max-w-7xl mx-auto mt-2 pointer-events-auto">
            <div class="bg-white/95 backdrop-blur-md rounded-3xl border border-slate-200/80 p-4 shadow-xl flex flex-col gap-3 text-sm font-medium text-slate-700">
                <a href="{{ route('home') }}" class="px-3 py-2 rounded-xl bg-slate-50 font-bold text-[#2D6A4F]">
                    Beranda
                </a>
                <a href="#tentang-kami" class="px-3 py-2 rounded-xl hover:bg-slate-50 transition">
                    Tentang Kami
                </a>
                <a href="#paket-donasi" class="px-3 py-2 rounded-xl hover:bg-slate-50 transition">
                    Paket Donasi
                </a>
                <a href="#transparansi" class="px-3 py-2 rounded-xl hover:bg-slate-50 transition">
                    Laporan Transparansi
                </a>
                <a href="#kabar-berita" class="px-3 py-2 rounded-xl hover:bg-slate-50 transition">
                    Kabar & Dokumentasi
                </a>
                <div class="pt-2 border-t border-slate-100 flex flex-col gap-2">
                    <a href="#paket-donasi" class="py-2.5 px-4 text-center font-bold text-xs rounded-full bg-[#2D6A4F] text-white hover:bg-[#235843] transition shadow-xs">
                        Donasi Sekarang
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Top Wrapper with Rich Gradient Background (Envelops Hero for seamless gradation) -->
    <div class="relative bg-gradient-to-b from-[#D8F4EB] via-[#EDF8F3] to-white overflow-hidden pt-20 sm:pt-24 lg:pt-28">
        
        <!-- Ambient Radial Glows (Mint di kiri atas, Warm Amber Sunshine di kanan atas) -->
        <div class="absolute -top-24 left-0 w-full sm:w-2/3 h-[600px] bg-gradient-to-br from-[#BFEFE0]/85 via-[#DCF6ED]/45 to-transparent pointer-events-none blur-3xl -z-10"></div>
        <div class="absolute -top-20 right-0 w-full sm:w-3/5 h-[650px] bg-gradient-to-bl from-[#FDE899]/70 via-[#FEF3C7]/40 to-transparent pointer-events-none blur-3xl -z-10"></div>

        <!-- Main Content (Hero & Top Sections flow inside the gradient) -->
        <main>
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    <!-- Footer Publik -->
    <footer id="tentang-kami" class="border-t border-slate-200 bg-white mt-20 pt-14 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 lg:gap-12 items-start">
                
                <!-- Kolom 1: Profil Yayasan -->
                <div class="md:col-span-5 lg:col-span-4">
                    <h3 class="text-base font-bold text-slate-900 tracking-tight">
                        Yayasan Himatun Ayat Bandung
                    </h3>
                    <p class="mt-3 text-xs sm:text-[13px] text-slate-500 leading-relaxed">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                    </p>
                </div>

                <!-- Kolom 2: Lokasi & Kontak -->
                <div class="md:col-span-4 lg:col-span-4">
                    <h4 class="text-sm font-bold text-slate-900">
                        Lokasi & Kontak
                    </h4>
                    <div class="mt-3 space-y-3 text-xs sm:text-[13px] text-slate-600">
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-slate-700 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            <p class="leading-relaxed">
                                Jalan Cibiru Indah VII RT/RW 04/14, Cibiru Wetan, Kec. Cileunyi, Kabupaten Bandung, Jawa Barat 40625
                            </p>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2zm.01 1.67c2.2 0 4.26.86 5.82 2.41 1.56 1.56 2.41 3.63 2.41 5.83 0 4.54-3.7 8.24-8.24 8.24-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31c-.82-1.31-1.26-2.83-1.26-4.38 0-4.54 3.7-8.24 8.24-8.24zm4.52 11.66c-.25-.13-1.47-.72-1.7-.81-.23-.08-.39-.13-.56.13-.17.25-.64.81-.79.97-.14.17-.29.19-.54.06-.25-.13-1.07-.39-2.03-1.25-.75-.67-1.26-1.5-1.41-1.75-.14-.25-.02-.39.11-.51.11-.11.25-.29.38-.44.13-.14.17-.25.25-.42.08-.17.04-.31-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.49-.41-.42-.56-.43h-.48c-.17 0-.44.06-.67.31-.23.25-.88.86-.88 2.1 0 1.24.9 2.44 1.03 2.61.13.17 1.77 2.7 4.29 3.79.6.26 1.07.41 1.43.53.6.19 1.15.16 1.58.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.15-1.18-.06-.11-.23-.17-.48-.29z"/>
                            </svg>
                            <a href="https://wa.me/6281268765420" target="_blank" rel="noopener noreferrer" class="hover:text-emerald-700 transition font-medium">
                                WhatsApp: +6281268765420
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kolom 3: Cuplikan Peta Lokasi -->
                <div class="md:col-span-3 lg:col-span-4">
                    <a href="https://maps.google.com/?q=Jalan+Cibiru+Indah+VII+Bandung" target="_blank" rel="noopener noreferrer" 
                       class="block group relative rounded-2xl overflow-hidden border border-slate-200 shadow-xs hover:shadow-md transition">
                        <!-- Stylized Map Preview Graphic -->
                        <div class="relative w-full h-32 bg-[#EBE7DF] overflow-hidden">
                            <!-- SVG Street Grid Lines -->
                            <svg class="absolute inset-0 w-full h-full opacity-60" viewBox="0 0 300 150" preserveAspectRatio="none">
                                <path d="M-10,30 L310,40 M-10,90 L310,80 M60,-10 L70,160 M180,-10 L170,160 M240,-10 L250,160 M-10,130 L150,130" stroke="#FFFFFF" stroke-width="6" fill="none" />
                                <path d="M-10,65 L310,65 M120,-10 L130,160 M40,20 L280,140" stroke="#D5CEC2" stroke-width="4" fill="none" />
                                <rect x="80" y="45" width="35" height="15" fill="#DDD8CE" rx="2"/>
                                <rect x="140" y="15" width="30" height="20" fill="#DDD8CE" rx="2"/>
                                <rect x="190" y="70" width="40" height="18" fill="#DDD8CE" rx="2"/>
                            </svg>
                            
                            <!-- Location Pin Marker -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="relative flex flex-col items-center animate-bounce">
                                    <div class="w-8 h-8 rounded-full bg-sky-500 border-2 border-white shadow-md flex items-center justify-center text-white">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="w-2 h-1 bg-slate-400 rounded-full blur-[1px] mt-0.5"></div>
                                </div>
                            </div>

                            <!-- Click overlay banner -->
                            <div class="absolute bottom-2 inset-x-2 bg-white/90 backdrop-blur-xs py-1 px-2.5 rounded-lg text-center text-[10px] font-semibold text-slate-700 group-hover:bg-[#2D6A4F] group-hover:text-white transition">
                                Buka di Google Maps ↗
                            </div>
                        </div>
                    </a>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="mt-12 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-2">
                <p>2026 Yayasan Himatun Ayat Bandung</p>
                <div class="flex items-center gap-4 text-slate-400">
                    <a href="/admin/dashboard" class="hover:text-slate-600 transition">Portal Admin</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
