@extends('layouts.user')

@section('content')
<div class="overflow-x-hidden">

    <!-- ====================================================================== -->
    <!-- 1. HERO SECTION: PROFIL LEMBAGA & LOGO RESMI                          -->
    <!-- ====================================================================== -->
    <section class="relative pt-4 sm:pt-8 lg:pt-12 pb-14 sm:pb-20 lg:pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 lg:gap-14 items-center">
                
                <!-- Kolom Teks (Kiri di Desktop) -->
                <div class="lg:col-span-7 space-y-4 sm:space-y-6 text-left">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[3.25rem] font-extrabold text-slate-900 leading-[1.15] tracking-tight">
                        Profil Lembaga &amp; Legalitas Hukum Resmi Yayasan Himmatun Ayat Bandung
                    </h1>

                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed font-normal max-w-2xl">
                        Membangun masa depan generasi yatim, piatu, dan dhuafa Kota Bandung berlandaskan tata kelola amanah, legitimasi negara yang transparan, serta pembinaan akhlak karimah yang berkesinambungan.
                    </p>
                </div>

                <!-- Kolom Lambang / Logo Yayasan (Kanan di Desktop, Tengah di Tablet/Mobile) -->
                <div class="lg:col-span-5 flex justify-center items-center relative">
                    <!-- Warm Yellow & Mint Aura Behind the Logo -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-[#B7E4C7]/40 via-[#FDE68A]/50 to-transparent rounded-full blur-3xl pointer-events-none -z-10 scale-110"></div>
                    
                    <div class="relative group">
                        <img src="{{ asset('images/Asset/logo_himmatun.png') }}" 
                             alt="Logo Resmi Yayasan Himmatun Ayat" 
                             class="w-64 sm:w-80 lg:w-96 max-w-full h-auto object-contain drop-shadow-xl group-hover:scale-105 transition-transform duration-500">
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ====================================================================== -->
    <!-- 2. SEJARAH SINGKAT & KOMITMEN                                          -->
    <!-- ====================================================================== -->
    <section class="relative py-12 sm:py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 sm:gap-12 lg:gap-16 items-center">
                
                <!-- Kolom Kiri: Foto Komitmen & Badge Sejak 2006 -->
                <div class="lg:col-span-6 relative">
                    <div class="relative rounded-3xl overflow-hidden shadow-xl border-4 border-white bg-slate-100 aspect-[4/3] group">
                        <!-- Main Image -->
                        <img src="{{ asset('images/Asset/Mengayomi1.450+AnakYatim&Dhuafa.png') }}" 
                             alt="Mengayomi Anak Yatim dan Dhuafa" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        
                        <!-- Soft Ambient Glow on Image -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent pointer-events-none"></div>

                        <!-- Floating Frosted Glass Badge at Bottom-Left -->
                        <div class="absolute bottom-4 left-4 right-4 sm:right-auto bg-slate-950/75 backdrop-blur-md border border-white/20 rounded-2xl p-2.5 sm:p-3 flex items-center gap-2.5 text-white shadow-xl">
                            <span class="bg-amber-400 text-slate-950 font-bold px-2.5 py-0.5 rounded-full text-2xs uppercase tracking-wide shrink-0">
                                SEJAK 2006
                            </span>
                            <span class="text-xs sm:text-sm font-semibold tracking-wide drop-shadow-xs">
                                Mengayomi 1.450+ Anak Yatim &amp; Dhuafa
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Uraian Sejarah & 2 Metric Cards -->
                <div class="lg:col-span-6 space-y-4 sm:space-y-6">
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
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <x-tentang-kami.stat-card 
                            value="100%" 
                            label="Amanah Penyaluran Donatur" 
                        />
                        <x-tentang-kami.stat-card 
                            value="2 Asrama" 
                            label="Cikutra Putra &amp; Cibiru Putri" 
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
            
            <!-- Section Header (Centered) -->
            <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-14 space-y-3">
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
                
                <!-- Left: Visi Utama Big Card (Dark Green) -->
                <div class="lg:col-span-5 flex">
                    <div class="relative w-full rounded-3xl bg-brand-900 text-white p-6 sm:p-8 flex flex-col justify-between shadow-xl overflow-hidden border border-brand-800">
                        
                        <!-- Background Glow Accent -->
                        <div class="absolute -top-20 -right-20 w-60 h-60 bg-brand-700/40 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute -bottom-16 -left-16 w-52 h-52 bg-brand-500/20 rounded-full blur-3xl pointer-events-none"></div>

                        <!-- Top Content: Badge & Quote -->
                        <div class="relative z-10">
                            <span class="inline-block px-3 py-1 rounded-full text-2xs font-extrabold uppercase tracking-wider bg-brand-800/90 border border-brand-700 text-brand-200 shadow-2xs">
                                VISI UTAMA
                            </span>

                            <blockquote class="text-lg sm:text-xl md:text-2xl font-bold leading-snug text-white mt-5">
                                “Menjadi lembaga filantropi dan pengasuhan anak yatim terdepan, amanah, profesional, dan berakhlak mulia di Jawa Barat.”
                            </blockquote>

                            <p class="text-xs sm:text-[13px] text-brand-100/85 leading-relaxed mt-4 font-normal">
                                Sebuah komitmen jangka panjang melahirkan generasi dhuafa yang bertransformasi dari penerima manfaat (<em>mustahik</em>) menjadi insan mandiri pemberi manfaat (<em>muzakki</em>).
                            </p>
                        </div>

                        <!-- Bottom Content: Author & Avatar Illustration -->
                        <div class="relative z-10 pt-8 mt-6 border-t border-brand-800/80 flex items-end justify-between gap-4">
                            <div>
                                <h4 class="text-base sm:text-lg font-bold text-white tracking-tight">
                                    Pak Yusuf
                                </h4>
                                <p class="text-xs text-brand-300 font-medium mt-0.5">
                                    Ketua Yayasan Himmatun Ayat Bandung
                                </p>
                            </div>

                            <!-- Pak Yusuf Illustration (Positioned on the bottom right) -->
                            <div class="shrink-0 -mb-6 sm:-mb-8 -mr-2 sm:-mr-4">
                                <img src="{{ asset('images/Asset/YusufHamdani.png') }}" 
                                     alt="Pak Yusuf Hamdani" 
                                     class="w-32 sm:w-40 h-auto object-contain drop-shadow-lg">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right: 4 Misi Cards Grid (2x2) -->
                <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    
                    <!-- Misi 01 -->
                    <x-tentang-kami.misi-card 
                        number="01" 
                        title="Pendidikan Formal Unggul" 
                        description="Menjamin 100% anak asuh menyelesaikan wajib belajar 12 tahun hingga beasiswa penuh perguruan tinggi negeri maupun swasta terakreditasi." 
                        tag="Bebas Putus Sekolah" 
                        icon="education" 
                    />

                    <!-- Misi 02 -->
                    <x-tentang-kami.misi-card 
                        number="02" 
                        title="Pembinaan Karakter Tangguh" 
                        description="Membentuk jiwa kepemimpinan, kedisiplinan, integritas sosial, serta kesiapan mental melalui pendampingan psikologis berkala." 
                        tag="Konseling & Mentorship" 
                        icon="character" 
                    />

                    <!-- Misi 03 -->
                    <x-tentang-kami.misi-card 
                        number="03" 
                        title="Tahfidz & Akidah Islam" 
                        description="Menyelenggarakan kurikulum intensif tahsin, tahfidz Al-Qur'an juz 30, hafalan hadits pilihan, serta penanaman adab harian islami." 
                        tag="Sanad & Pembinaan Santri" 
                        icon="quran" 
                    />

                    <!-- Misi 04 -->
                    <x-tentang-kami.misi-card 
                        number="04" 
                        title="Kemandirian Ekonomi Santri" 
                        description="Memberikan kursus literasi digital, wirausaha kreatif, kejuruan teknik, serta inkubasi bisnis mini untuk alumni asrama." 
                        tag="Kesiapan Kerja Nyata" 
                        icon="business" 
                    />

                </div>

            </div>

        </div>
    </section>


    <!-- ====================================================================== -->
    <!-- 4. TATA KELOLA & STRUKTUR ORGANISASI                                   -->
    <!-- ====================================================================== -->
    <section class="relative py-16 sm:py-20 lg:py-24 bg-brand-900 text-white overflow-hidden">
        
        <!-- Subtle Islamic Geometric / Star Pattern Watermark in Background -->
        <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: radial-gradient(#ffffff 1.5px, transparent 1.5px); background-size: 24px 24px;"></div>
        
        <!-- Ambient Radial Glows -->
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-brand-700/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-brand-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- DESKTOP TOP ROW: Left Card, Center Title, Right Card (Sesuai Desain Tentang Kami.png) -->
            <div class="hidden lg:grid lg:grid-cols-12 gap-8 items-center mb-14">
                
                <!-- Left Card: Yusuf Hamdani (01) -->
                <div class="lg:col-span-3">
                    <x-tentang-kami.pengurus-card 
                        number="01" 
                        name="Yusuf Hamdani" 
                        role="Ketua Yayasan" 
                        :image="asset('images/Asset/YusufHamdani.png')" 
                        icon="leader"
                        :featured="true"
                    />
                </div>

                <!-- Center: Title & Description -->
                <div class="lg:col-span-6 text-center space-y-3 px-4">
                    <x-tentang-kami.section-badge icon="organization" variant="dark">
                        TATA KELOLA &amp; STRUKTUR ORGANISASI
                    </x-tentang-kami.section-badge>

                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                        Amanah Figur Profesional &amp; Syariah
                    </h2>

                    <p class="text-sm text-brand-100/85 leading-relaxed max-w-lg mx-auto font-normal">
                        Dikelola secara kolektif kolegial dengan pengawasan syariah ketat guna menjamin transparansi penyaluran dana umat dan kualitas pengasuhan.
                    </p>
                </div>

                <!-- Right Card: Euis Nuraeni (02) -->
                <div class="lg:col-span-3">
                    <x-tentang-kami.pengurus-card 
                        number="02" 
                        name="Euis Nuraeni" 
                        role="Pengurus" 
                        :image="asset('images/Asset/EuisNuraeni.png')" 
                        icon="manager"
                        :featured="true"
                    />
                </div>

            </div>

            <!-- TABLET & MOBILE HEADER (Tampil di atas grid pada layar < lg) -->
            <div class="lg:hidden text-center max-w-2xl mx-auto mb-10 space-y-3">
                <x-tentang-kami.section-badge icon="organization" variant="dark">
                    TATA KELOLA &amp; STRUKTUR ORGANISASI
                </x-tentang-kami.section-badge>

                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                    Amanah Figur Profesional &amp; Syariah
                </h2>

                <p class="text-xs sm:text-sm text-brand-100/85 leading-relaxed font-normal">
                    Dikelola secara kolektif kolegial dengan pengawasan syariah ketat guna menjamin transparansi penyaluran dana umat dan kualitas pengasuhan.
                </p>
            </div>

            <!-- BOTTOM GRID: 4 Pengurus Cards (Responsive: 1 col mobile, 2 col tablet, 4 col desktop) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6">
                
                <!-- Card 01: Yusuf Hamdani -->
                <x-tentang-kami.pengurus-card 
                    number="01" 
                    name="Yusuf Hamdani" 
                    role="Ketua Yayasan" 
                    :image="asset('images/Asset/YusufHamdani.png')" 
                    icon="leader" 
                />

                <!-- Card 02: Euis Nuraeni -->
                <x-tentang-kami.pengurus-card 
                    number="02" 
                    name="Euis Nuraeni" 
                    role="Pengurus" 
                    :image="asset('images/Asset/EuisNuraeni.png')" 
                    icon="manager" 
                />

                <!-- Card 03: Daslim -->
                <x-tentang-kami.pengurus-card 
                    number="03" 
                    name="Daslim" 
                    role="Pengurus" 
                    :image="asset('images/Asset/Daslim.png')" 
                    icon="manager" 
                />

                <!-- Card 04: Juhaeriyah -->
                <x-tentang-kami.pengurus-card 
                    number="04" 
                    name="Juhaeriyah" 
                    role="Pengurus" 
                    :image="asset('images/Asset/Juhaeriyah.png')" 
                    icon="book" 
                />

            </div>

        </div>
    </section>


    <!-- ====================================================================== -->
    <!-- 5. DOKUMEN HUKUM & LISENSI RESMI NEGARA                                -->
    <!-- ====================================================================== -->
    <section class="relative py-14 sm:py-20 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header (Left-Aligned, Sesuai Desain) -->
            <div class="max-w-3xl mb-10 sm:mb-14 space-y-3">
                <x-tentang-kami.section-badge icon="legal">
                    LEGALITAS &amp; LEGAL STANDING LEMBAGA
                </x-tentang-kami.section-badge>

                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    Dokumen Hukum &amp; Lisenssi Resmi Negara
                </h2>

                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed font-normal">
                    Yayasan Himmatun Ayat Bandung beroperasi penuh di bawah payung hukum Republik Indonesia dan diaudit secara berkala oleh kementrian terkait.
                </p>
            </div>

            <!-- Cards Grid (Responsive: 1 col on mobile, 2 col on tablet & desktop) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 items-start">
                
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
                />

                <!-- Dokumen 2: Akta Notaris & Kemenkumham -->
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
                />

            </div>

        </div>
    </section>

</div>
@endsection
