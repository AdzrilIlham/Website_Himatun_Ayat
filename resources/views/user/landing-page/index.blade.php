@extends('layouts.user')

@section('content')
@php
    $heroSlides = [
        ['image' => 'images/User/WisudaKhotmilQuran.png', 'alt' => 'Santri belajar bersama di ruang kelas'],
        ['image' => 'images/User/Paket SedekahKilat.png', 'alt' => 'Kegiatan makan bersama anak asuh'],
        ['image' => 'images/User/Lomba Balap Makan Kerupuk.png', 'alt' => 'Anak asuh mengikuti lomba makan kerupuk'],
        ['image' => 'images/User/Renovasi Ruang Belajar.png', 'alt' => 'Pembangunan ruang belajar asrama'],
    ];
@endphp
<div class="overflow-x-hidden" data-landing-animations>

    <!-- ========================================== -->
    <!-- 1. HERO SECTION                            -->
    <!-- ========================================== -->
    <section class="relative pt-10 sm:pt-12 lg:pt-6 pb-8 sm:pb-12 lg:pb-14" data-animate-hero>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 lg:gap-10 items-center">
                
                <!-- Kolom Foto: Di Mobile Tampil Pertama (order-1), di Desktop Tampil di Kanan (lg:order-2) -->
                <div class="order-1 lg:order-2 lg:col-span-5 relative flex justify-center">
                    <div class="relative w-full max-w-lg sm:max-w-xl lg:max-w-none">
                        
                        <!-- Warm Yellow Sunshine Ambient Aura behind the photo stack (Sesuai Desain Landing Page.png) -->
                        <div class="absolute -bottom-8 -right-8 w-60 sm:w-80 h-60 sm:h-80 bg-gradient-to-tl from-[#FDE047]/85 via-[#FEF08A]/60 to-transparent rounded-full blur-3xl pointer-events-none -z-10"></div>
                        <div class="absolute -top-10 -right-6 w-52 sm:w-72 h-52 sm:h-72 bg-gradient-to-bl from-[#FACC15]/45 via-[#FEF08A]/40 to-transparent rounded-full blur-2xl pointer-events-none -z-10"></div>

                        <!-- Main Frame Depan + Animated Stacked Image Carousel -->
                        <div class="relative aspect-[5/3]">
                            <!-- Swiper.js cards effect menggantikan layer rotate-3/rotate-6 manual -->
                            <div class="absolute inset-0">
                                <div class="hero-image-swiper swiper w-full h-full">
                                    <div class="swiper-wrapper">
                                        @foreach ($heroSlides as $slide)
                                            <div class="swiper-slide overflow-hidden rounded-2xl sm:rounded-3xl border-4 sm:border-[5px] border-white bg-white">
                                                <div class="relative w-full h-full">
                                                    <img src="{{ asset($slide['image']) }}"
                                                         alt="{{ $slide['alt'] }}"
                                                         class="w-full h-full object-cover"
                                                         @if ($loop->first) fetchpriority="high" @endif>

                                                    <!-- Gradient Fade Overlay + Caption, ikut berputar bersama foto -->
                                                    <div class="absolute inset-x-0 bottom-0 pointer-events-none bg-gradient-to-t from-[#143D2B]/95 via-[#1E4D3B]/75 to-transparent p-3.5 sm:p-5 pt-10 sm:pt-12 text-white opacity-0 transition-opacity duration-200 [.swiper-slide-active_&]:opacity-100">
                                                        <p class="text-[10px] sm:text-[11px] font-bold tracking-wider text-[#FCD34D] uppercase">
                                                            RUANG BELAJAR CERIA • ASRAMA BANDUNG
                                                        </p>
                                                        <p class="text-xs sm:text-[13px] font-medium text-white/95 mt-1 leading-snug">
                                                            "Menumbuhkan tunas generasi cerdas, mandiri, dan berakhlakul karimah."
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Soft subtle sunlit highlight over top-right of image (opacity diturunkan agar tidak terlalu kuning) -->
                            <div class="absolute top-0 right-0 w-36 sm:w-48 h-36 sm:h-48 bg-gradient-to-bl from-[#FEF08A]/12 via-transparent to-transparent pointer-events-none z-10"></div>
                        </div>

                    </div>
                </div>

                <!-- Kolom Teks: Di Mobile Tampil di Bawah Foto (order-2), di Desktop Tampil di Kiri (lg:order-1) -->
                <div class="order-2 lg:order-1 lg:col-span-7 space-y-4 sm:space-y-5">
                    <h1 class="text-2xl sm:text-4xl lg:text-[2.6rem] xl:text-[2.9rem] font-extrabold text-slate-800 leading-[1.16] tracking-tight" data-hero-heading>
                        Bersama <span class="text-[#2D6A4F]">Mengukir</span><br>
                        <span class="text-[#2D6A4F]">Senyum</span> dan <span class="text-[#E5A800]">Masa Depan</span><br>
                        Anak Yatim dan Dhuafa
                    </h1>

                    <p class="text-xs sm:text-sm lg:text-[15px] text-slate-600 leading-relaxed max-w-xl font-normal" data-hero-description>
                        Membangun masa depan generasi yatim, piatu, dan dhuafa Kota Bandung berlandaskan tata kelola amanah, legitimasi negara yang transparan, serta pembinaan akhlak karimah yang berkesinambungan.
                    </p>

                    <div class="pt-1" data-hero-cta>
                        <a href="{{ route('kampanye.index') }}" class="py-2.5 px-6 sm:py-3 sm:px-7 inline-flex items-center gap-x-2 text-xs sm:text-sm font-semibold rounded-full border border-transparent bg-[#2D6A4F] text-white hover:bg-[#235843] focus:outline-hidden transition shadow-sm shadow-emerald-900/15">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                            <span>Donasi Sekarang</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ========================================== -->
    <!-- 2. RECENT DONATIONS TICKER / BADGES        -->
    <!-- ========================================== -->
    @if ($recentDonors->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-1 sm:-mt-2 relative z-20">
            <div class="relative py-2"
                 data-animate="fade-up"
                 data-animate-distance="8"
                 data-animate-duration="0.5"
                 data-animate-start="top 88%">

                <!-- Ticker berjalan: dua set badge identik agar loop tidak terlihat putus -->
                <div class="donor-marquee-viewport no-scrollbar [mask-image:linear-gradient(to_right,transparent,black_4%,black_96%,transparent)]">
                    <div class="donor-marquee-track flex w-max items-center">
                        @foreach ([0, 1] as $pass)
                            <div class="flex items-center gap-2.5 pr-2.5 sm:gap-3 sm:pr-3"
                                 @if ($pass === 1) aria-hidden="true" @endif>
                                @foreach ($recentDonors as $donor)
                                    <div class="shrink-0 bg-white/95 backdrop-blur-sm border border-slate-200/80 rounded-xl py-1.5 px-3 flex items-center gap-2 shadow-2xs hover:shadow-xs transition">
                                        <div class="w-7 h-7 rounded-full bg-[#E4F1EB] flex items-center justify-center shrink-0 overflow-hidden">
                                            <img src="{{ asset('images/User/profil.png') }}" alt="Avatar" class="w-3.5 h-3.5 object-contain">
                                        </div>
                                        <div>
                                            <p class="font-bold text-[11px] sm:text-xs text-slate-800 leading-tight whitespace-nowrap">{{ $donor['name'] }}</p>
                                            <p class="text-[10px] sm:text-[11px] text-slate-500 font-medium whitespace-nowrap">
                                                Donasi {{ $donor['amount'] }} • {{ $donor['time'] }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif


    <!-- ========================================== -->
    <!-- 3. STATISTICS / IMPACT SECTION             -->
    <!-- ========================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 sm:mt-14">
        <!-- Dark Green Watermarked Container -->
        <div class="relative rounded-2xl sm:rounded-3xl bg-[#235843] p-5 sm:p-7 lg:p-8 text-white overflow-hidden shadow-lg"
             data-animate="fade-up"
             data-animate-start="top 82%"
             data-animate-distance="26"
             data-animate-duration="0.7">
            
            <!-- Islamic Crescent SVG Decorative Watermark Background -->
            <div class="absolute inset-0 pointer-events-none opacity-10 select-none">
                <svg class="w-full h-full object-cover" viewBox="0 0 800 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="100" cy="80" r="120" stroke="white" stroke-width="30" stroke-dasharray="10 20" />
                    <circle cx="120" cy="80" r="100" fill="white" />
                    <circle cx="150" cy="70" r="100" fill="#235843" />
                    <circle cx="700" cy="220" r="140" stroke="white" stroke-width="40" />
                    <circle cx="720" cy="220" r="120" fill="white" />
                    <circle cx="760" cy="210" r="120" fill="#235843" />
                    <circle cx="400" cy="50" r="90" stroke="white" stroke-width="25" />
                    <circle cx="415" cy="50" r="75" fill="white" />
                    <circle cx="445" cy="45" r="75" fill="#235843" />
                </svg>
            </div>

            <!-- 3 Columns Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5 relative z-10"
                 data-animate-group="fade-scale"
                 data-animate-start="top 82%"
                 data-animate-distance="18"
                 data-animate-duration="0.6"
                 data-animate-stagger="0.12">
                
                <!-- Card 1 -->
                <div class="bg-[#E4F1EB] rounded-xl sm:rounded-2xl p-4 sm:p-5 text-slate-900 flex flex-col justify-between shadow-xs transition hover:translate-y-[-2px] duration-200">
                    <div>
                        <div class="flex items-center justify-between">
                            <div class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center p-1.5 shadow-2xs">
                                <img src="{{ asset('images/User/Icons.png') }}" alt="Icon Donasi" class="w-5 h-5 object-contain">
                            </div>
                            <span class="bg-[#FDE68A] text-[#92400E] text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                                Akumulasi s.d. {{ $impactStats['tahun_akumulasi'] }}
                            </span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" data-impact-stat="total-dana">{{ $impactStats['total_dana_formatted'] }}</h3>
                            <p class="font-bold text-xs sm:text-[13px] text-slate-800 mt-1">
                                Total Dana Amanah Terhimpun
                            </p>
                        </div>
                    </div>
                    <p class="text-[11px] sm:text-xs text-slate-600 mt-2.5 leading-relaxed">
                        Penghimpunan zakat, infaq, sedekah, dan wakaf terverifikasi yang diamanahkan untuk para mustahik.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-[#E4F1EB] rounded-xl sm:rounded-2xl p-4 sm:p-5 text-slate-900 flex flex-col justify-between shadow-xs transition hover:translate-y-[-2px] duration-200">
                    <div>
                        <div class="flex items-center justify-between">
                            <div class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center p-1.5 shadow-2xs">
                                <img src="{{ asset('images/User/kelompokorang.png') }}" alt="Icon Anak Asuh" class="w-5 h-5 object-contain">
                            </div>
                            <span class="bg-[#A2C7B6] text-[#1B4332] text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                                Terbina Rutin
                            </span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" data-impact-stat="anak-asuh">{{ $impactStats['anak_asuh_formatted'] }}</h3>
                            <p class="font-bold text-xs sm:text-[13px] text-slate-800 mt-1">
                                Anak Asuh & Keluarga Dhuafa
                            </p>
                        </div>
                    </div>
                    <p class="text-[11px] sm:text-xs text-slate-600 mt-2.5 leading-relaxed">
                        Penerima beasiswa pendidikan, santunan pangan, dan pembinaan karakter di wilayah Bandung Raya.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-[#E4F1EB] rounded-xl sm:rounded-2xl p-4 sm:p-5 text-slate-900 flex flex-col justify-between shadow-xs transition hover:translate-y-[-2px] duration-200">
                    <div>
                        <div class="flex items-center justify-between">
                            <div class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center p-1.5 shadow-2xs">
                                <img src="{{ asset('images/User/buku.png') }}" alt="Icon Program" class="w-5 h-5 object-contain">
                            </div>
                            <span class="bg-[#FDE68A] text-[#92400E] text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                                Program Berjalan
                            </span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight" data-impact-stat="program-aktif">{{ $impactStats['program_aktif_formatted'] }}</h3>
                            <p class="font-bold text-xs sm:text-[13px] text-slate-800 mt-1">
                                Program Pendidikan & Dakwah
                            </p>
                        </div>
                    </div>
                    <p class="text-[11px] sm:text-xs text-slate-600 mt-2.5 leading-relaxed">
                        Mencakup pondok tahfidz yatim, bimbingan belajar gratis, klinik umat, serta pemberdayaan ekonomi.
                    </p>
                </div>

            </div>
        </div>
    </section>


    <!-- ========================================== -->
    <!-- 4. PAKET DONASI CEPAT TERARAH              -->
    <!-- ========================================== -->
    @if ($programUtama)
    <section id="paket-donasi" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 sm:mt-16">
        
        <!-- Header Section -->
        <div class="space-y-1"
             data-animate-group="fade-up"
             data-animate-distance="20"
             data-animate-duration="0.7"
             data-animate-stagger="0.09">
            <div class="inline-flex items-center gap-1.5 text-2xs sm:text-xs font-bold tracking-wider text-slate-700 uppercase">
                <svg class="w-3.5 h-3.5 text-[#2D6A4F]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                </svg>
                <span>PILIHAN SEDEKAH & ZAKAT</span>
            </div>
            <h2 class="text-xl sm:text-2xl lg:text-[1.75rem] font-extrabold text-slate-900 tracking-tight">
                Paket Donasi Cepat Terarah
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-2xl">
                Pilih paket kebaikan untuk mendukung kebutuhan esensial para santri yatim dhuafa dengan penyaluran langsung.
            </p>
        </div>

        <!-- 2 Columns Grid: Program Utama & Paket Sedekah Kilat -->
        <div class="grid grid-cols-1 {{ $paketKilat->isNotEmpty() ? 'lg:grid-cols-2' : '' }} gap-6 sm:gap-7 mt-6 sm:mt-7 items-stretch"
             data-animate-group="fade-up"
             data-animate-start="top 82%"
             data-animate-distance="24"
             data-animate-duration="0.8"
             data-animate-stagger="0.14">
            
            <!-- Kolom Kiri: Program Utama Card -->
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/90 p-4 sm:p-6 shadow-xs flex flex-col justify-between h-full" data-animate-item="slide-left" data-animate-distance="32">
                <div>
                    <!-- Image Card with Tag -->
                    <div class="relative rounded-xl sm:rounded-2xl overflow-hidden aspect-[16/9] bg-slate-100">
                        <img src="{{ $programUtama['image'] }}" 
                             alt="{{ $programUtama['judul'] }}" 
                             class="w-full h-full object-cover">
                        <span class="absolute top-2.5 left-2.5 sm:top-3 sm:left-3 bg-[#1E3A2F]/85 backdrop-blur-xs text-white text-[10px] sm:text-[11px] font-bold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full shadow-xs">
                            {{ $programUtama['kategori'] }}
                        </span>
                    </div>

                    <!-- Title & Donatur -->
                    <div class="mt-3.5 sm:mt-4 flex items-center justify-between gap-2 min-h-[1.75rem]">
                        <h3 class="font-bold text-sm sm:text-base lg:text-lg text-slate-900">
                            {{ $programUtama['judul'] }}
                        </h3>
                        <div class="flex items-center gap-1 text-[11px] sm:text-xs text-slate-500 font-medium shrink-0">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                            <span>{{ number_format($programUtama['donatur'], 0, ',', '.') }} Donatur</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-xs text-slate-600 mt-1.5 sm:mt-2 leading-relaxed">
                        {{ $programUtama['deskripsi'] }}
                    </p>

                    <!-- Verification Badge Card -->
                    <div class="bg-[#E2F0EA] rounded-xl p-2.5 sm:p-3 flex flex-wrap sm:flex-nowrap items-center justify-between gap-2 mt-3.5 sm:mt-4">
                        <div class="flex items-center gap-2 text-xs text-slate-700 font-medium">
                            <svg class="w-4 h-4 text-[#2D6A4F] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Penyaluran terverifikasi, amanah & bebas potongan liar</span>
                        </div>
                        <span class="bg-white text-slate-700 text-[10px] sm:text-[11px] font-semibold px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-lg border border-slate-200/80 shadow-2xs shrink-0">
                            Pilihan Wakaf Bebas
                        </span>
                    </div>

                    <!-- Progress Bar & Target -->
                    <div class="mt-4 sm:mt-5">
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-700">
                            <span>Terkumpul:</span>
                            <span class="text-[#2D6A4F] font-bold">{{ $programUtama['persentase'] }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 mt-1.5 overflow-hidden">
                            <div class="bg-[#2D6A4F] h-2 rounded-full transition-all duration-500" style="width: {{ $programUtama['persentase'] }}%"></div>
                        </div>

                        <div class="flex items-center justify-between mt-2.5 sm:mt-3 text-xs">
                            <div>
                                <span class="text-[10px] sm:text-[11px] text-slate-400 block font-medium">Terkumpul</span>
                                <span class="text-sm sm:text-base font-extrabold text-slate-900">{{ $programUtama['terkumpul'] }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] sm:text-[11px] text-slate-400 block font-medium">Target Kebutuhan</span>
                                <span class="text-xs sm:text-sm font-bold text-slate-700">{{ $programUtama['target'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="mt-5 sm:mt-6 flex justify-center">
                    <a href="{{ $programUtama['url'] }}" class="py-2.5 px-6 sm:py-3 sm:px-8 inline-flex items-center justify-center gap-x-2 text-xs sm:text-sm font-semibold rounded-full border border-transparent bg-[#2D6A4F] text-white hover:bg-[#235843] focus:outline-hidden transition shadow-xs w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                        <span>Donasi Sekarang</span>
                    </a>
                </div>
            </div>

            @if ($paketKilat->isNotEmpty())
            <!-- Kolom Kanan: Paket Sedekah Kilat (Fixed Sizing Card) -->
            <div id="paket-sedekah" class="flex flex-col justify-between h-full" data-animate-item="fade-up" data-animate-distance="20" x-data="{
                activeSlide: 0,
                slides: @js($paketKilat->all()),
                next() {
                    this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                },
                prev() {
                    this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
                }
            }">
                <!-- Navigation & Title Header -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5 sm:gap-3">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-[#2D6A4F] text-white flex items-center justify-center shrink-0 shadow-xs">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm sm:text-base text-slate-900 leading-tight">
                                Paket Sedekah Kilat
                            </h3>
                            <p class="text-[11px] sm:text-xs text-slate-500 font-medium">
                                Pilihan cepat donasi rutin santri yatim
                            </p>
                        </div>
                    </div>

                    <!-- Arrow Controls (Interactive Alpine buttons) -->
                    <div class="flex items-center gap-1.5 sm:gap-2">
                        <button type="button" 
                                @click="prev()" 
                                class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition shadow-2xs"
                                title="Sebelumnya">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button type="button" 
                                @click="next()" 
                                class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition shadow-2xs"
                                title="Berikutnya">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Sedekah Kilat Card Content (Fixed Height Structure) -->
                <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/90 p-4 sm:p-6 shadow-xs flex flex-col justify-between flex-1 mt-3">
                    <div>
                        <!-- Image Container with Fixed Aspect Ratio -->
                        <div class="relative rounded-xl sm:rounded-2xl overflow-hidden aspect-[16/9] bg-slate-100">
                            <img :src="slides[activeSlide].image" 
                                 src="{{ $paketKilat->first()['image'] }}"
                                 :alt="slides[activeSlide].judul" 
                                 alt="{{ $paketKilat->first()['judul'] }}"
                                 class="w-full h-full object-cover transition-opacity duration-300">
                        </div>

                        <!-- Title & Donatur with Fixed Height Row -->
                        <div class="mt-3.5 sm:mt-4 flex items-center justify-between gap-2 min-h-[1.75rem]">
                            <h3 class="font-bold text-sm sm:text-base text-slate-900 truncate" x-text="slides[activeSlide].judul">
                                {{ $paketKilat->first()['judul'] }}
                            </h3>
                            <div class="flex items-center gap-1 text-[11px] sm:text-xs text-slate-500 font-medium shrink-0">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                                <span x-text="slides[activeSlide].donatur + ' Donatur'">{{ number_format($paketKilat->first()['donatur'], 0, ',', '.') }} Donatur</span>
                            </div>
                        </div>

                        <!-- Description with Fixed Height (h-10 sm:h-12 overflow-hidden) to prevent any layout shifts -->
                        <div class="h-10 sm:h-12 mt-1.5 sm:mt-2 overflow-hidden">
                            <p class="text-xs text-slate-600 leading-relaxed line-clamp-2" x-text="slides[activeSlide].deskripsi">
                                {{ $paketKilat->first()['deskripsi'] }}
                            </p>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="mt-5 sm:mt-6 flex justify-center">
                        <a :href="slides[activeSlide].url" href="{{ $paketKilat->first()['url'] }}" class="py-2.5 px-6 sm:py-3 sm:px-8 inline-flex items-center justify-center gap-x-2 text-xs sm:text-sm font-semibold rounded-full border border-transparent bg-[#2D6A4F] text-white hover:bg-[#235843] focus:outline-hidden transition shadow-xs w-full sm:w-auto">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                            <span>Donasi Sekarang</span>
                        </a>
                    </div>
                </div>

                <!-- Carousel Dots Indicator (Interactive) -->
                <div class="flex items-center justify-center gap-2 mt-3 sm:mt-4">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button type="button" 
                                @click="activeSlide = index"
                                :class="activeSlide === index ? 'w-2.5 h-2.5 rounded-full bg-slate-700 transition' : 'w-2.5 h-2.5 rounded-full bg-slate-300 hover:bg-slate-400 transition'"
                                :aria-label="'Slide ' + (index + 1)">
                        </button>
                    </template>
                </div>
            </div>
            @endif

        </div>
    </section>
    @endif


    <!-- ========================================== -->
    <!-- 5. AJAKAN KOLABORASI KEGIATAN              -->
    <!-- ========================================== -->
    <section id="kolaborasi-kegiatan" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 sm:mt-16">
        <div class="relative rounded-2xl sm:rounded-3xl bg-[#F1F8F3] border border-[#DDEBE2] p-5 sm:p-7 lg:p-8 overflow-hidden shadow-xs"
             data-animate="fade-up"
             data-animate-start="top 82%"
             data-animate-distance="26"
             data-animate-duration="0.7">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 lg:gap-10 items-start"
                 data-animate-group="fade-up"
                 data-animate-start="top 82%"
                 data-animate-distance="22"
                 data-animate-duration="0.75"
                 data-animate-stagger="0.12">

                <!-- Sisi Kiri: Narasi & Pilihan Kegiatan -->
                <div class="lg:col-span-6 space-y-3 sm:space-y-4" data-animate-item="slide-left" data-animate-distance="28">
                    <div class="inline-flex items-center rounded-full bg-[#DCEEE3] text-[#235843] text-[10px] sm:text-[11px] font-bold tracking-wider uppercase px-3 sm:px-4 py-1 sm:py-1.5">
                        Kolaborasi Kebaikan
                    </div>

                    <h2 class="text-[1.7rem] sm:text-3xl lg:text-[2.15rem] font-extrabold text-[#143D2B] tracking-tight leading-[1.25]">
                        Punya Rencana
                        <span class="text-[#2D6A4F] bg-[linear-gradient(transparent_66%,#FDE68A_66%)] box-decoration-clone px-0.5">
                            Berbagi Kebaikan?
                        </span>
                    </h2>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Mari hadirkan kebahagiaan bersama adik-adik Himmatun Ayat.
                    </p>

                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-xl">
                        Ingin mengadakan buka puasa bersama, santunan, kunjungan, atau kegiatan sosial lainnya?
                        Kami membuka kesempatan bagi individu, komunitas, dan instansi untuk berkolaborasi bersama yayasan.
                    </p>

                    <!-- Pilihan Bentuk Kegiatan -->
                    <div class="flex flex-wrap gap-2 sm:gap-2.5 pt-1">
                        <span class="inline-flex items-center gap-1.5 sm:gap-2 rounded-full bg-white border border-slate-200 px-3 sm:px-4 py-1.5 sm:py-2 text-[11px] sm:text-xs font-semibold text-slate-700 shadow-2xs">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#2D6A4F] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                            </svg>
                            <span>Buka Puasa Bersama</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 sm:gap-2 rounded-full bg-white border border-slate-200 px-3 sm:px-4 py-1.5 sm:py-2 text-[11px] sm:text-xs font-semibold text-slate-700 shadow-2xs">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#2D6A4F] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H4.5a1.5 1.5 0 01-1.5-1.5v-8.25M12 10.5v9.75m0-9.75a3.75 3.75 0 100-7.5 3.75 3.75 0 000 7.5zm8.25 0H3.75" />
                            </svg>
                            <span>Santunan &amp; Donasi</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 sm:gap-2 rounded-full bg-white border border-slate-200 px-3 sm:px-4 py-1.5 sm:py-2 text-[11px] sm:text-xs font-semibold text-slate-700 shadow-2xs">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#2D6A4F] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            <span>Kunjungan Panti</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 sm:gap-2 rounded-full bg-white border border-slate-200 px-3 sm:px-4 py-1.5 sm:py-2 text-[11px] sm:text-xs font-semibold text-slate-700 shadow-2xs">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#2D6A4F] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                            <span>Pendidikan &amp; Sosial</span>
                        </span>
                    </div>

                    <!-- CTA Ajukan Kegiatan -->
                    <div class="pt-2 sm:pt-3">
                        <a href="{{ route('berita-kegiatan.index') }}#kalender-kegiatan"
                           class="py-3 px-6 sm:py-3.5 sm:px-8 inline-flex items-center justify-center gap-2 text-xs sm:text-sm font-bold rounded-full bg-[#2D6A4F] text-white hover:bg-[#235843] focus:outline-hidden transition shadow-sm shadow-emerald-900/15">
                            <span>Ajukan Kegiatan Sekarang</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                        <p class="text-[10px] sm:text-[11px] text-slate-500 mt-2.5 leading-relaxed max-w-md">
                            Sampaikan rencana kegiatanmu dan tim yayasan akan membantu meninjau pengajuan tersebut.
                        </p>
                    </div>
                </div>

                <!-- Sisi Kanan: Kegiatan Mendatang -->
                <div class="lg:col-span-6 space-y-3.5 sm:space-y-4" data-animate-item="slide-right" data-animate-distance="28">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="flex size-8 items-center justify-center rounded-xl bg-[#2D6A4F] text-white shadow-2xs">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5" />
                                </svg>
                            </span>
                            <h3 class="text-lg sm:text-xl font-extrabold text-[#143D2B] tracking-tight">
                                Kegiatan Mendatang
                            </h3>
                        </div>

                        <a href="{{ route('berita-kegiatan.index') }}#kalender-kegiatan"
                           class="inline-flex items-center gap-1 text-xs font-bold text-[#2D6A4F] hover:text-[#143D2B] transition">
                            <span>Lihat Kalender</span>
                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>

                    <div class="space-y-3">
                        @foreach ($kegiatanMendatang as $agenda)
                            <div class="group relative rounded-2xl bg-white border border-[#DCEEE3] p-4 sm:p-5 shadow-2xs hover:shadow-md hover:border-[#2D6A4F]/40 transition-all duration-200">
                                <div class="flex items-start gap-3.5 sm:gap-4">
                                    <!-- Kotak Tanggal & Bulan -->
                                    <div class="flex w-16 sm:w-20 shrink-0 flex-col items-center justify-center rounded-xl sm:rounded-2xl bg-[#E4F1EB] border border-[#CFE6DA] px-2.5 py-3 text-center transition group-hover:bg-[#2D6A4F] group-hover:border-[#2D6A4F]">
                                        <span class="text-xl sm:text-2xl font-extrabold leading-none text-[#143D2B] tabular transition group-hover:text-white">
                                            {{ $agenda['day'] }}
                                        </span>
                                        <span class="mt-1.5 text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider text-[#235843] transition group-hover:text-emerald-100">
                                            {{ $agenda['month_year'] }}
                                        </span>
                                    </div>

                                    <!-- Detail Kegiatan -->
                                    <div class="min-w-0 flex-1">
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-[#E4F1EB] px-2.5 py-0.5 text-[10px] sm:text-[11px] font-bold text-[#1E4D3B]">
                                            <span class="size-1.5 rounded-full bg-[#2D6A4F]"></span>
                                            <span>{{ $agenda['badge'] }}</span>
                                        </span>

                                        <h4 class="mt-1.5 text-sm sm:text-base font-extrabold text-[#143D2B] leading-snug group-hover:text-[#2D6A4F] transition">
                                            {{ $agenda['judul'] }}
                                        </h4>

                                        <div class="mt-2 space-y-1 text-xs text-slate-600">
                                            <div class="flex items-center gap-2">
                                                <svg class="size-3.5 shrink-0 text-[#2D6A4F]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                                <span class="font-medium">{{ $agenda['waktu'] }}</span>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <svg class="size-3.5 shrink-0 text-[#2D6A4F]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                                </svg>
                                                <span class="truncate">{{ $agenda['lokasi'] }}</span>
                                            </div>
                                        </div>

                                        <a href="{{ $agenda['url'] }}"
                                           class="mt-2.5 inline-flex items-center gap-1.5 text-xs font-extrabold text-[#2D6A4F] hover:text-[#143D2B] transition">
                                            <span>Informasi Kegiatan</span>
                                            <svg class="size-3.5 transition-transform duration-200 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ========================================== -->
    <!-- 6. LAPORAN AUDIT & TRANSPARANSI            -->
    <!-- ========================================== -->
    <section id="transparansi" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 sm:mt-16">
        <!-- Deep Green Watermarked Container -->
        <div class="relative rounded-2xl sm:rounded-3xl bg-[#235843] p-5 sm:p-7 lg:p-8 text-white overflow-hidden shadow-lg">
            
            <!-- Islamic Crescent SVG Decorative Background -->
            <div class="absolute inset-0 pointer-events-none opacity-10 select-none">
                <svg class="w-full h-full object-cover" viewBox="0 0 800 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="200" cy="150" r="160" stroke="white" stroke-width="35" />
                    <circle cx="220" cy="150" r="140" fill="white" />
                    <circle cx="260" cy="140" r="140" fill="#235843" />
                    <circle cx="650" cy="80" r="130" stroke="white" stroke-width="25" />
                    <circle cx="670" cy="80" r="110" fill="white" />
                    <circle cx="710" cy="70" r="110" fill="#235843" />
                </svg>
            </div>

            <!-- Content Grid: Left Text & Right 3 Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-center relative z-10">
                
                <!-- Sisi Kiri: Narasi Transparansi & Download Button -->
                <div class="lg:col-span-5 space-y-3 sm:space-y-4"
                     data-animate-group="slide-left"
                     data-animate-start="top 80%"
                     data-animate-distance="24"
                     data-animate-duration="0.7"
                     data-animate-stagger="0.1">
                    <div class="inline-flex items-center gap-1.5 text-2xs sm:text-xs font-bold tracking-wider text-emerald-200 uppercase">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                        <span>LAPORAN AUDIT & TRANSPARANSI</span>
                    </div>

                    <h2 class="text-xl sm:text-2xl lg:text-[1.7rem] font-extrabold text-white leading-tight">
                        Setiap Rupiah Dipertanggungjawabkan Dunia & Akhirat
                    </h2>

                    <p class="text-xs sm:text-[13px] text-emerald-100/90 leading-relaxed font-normal">
                        Kami memegang teguh amanah para muhsin. Seluruh alokasi dana donasi disalurkan secara profesional dengan presentase efisiensi operasional sesuai regulasi Syariah & BAZNAZ
                    </p>

                    <div class="pt-1">
                        <a href="/admin/dashboard/export/pdf" target="_blank" class="py-2.5 px-4 inline-flex items-center gap-x-2 text-xs font-bold rounded-xl bg-white text-slate-900 hover:bg-slate-50 transition shadow-xs">
                            <img src="{{ asset('images/User/lembaran.png') }}" alt="PDF" class="w-4 h-4 object-contain">
                            <span>Unduh Laporan Keuangan 2025 (PDF)</span>
                        </a>
                    </div>
                </div>

                <!-- Sisi Kanan: 3 White Stat Cards -->
                <div class="lg:col-span-7">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 sm:gap-3.5"
                         data-animate-group="fade-scale"
                         data-animate-start="top 80%"
                         data-animate-distance="16"
                         data-animate-duration="0.6"
                         data-animate-stagger="0.1">
                        
                        <!-- Card 1: Pendidikan & Santunan -->
                        <div class="bg-white rounded-xl sm:rounded-2xl p-3.5 sm:p-4 text-center flex flex-col items-center justify-center shadow-xs">
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-[#E4F1EB] flex items-center justify-center p-1.5 mb-1.5">
                                <img src="{{ asset('images/User/wisuda.png') }}" alt="Pendidikan" class="w-5 h-5 object-contain">
                            </div>
                            <span class="text-lg sm:text-xl lg:text-[1.35rem] font-extrabold text-slate-900">
                                78,5%
                            </span>
                            <h4 class="font-bold text-[11px] sm:text-xs text-slate-900 mt-1">
                                Pendidikan &amp; Santunan
                            </h4>
                            <p class="text-[10px] text-slate-500 mt-0.5">
                                Beasiswa, asrama, &amp; gizi langsung
                            </p>
                        </div>

                        <!-- Card 2: Dakwah & Syiar Islam -->
                        <div class="bg-white rounded-xl sm:rounded-2xl p-3.5 sm:p-4 text-center flex flex-col items-center justify-center shadow-xs">
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-[#E4F1EB] flex items-center justify-center p-1.5 mb-1.5">
                                <img src="{{ asset('images/User/masjid.png') }}" alt="Dakwah" class="w-5 h-5 object-contain">
                            </div>
                            <span class="text-lg sm:text-xl lg:text-[1.35rem] font-extrabold text-slate-900">
                                12%
                            </span>
                            <h4 class="font-bold text-[11px] sm:text-xs text-slate-900 mt-1">
                                Dakwah &amp; Syiar Islam
                            </h4>
                            <p class="text-[10px] text-slate-500 mt-0.5">
                                Tahfidz Quran &amp; Kajian pembinaan
                            </p>
                        </div>

                        <!-- Card 3: Operasional Amil (Full-width col-span-2 di Mobile) -->
                        <div class="col-span-2 sm:col-span-1 bg-white rounded-xl sm:rounded-2xl p-3.5 sm:p-4 text-center flex flex-col items-center justify-center shadow-xs">
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-[#E4F1EB] flex items-center justify-center p-1.5 mb-1.5">
                                <img src="{{ asset('images/User/operasionaladmin.png') }}" alt="Operasional" class="w-5 h-5 object-contain">
                            </div>
                            <span class="text-lg sm:text-xl lg:text-[1.35rem] font-extrabold text-slate-900">
                                9,5%
                            </span>
                            <h4 class="font-bold text-[11px] sm:text-xs text-slate-900 mt-1">
                                Operasional Amil
                            </h4>
                            <p class="text-[10px] text-slate-500 mt-0.5">
                                Sesuai batas ketetapan fiqih
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ========================================== -->
    <!-- 7. KABAR & AKTIVITAS TERBARU YAYASAN       -->
    <!-- ========================================== -->
    <section id="kabar-berita" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 sm:mt-16" x-data="{
        selectedCategory: 'all'
    }">
        
        <!-- Header -->
        <div class="space-y-1"
             data-animate-group="fade-up"
             data-animate-distance="20"
             data-animate-duration="0.75"
             data-animate-stagger="0.09">
            <div class="inline-flex items-center gap-1.5 text-2xs sm:text-xs font-bold tracking-wider text-slate-700 uppercase">
                <svg class="w-3.5 h-3.5 text-[#2D6A4F]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                </svg>
                <span>KABAR KEGIATAN & DOKUMENTASI</span>
            </div>
            <h2 class="text-xl sm:text-2xl lg:text-[1.75rem] font-extrabold text-slate-900 tracking-tight">
                Kabar & Aktivitas Terbaru Yayasan
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-2xl">
                Dokumentasi penyaluran amanah donatur, senyum syukur adik-adik asuh, dan perkembangan program binaan di Bandung
            </p>
        </div>

        <!-- Filter Tabs & Link -->
        <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
            <!-- Tabs (Interactive Filter) -->
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1"
                 data-animate-group="fade-in"
                 data-animate-duration="0.45"
                 data-animate-stagger="0.06"
                 data-animate-start="top 86%">
                <button type="button" 
                        @click="selectedCategory = 'all'" 
                        :class="selectedCategory === 'all' ? 'py-1.5 px-3.5 rounded-lg bg-[#2D6A4F] text-white text-xs font-semibold shadow-xs' : 'py-1.5 px-3.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-medium transition'">
                    Semua Program
                </button>
                @foreach ($kategoriBerita as $kategori)
                    <button type="button" 
                            @click="selectedCategory = @js($kategori)" 
                            :class="selectedCategory === @js($kategori) ? 'py-1.5 px-3.5 rounded-lg bg-[#2D6A4F] text-white text-xs font-semibold shadow-xs' : 'py-1.5 px-3.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-medium transition'">
                        {{ $kategori }}
                    </button>
                @endforeach
            </div>

            <!-- View All Link -->
            <a href="{{ route('berita-kegiatan.index') }}" class="text-xs font-bold text-slate-900 hover:text-[#2D6A4F] inline-flex items-center gap-1 transition"
               data-animate="fade-in"
               data-animate-duration="0.5"
               data-animate-delay="0.18"
               data-animate-start="top 86%">
                <span>Lihat Semua Kabar Berita</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>

        <!-- 4 News Cards Grid -->
        @if ($beritaTerbaru->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5 mt-5"
                 data-animate-group="fade-scale"
                 data-animate-distance="20"
                 data-animate-duration="0.65"
                 data-animate-stagger="0.1"
                 data-animate-start="top 84%">
                @foreach ($beritaTerbaru as $berita)
                    <div x-show="selectedCategory === 'all' || selectedCategory === @js($berita['kategori'])"
                         class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/90 p-3 sm:p-3.5 flex flex-col justify-between shadow-2xs hover:shadow-md transition duration-200">
                        <div>
                            <!-- Image with Badge -->
                            <div class="relative rounded-lg sm:rounded-xl overflow-hidden aspect-[4/3] bg-slate-100">
                                <img src="{{ $berita['image'] }}" 
                                     alt="{{ $berita['judul'] }}" 
                                     class="w-full h-full object-cover">
                                <span class="absolute top-2 left-2 bg-[#1E3A2F]/85 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    {{ $berita['kategori'] }}
                                </span>
                                <a href="{{ $berita['url'] }}" class="absolute inset-0" aria-label="{{ $berita['judul'] }}"></a>
                            </div>

                            <!-- Meta: Date & Location -->
                            <div class="mt-2.5 flex items-center gap-2.5 text-[10px] sm:text-[11px] text-slate-500 font-medium">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h14.25A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                                    </svg>
                                    {{ $berita['tanggal'] }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    {{ $berita['lokasi'] }}
                                </span>
                            </div>

                            <!-- Title -->
                            <h4 class="font-bold text-xs sm:text-[13px] text-slate-900 mt-1.5 line-clamp-1">
                                <a href="{{ $berita['url'] }}" class="transition hover:text-[#2D6A4F]">{{ $berita['judul'] }}</a>
                            </h4>

                            <!-- Subtitle -->
                            <p class="text-[11px] sm:text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2 sm:line-clamp-3">
                                {{ $berita['ringkasan'] }}
                            </p>
                        </div>

                        <!-- Button -->
                        <div class="mt-3">
                            <a href="{{ $berita['url'] }}" class="w-full py-1.5 px-2.5 rounded-lg bg-[#E4F1EB] hover:bg-[#D4EAE0] text-slate-800 text-[11px] sm:text-xs font-bold inline-flex items-center justify-center gap-1.5 transition">
                                <img src="{{ asset('images/User/lembaran.png') }}" alt="File" class="w-3 h-3 object-contain">
                                <span>Lihat Selengkapnya</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="mt-5 rounded-2xl border border-slate-200/90 bg-white px-6 py-12 text-center shadow-2xs">
                <p class="text-sm font-semibold text-slate-700">Belum ada kabar terbaru</p>
                <p class="mt-1 text-xs text-slate-500">Kabar kegiatan akan tampil di sini setelah dipublikasikan.</p>
            </div>
        @endif
    </section>


    <!-- ========================================== -->
    <!-- 8. INSTAGRAM LIVE STORY BANNER             -->
    <!-- ========================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 sm:mt-16">
        <div class="relative rounded-xl sm:rounded-2xl bg-[#235843] p-5 sm:p-7 text-white flex flex-col md:flex-row items-start md:items-center justify-between gap-5 sm:gap-6 shadow-md overflow-hidden"
             data-animate-group="fade-up"
             data-animate-distance="20"
             data-animate-duration="0.7"
             data-animate-stagger="0.14">
            
            <!-- Sisi Kiri -->
            <div class="space-y-1 sm:space-y-1.5 max-w-2xl relative z-10">
                <div class="inline-flex items-center gap-1.5 text-2xs sm:text-xs font-bold tracking-wider text-emerald-200 uppercase">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                    </svg>
                    <span>TERHUBUNG BERSAMA KAMI</span>
                </div>

                <h3 class="text-lg sm:text-xl font-extrabold text-white leading-tight">
                    Ikuti Keseharian &amp; Live Story Adik-adik Asuh di Media Sosial
                </h3>

                <p class="text-xs sm:text-[13px] text-emerald-100/90 leading-relaxed font-normal">
                    Saksikan langsung penyaluran doa dan kebaikan Anda melalui akun resmi instagram Yayasan Himmatun Ayat Bandung
                </p>
            </div>

            <!-- Sisi Kanan: Instagram Pill Button -->
            <div class="relative z-10 shrink-0 w-full sm:w-auto">
                <a href="https://instagram.com/himmatunayatbandung" target="_blank" rel="noopener noreferrer" 
                   class="w-full sm:w-auto py-2.5 px-5 inline-flex items-center justify-center gap-2 rounded-full bg-[#BEE1D2] hover:bg-[#A8DCBF] text-[#1E4D3B] font-bold text-xs sm:text-sm transition shadow-xs">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                    <span>@himmatunayatbandung</span>
                </a>
            </div>

        </div>
    </section>

</div>
@endsection
