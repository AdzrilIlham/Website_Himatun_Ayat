@extends('layouts.user')

@section('content')
<div class="overflow-x-hidden">

    <!-- ========================================== -->
    <!-- 1. HERO SECTION                            -->
    <!-- ========================================== -->
    <section class="relative pt-2 sm:pt-4 lg:pt-6 pb-8 sm:pb-12 lg:pb-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 lg:gap-10 items-center">
                
                <!-- Kolom Foto: Di Mobile Tampil Pertama (order-1), di Desktop Tampil di Kanan (lg:order-2) -->
                <div class="order-1 lg:order-2 lg:col-span-5 relative flex justify-center">
                    <div class="relative w-full max-w-lg sm:max-w-xl lg:max-w-none">
                        
                        <!-- Warm Yellow Sunshine Ambient Aura behind the photo stack (Sesuai Desain Landing Page.png) -->
                        <div class="absolute -bottom-8 -right-8 w-60 sm:w-80 h-60 sm:h-80 bg-gradient-to-tl from-[#FDE047]/85 via-[#FEF08A]/60 to-transparent rounded-full blur-3xl pointer-events-none -z-10"></div>
                        <div class="absolute -top-10 -right-6 w-52 sm:w-72 h-52 sm:h-72 bg-gradient-to-bl from-[#FACC15]/45 via-[#FEF08A]/40 to-transparent rounded-full blur-2xl pointer-events-none -z-10"></div>

                        <!-- Layer Belakang 2 (Rotated 6 deg) -->
                        <div class="hidden sm:block absolute inset-0 bg-white rounded-2xl sm:rounded-3xl border-2 border-slate-200/90 shadow-md rotate-6 transform translate-x-2.5 translate-y-2.5 -z-20 overflow-hidden">
                            <div class="w-full h-full bg-[#1F4634]/10"></div>
                        </div>
                        
                        <!-- Layer Belakang 1 (Rotated 3 deg) -->
                        <div class="hidden sm:block absolute inset-0 bg-white rounded-2xl sm:rounded-3xl border-2 border-slate-200/90 shadow-lg rotate-3 transform translate-x-1.5 translate-y-1.5 -z-10 overflow-hidden">
                            <div class="w-full h-full bg-[#1F4634]/5"></div>
                        </div>
                        
                        <!-- Main Frame Depan -->
                        <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-xl border-4 sm:border-[5px] border-white bg-slate-100 aspect-[4/3]">
                            <img src="{{ asset('images/User/potopalingatas.png') }}" 
                                 alt="Anak Asuh Belajar Ceria" 
                                 class="w-full h-full object-cover">
                            
                            <!-- Soft subtle sunlit highlight over top-right of image -->
                            <div class="absolute top-0 right-0 w-36 sm:w-48 h-36 sm:h-48 bg-gradient-to-bl from-[#FEF08A]/25 via-transparent to-transparent pointer-events-none"></div>

                            <!-- Gradient Fade Overlay di Bagian Bawah Foto (Sesuai Desain Asli Landing Page.png) -->
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#143D2B]/95 via-[#1E4D3B]/75 to-transparent p-3.5 sm:p-5 pt-10 sm:pt-12 text-white">
                                <p class="text-[10px] sm:text-[11px] font-bold tracking-wider text-[#FCD34D] uppercase">
                                    RUANG BELAJAR CERIA • ASRAMA BANDUNG
                                </p>
                                <p class="text-xs sm:text-[13px] font-medium text-white/95 mt-1 leading-snug">
                                    "Menumbuhkan tunas generasi cerdas, mandiri, dan berakhlakul karimah."
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Kolom Teks: Di Mobile Tampil di Bawah Foto (order-2), di Desktop Tampil di Kiri (lg:order-1) -->
                <div class="order-2 lg:order-1 lg:col-span-7 space-y-4 sm:space-y-5">
                    <h1 class="text-2xl sm:text-4xl lg:text-[2.6rem] xl:text-[2.9rem] font-extrabold text-slate-800 leading-[1.16] tracking-tight">
                        Bersama <span class="text-[#2D6A4F]">Mengukir</span><br>
                        <span class="text-[#2D6A4F]">Senyum</span> dan <span class="text-[#E5A800]">Masa Depan</span><br>
                        Anak Yatim dan Dhuafa
                    </h1>

                    <p class="text-xs sm:text-sm lg:text-[15px] text-slate-600 leading-relaxed max-w-xl font-normal">
                        Membangun masa depan generasi yatim, piatu, dan dhuafa Kota Bandung berlandaskan tata kelola amanah, legitimasi negara yang transparan, serta pembinaan akhlak karimah yang berkesinambungan.
                    </p>

                    <div class="pt-1">
                        <a href="#paket-donasi" class="py-2.5 px-6 sm:py-3 sm:px-7 inline-flex items-center gap-x-2 text-xs sm:text-sm font-semibold rounded-full border border-transparent bg-[#2D6A4F] text-white hover:bg-[#235843] focus:outline-hidden transition shadow-sm shadow-emerald-900/15">
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
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-1 sm:-mt-2 relative z-20">
        <div class="flex items-center gap-2.5 sm:gap-3 overflow-x-auto no-scrollbar py-2 sm:justify-between">
            
            <!-- Donor 1 -->
            <div class="shrink-0 bg-white/95 backdrop-blur-sm border border-slate-200/80 rounded-xl py-1.5 px-3 flex items-center gap-2 shadow-2xs hover:shadow-xs transition">
                <div class="w-7 h-7 rounded-full bg-[#E4F1EB] flex items-center justify-center shrink-0 overflow-hidden">
                    <img src="{{ asset('images/User/profil.png') }}" alt="Avatar" class="w-3.5 h-3.5 object-contain">
                </div>
                <div>
                    <p class="font-bold text-[11px] sm:text-xs text-slate-800 leading-tight">Hamba Allah</p>
                    <p class="text-[10px] sm:text-[11px] text-slate-500 font-medium">Donasi Rp50.000,00 • 2 menit lalu</p>
                </div>
            </div>

            <!-- Donor 2 -->
            <div class="shrink-0 bg-white/95 backdrop-blur-sm border border-slate-200/80 rounded-xl py-1.5 px-3 flex items-center gap-2 shadow-2xs hover:shadow-xs transition">
                <div class="w-7 h-7 rounded-full bg-[#E4F1EB] flex items-center justify-center shrink-0 overflow-hidden">
                    <img src="{{ asset('images/User/profil.png') }}" alt="Avatar" class="w-3.5 h-3.5 object-contain">
                </div>
                <div>
                    <p class="font-bold text-[11px] sm:text-xs text-slate-800 leading-tight">Febriansyah Nugraha</p>
                    <p class="text-[10px] sm:text-[11px] text-slate-500 font-medium">Donasi Rp70.000,00 • 15 menit lalu</p>
                </div>
            </div>

            <!-- Donor 3 -->
            <div class="shrink-0 bg-white/95 backdrop-blur-sm border border-slate-200/80 rounded-xl py-1.5 px-3 flex items-center gap-2 shadow-2xs hover:shadow-xs transition">
                <div class="w-7 h-7 rounded-full bg-[#E4F1EB] flex items-center justify-center shrink-0 overflow-hidden">
                    <img src="{{ asset('images/User/profil.png') }}" alt="Avatar" class="w-3.5 h-3.5 object-contain">
                </div>
                <div>
                    <p class="font-bold text-[11px] sm:text-xs text-slate-800 leading-tight">Muhammad Ihsan Ansori</p>
                    <p class="text-[10px] sm:text-[11px] text-slate-500 font-medium">Donasi Rp10.000,00 • 22 menit lalu</p>
                </div>
            </div>

            <!-- Donor 4 -->
            <div class="shrink-0 bg-white/95 backdrop-blur-sm border border-slate-200/80 rounded-xl py-1.5 px-3 flex items-center gap-2 shadow-2xs hover:shadow-xs transition">
                <div class="w-7 h-7 rounded-full bg-[#E4F1EB] flex items-center justify-center shrink-0 overflow-hidden">
                    <img src="{{ asset('images/User/profil.png') }}" alt="Avatar" class="w-3.5 h-3.5 object-contain">
                </div>
                <div>
                    <p class="font-bold text-[11px] sm:text-xs text-slate-800 leading-tight">Adzril Ilham</p>
                    <p class="text-[10px] sm:text-[11px] text-slate-500 font-medium">Donasi Rp5.000,00 • 27 menit lalu</p>
                </div>
            </div>

        </div>
    </section>


    <!-- ========================================== -->
    <!-- 3. STATISTICS / IMPACT SECTION             -->
    <!-- ========================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 sm:mt-14">
        <!-- Dark Green Watermarked Container -->
        <div class="relative rounded-2xl sm:rounded-3xl bg-[#235843] p-5 sm:p-7 lg:p-8 text-white overflow-hidden shadow-lg">
            
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5 relative z-10">
                
                <!-- Card 1 -->
                <div class="bg-[#E4F1EB] rounded-xl sm:rounded-2xl p-4 sm:p-5 text-slate-900 flex flex-col justify-between shadow-xs transition hover:translate-y-[-2px] duration-200">
                    <div>
                        <div class="flex items-center justify-between">
                            <div class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center p-1.5 shadow-2xs">
                                <img src="{{ asset('images/User/Icons.png') }}" alt="Icon Donasi" class="w-5 h-5 object-contain">
                            </div>
                            <span class="bg-[#FDE68A] text-[#92400E] text-[10px] sm:text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                                Akumulasi 2025
                            </span>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                                Rp. 400 Juta+
                            </h3>
                            <p class="font-bold text-xs sm:text-[13px] text-slate-800 mt-1">
                                Total Dana Amanah Disalurkan
                            </p>
                        </div>
                    </div>
                    <p class="text-[11px] sm:text-xs text-slate-600 mt-2.5 leading-relaxed">
                        Pendayagunaan zakat, infaq, sedekah, dan wakaf yang disalurkan tepat sasaran kepada mustahik.
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
                            <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                                250+
                            </h3>
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
                            <h3 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                                250+
                            </h3>
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
    <section id="paket-donasi" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 sm:mt-16">
        
        <!-- Header Section -->
        <div class="space-y-1">
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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-7 mt-6 sm:mt-7 items-stretch">
            
            <!-- Kolom Kiri: Program Utama Card -->
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/90 p-4 sm:p-6 shadow-xs flex flex-col justify-between h-full">
                <div>
                    <!-- Image Card with Tag -->
                    <div class="relative rounded-xl sm:rounded-2xl overflow-hidden aspect-[16/9] bg-slate-100">
                        <img src="{{ asset('images/User/Renovasi Ruang Belajar.png') }}" 
                             alt="Renovasi Ruang Belajar" 
                             class="w-full h-full object-cover">
                        <span class="absolute top-2.5 left-2.5 sm:top-3 sm:left-3 bg-[#1E3A2F]/85 backdrop-blur-xs text-white text-[10px] sm:text-[11px] font-bold px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full shadow-xs">
                            Program Utama
                        </span>
                    </div>

                    <!-- Title & Donatur -->
                    <div class="mt-3.5 sm:mt-4 flex items-center justify-between gap-2 min-h-[1.75rem]">
                        <h3 class="font-bold text-sm sm:text-base lg:text-lg text-slate-900">
                            Renovasi Ruang Belajar
                        </h3>
                        <div class="flex items-center gap-1 text-[11px] sm:text-xs text-slate-500 font-medium shrink-0">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                            <span>215 Donatur</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="text-xs text-slate-600 mt-1.5 sm:mt-2 leading-relaxed">
                        Menyediakan ruang mukim aman, masjid asrama, dan 6 ruang belajar kondusif bagi 65 santri yatim dhuafa penghafal Al-Qur'an agar bebas dari ancaman relokasi dan memiliki sarana ibadah yang layak.
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
                            <span class="text-[#2D6A4F] font-bold">62%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 mt-1.5 overflow-hidden">
                            <div class="bg-[#2D6A4F] h-2 rounded-full w-[62%] transition-all duration-500"></div>
                        </div>

                        <div class="flex items-center justify-between mt-2.5 sm:mt-3 text-xs">
                            <div>
                                <span class="text-[10px] sm:text-[11px] text-slate-400 block font-medium">Terkumpul</span>
                                <span class="text-sm sm:text-base font-extrabold text-slate-900">Rp 52.700.000</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] sm:text-[11px] text-slate-400 block font-medium">Target Kebutuhan</span>
                                <span class="text-xs sm:text-sm font-bold text-slate-700">Rp 85.000.000</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Button -->
                <div class="mt-5 sm:mt-6 flex justify-center">
                    <a href="#paket-donasi" class="py-2.5 px-6 sm:py-3 sm:px-8 inline-flex items-center justify-center gap-x-2 text-xs sm:text-sm font-semibold rounded-full border border-transparent bg-[#2D6A4F] text-white hover:bg-[#235843] focus:outline-hidden transition shadow-xs w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                        <span>Donasi Sekarang</span>
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan: Paket Sedekah Kilat (Fixed Sizing Card) -->
            <div id="paket-sedekah" class="flex flex-col justify-between h-full" x-data="{
                activeSlide: 0,
                slides: [
                    {
                        title: 'Paket Nutrisi & Pangan Bergizi Anak',
                        image: '{{ asset('images/User/Paket SedekahKilat.png') }}',
                        donatur: '215 Donatur',
                        desc: 'Penyediaan pasokan susu formula, madu, telur, dan sembako berkualitas untuk 120 santri asrama dan...'
                    },
                    {
                        title: 'Paket Beasiswa Pendidikan Yatim',
                        image: '{{ asset('images/User/WisudaKhotmillQuran.png') }}',
                        donatur: '184 Donatur',
                        desc: 'Dukungan perlengkapan sekolah, seragam baru, tas, dan SPP santri yatim dhuafa berprestasi asrama.'
                    },
                    {
                        title: 'Paket Fasilitas Tahfidz & Quran',
                        image: '{{ asset('images/User/potopalingatas.png') }}',
                        donatur: '342 Donatur',
                        desc: 'Fasilitas mushaf hafalan Quran, sarana meja asrama, dan bimbingan tahfidz intensif berkelanjutan.'
                    }
                ],
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
                                 :alt="slides[activeSlide].title" 
                                 class="w-full h-full object-cover transition-opacity duration-300">
                        </div>

                        <!-- Title & Donatur with Fixed Height Row -->
                        <div class="mt-3.5 sm:mt-4 flex items-center justify-between gap-2 min-h-[1.75rem]">
                            <h3 class="font-bold text-sm sm:text-base text-slate-900 truncate" x-text="slides[activeSlide].title">
                                Paket Nutrisi & Pangan Bergizi Anak
                            </h3>
                            <div class="flex items-center gap-1 text-[11px] sm:text-xs text-slate-500 font-medium shrink-0">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                                <span x-text="slides[activeSlide].donatur">215 Donatur</span>
                            </div>
                        </div>

                        <!-- Description with Fixed Height (h-10 sm:h-12 overflow-hidden) to prevent any layout shifts -->
                        <div class="h-10 sm:h-12 mt-1.5 sm:mt-2 overflow-hidden">
                            <p class="text-xs text-slate-600 leading-relaxed line-clamp-2" x-text="slides[activeSlide].desc">
                                Penyediaan pasokan susu formula, madu, telur, dan sembako berkualitas untuk 120 santri asrama dan...
                            </p>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="mt-5 sm:mt-6 flex justify-center">
                        <a href="#paket-sedekah" class="py-2.5 px-6 sm:py-3 sm:px-8 inline-flex items-center justify-center gap-x-2 text-xs sm:text-sm font-semibold rounded-full border border-transparent bg-[#2D6A4F] text-white hover:bg-[#235843] focus:outline-hidden transition shadow-xs w-full sm:w-auto">
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

        </div>
    </section>


    <!-- ========================================== -->
    <!-- 5. LAPORAN AUDIT & TRANSPARANSI            -->
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
                <div class="lg:col-span-5 space-y-3 sm:space-y-4">
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
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 sm:gap-3.5">
                        
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
    <!-- 6. KABAR & AKTIVITAS TERBARU YAYASAN       -->
    <!-- ========================================== -->
    <section id="kabar-berita" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 sm:mt-16" x-data="{
        selectedCategory: 'all'
    }">
        
        <!-- Header -->
        <div class="space-y-1">
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
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1">
                <button type="button" 
                        @click="selectedCategory = 'all'" 
                        :class="selectedCategory === 'all' ? 'py-1.5 px-3.5 rounded-lg bg-[#2D6A4F] text-white text-xs font-semibold shadow-xs' : 'py-1.5 px-3.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-medium transition'">
                    Semua Program
                </button>
                <button type="button" 
                        @click="selectedCategory = 'pendidikan'" 
                        :class="selectedCategory === 'pendidikan' ? 'py-1.5 px-3.5 rounded-lg bg-[#2D6A4F] text-white text-xs font-semibold shadow-xs' : 'py-1.5 px-3.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-medium transition'">
                    Pendidikan
                </button>
                <button type="button" 
                        @click="selectedCategory = 'tahfidz'" 
                        :class="selectedCategory === 'tahfidz' ? 'py-1.5 px-3.5 rounded-lg bg-[#2D6A4F] text-white text-xs font-semibold shadow-xs' : 'py-1.5 px-3.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-medium transition'">
                    Tahfidz Quran
                </button>
                <button type="button" 
                        @click="selectedCategory = 'kegiatan'" 
                        :class="selectedCategory === 'kegiatan' ? 'py-1.5 px-3.5 rounded-lg bg-[#2D6A4F] text-white text-xs font-semibold shadow-xs' : 'py-1.5 px-3.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-medium transition'">
                    Kegiatan Luar Ruangan
                </button>
            </div>

            <!-- View All Link -->
            <a href="{{ route('berita-kegiatan.index') }}" class="text-xs font-bold text-slate-900 hover:text-[#2D6A4F] inline-flex items-center gap-1 transition">
                <span>Lihat Semua Kabar Berita</span>
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>

        <!-- 4 News Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5 mt-5">
            
            <!-- News Card 1 -->
            <div x-show="selectedCategory === 'all' || selectedCategory === 'pendidikan'" 
                 class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/90 p-3 sm:p-3.5 flex flex-col justify-between shadow-2xs hover:shadow-md transition duration-200">
                <div>
                    <!-- Image with Badge -->
                    <div class="relative rounded-lg sm:rounded-xl overflow-hidden aspect-[4/3] bg-slate-100">
                        <img src="{{ asset('images/User/WisudaKhotmillQuran.png') }}" 
                             alt="Penyaluran Perlengkapan Sekolah" 
                             class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 bg-[#1E3A2F]/85 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            Pendidikan
                        </span>
                    </div>

                    <!-- Meta: Date & Location -->
                    <div class="mt-2.5 flex items-center gap-2.5 text-[10px] sm:text-[11px] text-slate-500 font-medium">
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h14.25A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                            </svg>
                            11 Okt 2025
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            Bandung
                        </span>
                    </div>

                    <!-- Title -->
                    <h4 class="font-bold text-xs sm:text-[13px] text-slate-900 mt-1.5 line-clamp-1">
                        Penyaluran Perlengkapan Sekolah
                    </h4>

                    <!-- Subtitle -->
                    <p class="text-[11px] sm:text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2 sm:line-clamp-3">
                        Sebanyak 85 anak asuh tingkat SD dan SMP menerima paket seragam baru, tas, sepatu, dan buku tulis untuk menyambut tahun ajaran baru
                    </p>
                </div>

                <!-- Button -->
                <div class="mt-3">
                    <button type="button" class="w-full py-1.5 px-2.5 rounded-lg bg-[#E4F1EB] hover:bg-[#D4EAE0] text-slate-800 text-[11px] sm:text-xs font-bold inline-flex items-center justify-center gap-1.5 transition">
                        <img src="{{ asset('images/User/lembaran.png') }}" alt="File" class="w-3 h-3 object-contain">
                        <span>Lihat Selengkapnya</span>
                    </button>
                </div>
            </div>

            <!-- News Card 2 -->
            <div x-show="selectedCategory === 'all' || selectedCategory === 'tahfidz'" 
                 class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/90 p-3 sm:p-3.5 flex flex-col justify-between shadow-2xs hover:shadow-md transition duration-200">
                <div>
                    <!-- Image with Badge -->
                    <div class="relative rounded-lg sm:rounded-xl overflow-hidden aspect-[4/3] bg-slate-100">
                        <img src="{{ asset('images/User/WisudaKhotmilQuran.png') }}" 
                             alt="Wisuda Khotmil Quran" 
                             class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 bg-[#1E3A2F]/85 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            Tahfidz Quran
                        </span>
                    </div>

                    <!-- Meta: Date & Location -->
                    <div class="mt-2.5 flex items-center gap-2.5 text-[10px] sm:text-[11px] text-slate-500 font-medium">
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h14.25A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                            </svg>
                            11 Okt 2025
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            Bandung
                        </span>
                    </div>

                    <!-- Title -->
                    <h4 class="font-bold text-xs sm:text-[13px] text-slate-900 mt-1.5 line-clamp-1">
                        Wisuda Khotmil Quran
                    </h4>

                    <!-- Subtitle -->
                    <p class="text-[11px] sm:text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2 sm:line-clamp-3">
                        Sebanyak 85 anak asuh tingkat SD dan SMP menerima paket seragam baru, tas, sepatu, dan buku tulis untuk menyambut tahun ajaran baru
                    </p>
                </div>

                <!-- Button -->
                <div class="mt-3">
                    <button type="button" class="w-full py-1.5 px-2.5 rounded-lg bg-[#E4F1EB] hover:bg-[#D4EAE0] text-slate-800 text-[11px] sm:text-xs font-bold inline-flex items-center justify-center gap-1.5 transition">
                        <img src="{{ asset('images/User/lembaran.png') }}" alt="File" class="w-3 h-3 object-contain">
                        <span>Lihat Selengkapnya</span>
                    </button>
                </div>
            </div>

            <!-- News Card 3 -->
            <div x-show="selectedCategory === 'all' || selectedCategory === 'kegiatan'" 
                 class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/90 p-3 sm:p-3.5 flex flex-col justify-between shadow-2xs hover:shadow-md transition duration-200">
                <div>
                    <!-- Image with Badge -->
                    <div class="relative rounded-lg sm:rounded-xl overflow-hidden aspect-[4/3] bg-slate-100">
                        <img src="{{ asset('images/User/Lomba Balap Makan Kerupuk.png') }}" 
                             alt="Lomba Balap Makan Kerupuk" 
                             class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 bg-[#1E3A2F]/85 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            Kegiatan Luar Ruangan
                        </span>
                    </div>

                    <!-- Meta: Date & Location -->
                    <div class="mt-2.5 flex items-center gap-2.5 text-[10px] sm:text-[11px] text-slate-500 font-medium">
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h14.25A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                            </svg>
                            11 Okt 2025
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            Bandung
                        </span>
                    </div>

                    <!-- Title -->
                    <h4 class="font-bold text-xs sm:text-[13px] text-slate-900 mt-1.5 line-clamp-1">
                        Lomba Balap Makan Kerupuk
                    </h4>

                    <!-- Subtitle -->
                    <p class="text-[11px] sm:text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2 sm:line-clamp-3">
                        Sebanyak 85 anak asuh tingkat SD dan SMP menerima paket seragam baru, tas, sepatu, dan buku tulis untuk menyambut tahun ajaran baru
                    </p>
                </div>

                <!-- Button -->
                <div class="mt-3">
                    <button type="button" class="w-full py-1.5 px-2.5 rounded-lg bg-[#E4F1EB] hover:bg-[#D4EAE0] text-slate-800 text-[11px] sm:text-xs font-bold inline-flex items-center justify-center gap-1.5 transition">
                        <img src="{{ asset('images/User/lembaran.png') }}" alt="File" class="w-3 h-3 object-contain">
                        <span>Lihat Selengkapnya</span>
                    </button>
                </div>
            </div>

            <!-- News Card 4 -->
            <div x-show="selectedCategory === 'all' || selectedCategory === 'tahfidz'" 
                 class="bg-white rounded-xl sm:rounded-2xl border border-slate-200/90 p-3 sm:p-3.5 flex flex-col justify-between shadow-2xs hover:shadow-md transition duration-200">
                <div>
                    <!-- Image with Badge -->
                    <div class="relative rounded-lg sm:rounded-xl overflow-hidden aspect-[4/3] bg-slate-100">
                        <img src="{{ asset('images/User/WisudaKhotmillQuran.png') }}" 
                             alt="Wisuda Khotmil Quran" 
                             class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 bg-[#1E3A2F]/85 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            Tahfidz Quran
                        </span>
                    </div>

                    <!-- Meta: Date & Location -->
                    <div class="mt-2.5 flex items-center gap-2.5 text-[10px] sm:text-[11px] text-slate-500 font-medium">
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h14.25A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                            </svg>
                            11 Okt 2025
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            Bandung
                        </span>
                    </div>

                    <!-- Title -->
                    <h4 class="font-bold text-xs sm:text-[13px] text-slate-900 mt-1.5 line-clamp-1">
                        Wisuda Khotmil Quran
                    </h4>

                    <!-- Subtitle -->
                    <p class="text-[11px] sm:text-xs text-slate-500 mt-1 leading-relaxed line-clamp-2 sm:line-clamp-3">
                        Sebanyak 85 anak asuh tingkat SD dan SMP menerima paket seragam baru, tas, sepatu, dan buku tulis untuk menyambut tahun ajaran baru
                    </p>
                </div>

                <!-- Button -->
                <div class="mt-3">
                    <button type="button" class="w-full py-1.5 px-2.5 rounded-lg bg-[#E4F1EB] hover:bg-[#D4EAE0] text-slate-800 text-[11px] sm:text-xs font-bold inline-flex items-center justify-center gap-1.5 transition">
                        <img src="{{ asset('images/User/lembaran.png') }}" alt="File" class="w-3 h-3 object-contain">
                        <span>Lihat Selengkapnya</span>
                    </button>
                </div>
            </div>

        </div>
    </section>


    <!-- ========================================== -->
    <!-- 7. INSTAGRAM LIVE STORY BANNER             -->
    <!-- ========================================== -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 sm:mt-16">
        <div class="relative rounded-xl sm:rounded-2xl bg-[#235843] p-5 sm:p-7 text-white flex flex-col md:flex-row items-start md:items-center justify-between gap-5 sm:gap-6 shadow-md overflow-hidden">
            
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
