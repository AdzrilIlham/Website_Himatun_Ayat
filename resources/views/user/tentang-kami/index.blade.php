@extends('layouts.user')

@section('content')
<div class="overflow-x-hidden" data-page-animations>

    <!-- ====================================================================== -->
    <!-- 1. HERO SECTION: PROFIL LEMBAGA & LOGO RESMI                          -->
    <!-- ====================================================================== -->
    <section class="relative pt-4 sm:pt-8 lg:pt-12 pb-10 sm:pb-16 lg:pb-20" data-animate-hero>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 lg:gap-14 items-center">
                
                <!-- Tampilan Mobile: Gambar mengayomi.png ditaruh di paling atas sebelum teks (Sesuai Permintaan User) -->
                <div class="block lg:hidden w-full" data-hero-media>
                    <div class="relative rounded-3xl overflow-hidden shadow-xl border-4 border-white bg-slate-100 aspect-[4/3] group"
                         data-tilt-3d
                         data-tilt-glare
                         data-tilt-max="8">
                        <!-- Main Image -->
                        <img src="{{ asset('images/Asset/mengayomi.png') }}" 
                             alt="Mengayomi Anak Yatim dan Dhuafa" 
                             class="w-full h-full object-cover">
                        
                        <!-- Soft Ambient Dark Green Gradient & Blur Overlay at Bottom (Sesuai Prototype Tentang Kami.png) -->
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#1C4E3D]/95 via-[#1C4E3D]/65 via-60% to-transparent backdrop-blur-[2px] p-4 sm:p-5 pt-12 flex flex-col justify-end items-start pointer-events-none"
                             data-tilt-layer="22">
                            <span class="inline-block bg-[#F9BC38] text-slate-900 font-extrabold px-2.5 py-0.5 rounded-md text-[10px] sm:text-xs tracking-wide shadow-xs mb-1">
                                SEJAK 2006
                            </span>
                            <span class="text-white font-bold text-sm sm:text-base tracking-tight drop-shadow-sm leading-snug">
                                Mengayomi 1.450+ Anak Yatim &amp; Dhuafa
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Kolom Teks: Hero Title & Description -->
                <div class="lg:col-span-7 space-y-4 sm:space-y-6 text-left">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[3.25rem] font-extrabold text-slate-900 leading-[1.15] tracking-tight" data-hero-heading>
                        Profil Lembaga &amp; Legalitas Hukum Resmi Yayasan Himmatun Ayat Bandung
                    </h1>

                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed font-normal max-w-2xl" data-hero-description>
                        Membangun masa depan generasi yatim, piatu, dan dhuafa Kota Bandung berlandaskan tata kelola amanah, legitimasi negara yang transparan, serta pembinaan akhlak karimah yang berkesinambungan.
                    </p>
                </div>

                <!-- Kolom Lambang / Logo Yayasan (Hanya Tampil di Desktop, di Mobile disembunyikan agar visual mengayomi.png lebih menonjol) -->
                <div class="hidden lg:flex lg:col-span-5 justify-center items-center relative" data-hero-media data-3d-hero-container>
                    <!-- Warm Yellow & Mint Aura Behind the Logo -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-[#B7E4C7]/40 via-[#FDE68A]/50 to-transparent rounded-full blur-3xl pointer-events-none -z-10 scale-110" data-3d-hero-aura></div>
                    
                    <div class="relative group cursor-pointer" 
                         data-3d-hero-target 
                         data-3d-float 
                         data-tilt-3d 
                         data-tilt-glare 
                         data-tilt-max="14" 
                         data-tilt-scale="1.04">
                        <img src="{{ asset('images/Asset/logo_himmatun.png') }}" 
                             alt="Logo Resmi Yayasan Himmatun Ayat" 
                             class="w-72 lg:w-96 max-w-full h-auto object-contain drop-shadow-2xl transition-transform duration-300"
                             data-tilt-layer="30">
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ====================================================================== -->
    <!-- 2. SEJARAH SINGKAT & KOMITMEN                                          -->
    <!-- ====================================================================== -->
    <section class="relative py-10 sm:py-14 lg:py-18 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 lg:gap-16 items-center">
                
                <!-- Kolom Kiri: Foto Komitmen & Badge Sejak 2006 (Di Desktop tampil di sini, di Mobile sudah tampil di atas hero agar tidak duplikat) -->
                <div class="hidden lg:block lg:col-span-6 relative" data-animate="fade-up-3d" data-animate-distance="28">
                    <div class="relative rounded-3xl overflow-hidden shadow-xl border-4 sm:border-[6px] border-white bg-slate-100 aspect-[4/3] group cursor-pointer"
                         data-tilt-3d
                         data-tilt-glare
                         data-tilt-max="9"
                         data-tilt-scale="1.02">
                        <!-- Main Image -->
                        <img src="{{ asset('images/Asset/mengayomi.png') }}" 
                             alt="Mengayomi Anak Yatim dan Dhuafa" 
                             class="w-full h-full object-cover">
                        
                        <!-- Soft Ambient Dark Green Gradient & Blur Overlay at Bottom (Sesuai Prototype Tentang Kami.png) -->
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#1C4E3D]/95 via-[#1C4E3D]/65 via-60% to-transparent backdrop-blur-[2px] p-5 sm:p-6 pt-14 flex flex-col justify-end items-start pointer-events-none"
                             data-tilt-layer="24">
                            <span class="inline-block bg-[#F9BC38] text-slate-900 font-extrabold px-2.5 py-0.5 rounded-md text-[11px] sm:text-xs tracking-wide shadow-xs mb-1.5">
                                SEJAK 2006
                            </span>
                            <span class="text-white font-bold text-base sm:text-lg md:text-xl tracking-tight drop-shadow-sm leading-snug">
                                Mengayomi 1.450+ Anak Yatim &amp; Dhuafa
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Uraian Sejarah & 2 Metric Cards (Muncul Satu per Satu Saat di-Scroll) -->
                <div class="lg:col-span-6 space-y-4 sm:space-y-6"
                     data-animate-group="fade-up"
                     data-animate-distance="22"
                     data-animate-stagger="0.14"
                     data-animate-start="top 85%">
                    <!-- Badge Section -->
                    <x-tentang-kami.section-badge icon="history">
                        SEJARAH SINGKAT &amp; KOMITMEN
                    </x-tentang-kami.section-badge>

                    <!-- Title -->
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Dedikasi Luhur Memuliakan Anak Yatim Sejak 2005
                    </h2>

                    <!-- Paragraph 1 -->
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                        Yayasan Himmatun Ayat Bandung didirikan atas dasar keprihatinan mendalam terhadap nasib anak-anak yatim dan dhuafa di wilayah Bandung Raya yang terancam putus sekolah serta kehilangan figur pembimbing karakter. Berdiri secara resmi pada tahun 2005, yayasan berkomitmen menyediakan ekosistem pengasuhan berbasis kekeluargaan (<em>family-based care</em>).
                    </p>

                    <!-- Paragraph 2 -->
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                        Kami meyakini bahwa menyantuni anak yatim tidak cukup hanya dengan bantuan logistik sesaat. Pendekatan kami bertumpu pada pengasuhan holistik: penanaman akidah yang kokoh, akses pendidikan formal hingga jenjang perguruan tinggi, tahfidz Al-Qur'an terstruktur, serta pembekalan vokasional agar mereka mandiri saat menginjak usia dewasa.
                    </p>

                    <!-- 2 Stat Metric Cards Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2"
                         data-animate-group="fade-up-3d"
                         data-animate-distance="20"
                         data-animate-stagger="0.15"
                         data-animate-start="top 88%">
                        <x-tentang-kami.stat-card 
                            value="100%" 
                            label="Amanah Penyaluran Donatur" 
                            data-tilt-3d
                            data-tilt-max="10"
                        />
                        <x-tentang-kami.stat-card 
                            value="2 Asrama" 
                            label="Cikutra Putra &amp; Cibiru Putri" 
                            data-tilt-3d
                            data-tilt-max="10"
                        />
                    </div>
                </div>

            </div>

        </div>
    </section>


    <!-- ====================================================================== -->
    <!-- 3. VISI & MISI PERJUANGAN LEMBAGA                                      -->
    <!-- ====================================================================== -->
    <section class="relative py-14 sm:py-20 lg:py-24 bg-slate-50/60 border-t border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header (Centered, Muncul Satu per Satu Saat di-Scroll) -->
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-14 space-y-3"
                 data-animate-group="fade-up"
                 data-animate-distance="22"
                 data-animate-stagger="0.12"
                 data-animate-start="top 85%">
                <x-tentang-kami.section-badge icon="direction">
                    ARAH &amp; LANDASAN GERAK
                </x-tentang-kami.section-badge>

                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Visi &amp; Misi Perjuangan Lembaga
                </h2>

                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-xl mx-auto">
                    Pilar fundamental dalam mewujudkan peradaban generasi yatim berilmu, mandiri, dan bermoral mulia.
                </p>
            </div>

            <!-- Two-Column Layout: Left (Visi Card) & Right (4 Misi Cards Grid) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-stretch">
                
                <!-- Left: Visi Utama Big Card (Sesuai Prototype Asli Tentang Kami.png) -->
                <div class="lg:col-span-5 flex" data-animate="fade-up-3d" data-animate-distance="28" data-animate-start="top 82%">
                    <div class="relative w-full rounded-3xl bg-[#1C4E3D] text-white p-6 sm:p-8 lg:p-9 flex flex-col justify-between shadow-xl overflow-hidden border border-[#235845] transition-shadow duration-300 hover:shadow-2xl hover:shadow-emerald-950/30 cursor-pointer"
                         data-tilt-3d
                         data-tilt-glare
                         data-tilt-max="10"
                         data-tilt-scale="1.02">
                        
                        <!-- Ambient Glow Accent -->
                        <div class="absolute -top-16 -right-16 w-56 h-56 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none" data-tilt-layer="-16"></div>

                        <!-- Top Content: Badge, Quote & Paragraph (Lebar dibatasi agar teks tidak menutupi ilustrasi Pak Yusuf) -->
                        <div class="relative z-10 max-w-[66%] sm:max-w-[68%]" data-tilt-layer="22">
                            <span class="inline-block px-3.5 py-1 rounded-full text-2xs font-extrabold uppercase tracking-wider bg-[#295A48] border border-emerald-500/30 text-emerald-200 shadow-2xs">
                                VISI UTAMA
                            </span>

                            <blockquote class="text-lg sm:text-xl lg:text-[1.4rem] font-bold leading-snug text-white mt-4 sm:mt-5 tracking-tight">
                                “Menjadi lembaga filantropi dan pengasuhan anak yatim terdepan, amanah, profesional, dan berakhlak mulia di Jawa Barat.”
                            </blockquote>

                            <p class="text-xs sm:text-[13px] text-emerald-100/80 leading-relaxed mt-3 sm:mt-4 font-normal">
                                Sebuah komitmen jangka panjang melahirkan generasi dhuafa yang bertransformasi dari penerima manfaat (<em>mustahik</em>) menjadi insan mandiri pemberi manfaat (<em>muzakki</em>).
                            </p>
                        </div>

                        <!-- Bottom Left: Pak Yusuf Name & Role -->
                        <div class="relative z-10 max-w-[66%] sm:max-w-[68%] pt-8 sm:pt-10" data-tilt-layer="20">
                            <h4 class="text-base sm:text-lg font-bold text-white tracking-tight">
                                Pak Yusuf
                            </h4>
                            <p class="text-xs text-emerald-200/85 font-medium mt-0.5">
                                Ketua Yayasan Himmatun Ayat Bandung
                            </p>
                        </div>

                        <!-- Pak Yusuf Illustration (Berdiri tegak di kanan bawah kartu) -->
                        <div class="absolute bottom-0 right-0 w-[42%] sm:w-[40%] max-w-[210px] pointer-events-none z-0" data-tilt-layer="36">
                            <img src="{{ asset('images/Asset/YusufHamdani.png') }}" 
                                 alt="Pak Yusuf Hamdani" 
                                 class="w-full h-auto object-contain object-bottom drop-shadow-2xl translate-y-1">
                        </div>

                    </div>
                </div>

                <!-- Right: 4 Misi Cards Grid (Muncul SATU PER SATU dengan Stagger 0.18s!) -->
                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5"
                     data-animate-group="fade-up-3d"
                     data-animate-distance="24"
                     data-animate-stagger="0.18"
                     data-animate-start="top 82%">
                    
                    <!-- Misi 01 -->
                    <x-tentang-kami.misi-card 
                        number="01" 
                        title="Pendidikan Formal Unggul" 
                        description="Menjamin 100% anak asuh menyelesaikan wajib belajar 12 tahun hingga beasiswa penuh perguruan tinggi negeri maupun swasta terakreditasi." 
                        tag="Bebas Putus Sekolah" 
                        icon="education" 
                        data-tilt-3d
                        data-tilt-max="11"
                    />

                    <!-- Misi 02 -->
                    <x-tentang-kami.misi-card 
                        number="02" 
                        title="Pembinaan Karakter Tangguh" 
                        description="Membentuk jiwa kepemimpinan, kedisiplinan, integritas sosial, serta kesiapan mental melalui pendampingan psikologis berkala." 
                        tag="Konseling & Mentorship" 
                        icon="character" 
                        data-tilt-3d
                        data-tilt-max="11"
                    />

                    <!-- Misi 03 -->
                    <x-tentang-kami.misi-card 
                        number="03" 
                        title="Tahfidz & Akidah Islam" 
                        description="Menyelenggarakan kurikulum intensif tahsin, tahfidz Al-Qur'an juz 30, hafalan hadits pilihan, serta penanaman adab harian islami." 
                        tag="Sanad & Pembinaan Santri" 
                        icon="quran" 
                        data-tilt-3d
                        data-tilt-max="11"
                    />

                    <!-- Misi 04 -->
                    <x-tentang-kami.misi-card 
                        number="04" 
                        title="Kemandirian Ekonomi Santri" 
                        description="Memberikan kursus literasi digital, wirausaha kreatif, kejuruan teknik, serta inkubasi bisnis mini untuk alumni asrama." 
                        tag="Kesiapan Kerja Nyata" 
                        icon="business" 
                        data-tilt-3d
                        data-tilt-max="11"
                    />

                </div>

            </div>

        </div>
    </section>


    <!-- ====================================================================== -->
    <!-- 4. TATA KELOLA & STRUKTUR ORGANISASI                                   -->
    <!-- Menggunakan Shape-pengurus.png resmi, Icon-ketua.png, Icon-pengurus.png-->
    <!-- ====================================================================== -->
    <section class="relative py-16 sm:py-20 lg:py-24 bg-[#1C4E3D] text-white overflow-hidden">
        
        <!-- Watermark Pattern Crescent Moons (Sesuai Prototype Asli) -->
        <div class="absolute inset-0 opacity-[0.06] pointer-events-none" style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cpath fill='%23ffffff' d='M40 18a22 22 0 1 0 0 44 20 20 0 1 1 0-44z'/%3E%3C/svg%3E&quot;); background-size: 80px 80px;"></div>
        
        <!-- Ambient Radial Glows -->
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-emerald-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-emerald-700/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- DESKTOP TOP ROW: Left Card, Center Title, Right Card (Muncul Satu per Satu Saat di-Scroll) -->
            <div class="hidden lg:grid lg:grid-cols-12 gap-8 items-center mb-16"
                 data-animate-group="fade-up-3d"
                 data-animate-stagger="0.2"
                 data-animate-start="top 82%">
                
                <!-- Left Card: Yusuf Hamdani (01) -->
                <div class="lg:col-span-3" data-animate-item="tilt-right-3d" data-animate-distance="30">
                    <x-tentang-kami.pengurus-card 
                        number="01" 
                        name="Yusuf Hamdani" 
                        role="Ketua Yayasan" 
                        :image="asset('images/Asset/YusufHamdani.png')" 
                        icon="leader"
                        :featured="true"
                        data-tilt-3d
                        data-tilt-max="14"
                    />
                </div>

                <!-- Center: Title & Description -->
                <div class="lg:col-span-6 text-center space-y-3 px-4" data-animate-item="fade-up" data-animate-distance="22">
                    <x-tentang-kami.section-badge icon="organization" variant="dark">
                        TATA KELOLA &amp; STRUKTUR ORGANISASI
                    </x-tentang-kami.section-badge>

                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                        Amanah Figur Profesional &amp; Syariah
                    </h2>

                    <p class="text-sm text-emerald-100/85 leading-relaxed max-w-lg mx-auto font-normal">
                        Dikelola secara kolektif kolegial dengan pengawasan syariah ketat guna menjamin transparansi penyaluran dana umat dan kualitas pengasuhan.
                    </p>
                </div>

                <!-- Right Card: Euis Nuraeni (02) -->
                <div class="lg:col-span-3" data-animate-item="tilt-left-3d" data-animate-distance="30">
                    <x-tentang-kami.pengurus-card 
                        number="02" 
                        name="Euis Nuraeni" 
                        role="Pengurus" 
                        :image="asset('images/Asset/EuisNuraeni.png')" 
                        icon="manager"
                        :featured="true"
                        data-tilt-3d
                        data-tilt-max="14"
                    />
                </div>

            </div>

            <!-- TABLET & MOBILE HEADER (Tampil di atas grid pada layar < lg, Muncul Satu per Satu) -->
            <div class="lg:hidden text-center max-w-2xl mx-auto mb-10 space-y-3"
                 data-animate-group="fade-up"
                 data-animate-distance="22"
                 data-animate-stagger="0.12"
                 data-animate-start="top 85%">
                <x-tentang-kami.section-badge icon="organization" variant="dark">
                    TATA KELOLA &amp; STRUKTUR ORGANISASI
                </x-tentang-kami.section-badge>

                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                    Amanah Figur Profesional &amp; Syariah
                </h2>

                <p class="text-xs sm:text-sm text-emerald-100/85 leading-relaxed font-normal">
                    Dikelola secara kolektif kolegial dengan pengawasan syariah ketat guna menjamin transparansi penyaluran dana umat dan kualitas pengasuhan.
                </p>
            </div>

            <!-- BOTTOM GRID: 4 Pengurus Cards (Muncul SATU PER SATU dengan Stagger 0.18s!) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-6"
                 data-animate-group="fade-up-3d"
                 data-animate-distance="30"
                 data-animate-stagger="0.18"
                 data-animate-start="top 80%">
                
                <!-- Card 01: Yusuf Hamdani -->
                <x-tentang-kami.pengurus-card 
                    number="01" 
                    name="Yusuf Hamdani" 
                    role="Ketua Yayasan" 
                    :image="asset('images/Asset/YusufHamdani.png')" 
                    icon="leader" 
                    data-tilt-3d
                    data-tilt-max="14"
                />

                <!-- Card 02: Euis Nuraeni -->
                <x-tentang-kami.pengurus-card 
                    number="02" 
                    name="Euis Nuraeni" 
                    role="Pengurus" 
                    :image="asset('images/Asset/EuisNuraeni.png')" 
                    icon="manager" 
                    data-tilt-3d
                    data-tilt-max="14"
                />

                <!-- Card 03: Daslim -->
                <x-tentang-kami.pengurus-card 
                    number="03" 
                    name="Daslim" 
                    role="Pengurus" 
                    :image="asset('images/Asset/Daslim.png')" 
                    icon="manager" 
                    data-tilt-3d
                    data-tilt-max="14"
                />

                <!-- Card 04: Juhaeriyah -->
                <x-tentang-kami.pengurus-card 
                    number="04" 
                    name="Juhaeriyah" 
                    role="Pengurus" 
                    :image="asset('images/Asset/Juhaeriyah.png')" 
                    icon="book" 
                    data-tilt-3d
                    data-tilt-max="14"
                />

            </div>

        </div>
    </section>


    <!-- ====================================================================== -->
    <!-- 5. DOKUMEN HUKUM & LISENSI RESMI NEGARA                                -->
    <!-- Diperkaya dengan 3D Floating Seals, Trust Pillars, & Staggered Reveal  -->
    <!-- ====================================================================== -->
    <section class="relative py-14 sm:py-20 lg:py-24 bg-white overflow-hidden">
        
        <!-- Ambient Background 3D Glow Orbs -->
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"
             data-3d-float
             data-float-distance="14"
             data-float-duration="5"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-[#FDE68A]/30 rounded-full blur-3xl pointer-events-none"
             data-3d-float
             data-float-distance="10"
             data-float-duration="4.2"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Section Header (Muncul Satu per Satu Saat di-Scroll) -->
            <div class="max-w-3xl mb-8 sm:mb-10 space-y-3"
                 data-animate-group="fade-up"
                 data-animate-distance="24"
                 data-animate-stagger="0.12"
                 data-animate-start="top 85%">
                
                <x-tentang-kami.section-badge icon="legal">
                    LEGALITAS &amp; LEGAL STANDING LEMBAGA
                </x-tentang-kami.section-badge>

                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    Dokumen Hukum &amp; Lisensi Resmi Negara
                </h2>

                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                    Yayasan Himmatun Ayat Bandung beroperasi penuh di bawah payung hukum Republik Indonesia dan diaudit secara berkala oleh kementerian terkait guna menjamin transparansi serta kepatuhan syariah yang mutlak.
                </p>
            </div>

            <!-- 4 Interactive 3D Legal Trust Metric Pills (Muncul Satu per Satu!) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-10 sm:mb-14"
                 data-animate-group="fade-up-3d"
                 data-animate-distance="18"
                 data-animate-stagger="0.1"
                 data-animate-start="top 85%">
                
                <div class="rounded-2xl p-3.5 sm:p-4 bg-emerald-50/70 border border-emerald-200/80 shadow-2xs hover:shadow-md hover:border-emerald-300 transition-all duration-300 flex items-center gap-3 cursor-pointer group"
                     data-tilt-3d
                     data-tilt-max="9">
                    <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-xs" data-tilt-layer="20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.5M4.5 21V10.5M3 21h18M5.25 6h13.5" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-900 group-hover:text-emerald-900 leading-tight">Kemenkumham RI</p>
                        <p class="text-2xs text-slate-600 mt-0.5 leading-snug">Badan Hukum Sah Permanen</p>
                    </div>
                </div>

                <div class="rounded-2xl p-3.5 sm:p-4 bg-emerald-50/70 border border-emerald-200/80 shadow-2xs hover:shadow-md hover:border-emerald-300 transition-all duration-300 flex items-center gap-3 cursor-pointer group"
                     data-tilt-3d
                     data-tilt-max="9">
                    <div class="w-9 h-9 rounded-xl bg-[#1C4E3D] text-white flex items-center justify-center shrink-0 shadow-xs" data-tilt-layer="20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-900 group-hover:text-emerald-900 leading-tight">Terakreditasi A</p>
                        <p class="text-2xs text-slate-600 mt-0.5 leading-snug">Dinas Sosial Prov. Jabar</p>
                    </div>
                </div>

                <div class="rounded-2xl p-3.5 sm:p-4 bg-emerald-50/70 border border-emerald-200/80 shadow-2xs hover:shadow-md hover:border-emerald-300 transition-all duration-300 flex items-center gap-3 cursor-pointer group"
                     data-tilt-3d
                     data-tilt-max="9">
                    <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-xs" data-tilt-layer="20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-900 group-hover:text-emerald-900 leading-tight">Taat Pajak NPWP</p>
                        <p class="text-2xs text-slate-600 mt-0.5 leading-snug">KPP Pratama Cibeunying</p>
                    </div>
                </div>

                <div class="rounded-2xl p-3.5 sm:p-4 bg-emerald-50/70 border border-emerald-200/80 shadow-2xs hover:shadow-md hover:border-emerald-300 transition-all duration-300 flex items-center gap-3 cursor-pointer group"
                     data-tilt-3d
                     data-tilt-max="9">
                    <div class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center shrink-0 shadow-xs" data-tilt-layer="20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.09 1.976 1.053 1.976 2.188V8.25m-12 0v11.25c0 1.135.845 2.098 1.976 2.188 1.18.094 2.37.142 3.564.142s2.384-.048 3.564-.142c1.131-.09 1.976-1.053 1.976-2.188V8.25" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-900 group-hover:text-emerald-900 leading-tight">Audit Syariah & WTP</p>
                        <p class="text-2xs text-slate-600 mt-0.5 leading-snug">100% Bebas Pungutan</p>
                    </div>
                </div>

            </div>

            <!-- Cards Grid (Muncul SATU PER SATU dengan Stagger 0.22s!) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 items-start"
                 data-animate-group="fade-up-3d"
                 data-animate-distance="28"
                 data-animate-stagger="0.22"
                 data-animate-start="top 82%">
                
                <!-- Dokumen 1: LKSA -->
                <x-tentang-kami.dokumen-hukum-card 
                    icon="shield"
                    badgeText="Terakreditasi A"
                    badgeVariant="success"
                    title="Izin Operasional Lembaga Kesejahteraan Sosial Anak (LKSA)"
                    nomor="No. Registrasi: 460/182-Dinsos/2021"
                    deskripsi="Surat Keputusan Kepala Dinas Sosial Provinsi Jawa Barat perihal Izin Operasional Panti Sosial Asuhan Anak Yatim &amp; Dhuafa Berkelanjutan."
                    :image="asset('images/Asset/dokumentasiHukum.png')"
                    imageAlt="Izin Operasional LKSA Yayasan Himmatun Ayat"
                    footerText="Masa Berlaku: 20XX - 20XX"
                    data-tilt-3d
                    data-tilt-glare
                    data-tilt-max="12"
                />

                <!-- Dokumen 2: Akta Notaris & SK Kemenkumham -->
                <x-tentang-kami.dokumen-hukum-card 
                    icon="notary"
                    badgeText="KemenKumHam RI Sah"
                    badgeVariant="sage"
                    title="Akta Notaris Pendirian &amp; SK KemenkumHam RI"
                    nomor="SK Pengesahan: AHU-0012345.AH.01.04.Tahun 2005"
                    deskripsi="Akta Notaris No.XXXXX dan Pengesahan Menteri Hukum dan HAM Republik Indonesia atas Badan hukum Yayasan."
                    :image="asset('images/Asset/dokumentasiHukum.png')"
                    imageAlt="Akta Notaris dan SK Kemenkumham"
                    footerText="Status: Badan Hukum Sah Permanen"
                    data-tilt-3d
                    data-tilt-glare
                    data-tilt-max="12"
                />

                <!-- Dokumen 3: NPWP Resmi Lembaga -->
                <x-tentang-kami.dokumen-hukum-card 
                    icon="tax"
                    badgeText="KemenKumHam RI Sah"
                    badgeVariant="sage"
                    title="NPWP Resmi Lembaga Yayasan"
                    nomor="XXXX XXXX XXXX"
                    deskripsi="Kartu Pokok Wajib Pajak Badan atas nama Yayasan Himmatun Ayat Bandung di KPP Pratama Bandung Cibeunying dengan ketaatan Pelaporan SPT Tahunan nilai audit"
                    :image="asset('images/Asset/dokumentasiHukum.png')"
                    imageAlt="NPWP Lembaga Yayasan Himmatun Ayat"
                    footerText="KPP Pratama Bandung Cibeunying"
                    data-tilt-3d
                    data-tilt-glare
                    data-tilt-max="12"
                />

                <!-- Dokumen 4: Pengawasan Syariah & Transparansi Publik -->
                <x-tentang-kami.dokumen-hukum-card 
                    icon="shield"
                    badgeText="Audit & Akuntabel"
                    badgeVariant="success"
                    title="Sertifikasi Dewan Syariah & Akuntabilitas Finansial"
                    nomor="SK Penetapan: DPS-HA/BDG/X-2024"
                    deskripsi="Audit kepatuhan syariah dan verifikasi transparansi pengasuhan santri yatim dhuafa secara akuntabel, bebas konflik kepentingan, serta dapat diaudit donatur."
                    :image="asset('images/Asset/dokumentasiHukum.png')"
                    imageAlt="Sertifikasi Dewan Syariah Yayasan Himmatun Ayat"
                    footerText="Status: Wajar Tanpa Pengecualian (WTP)"
                    data-tilt-3d
                    data-tilt-glare
                    data-tilt-max="12"
                />

            </div>

        </div>
    </section>

</div>
@endsection
