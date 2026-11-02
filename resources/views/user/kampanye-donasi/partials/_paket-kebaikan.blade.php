    <!-- Section 2: Hasil Donasi Yang Anda Berikan (Paket Kebaikan) -->
    <section class="relative py-16">
        <!-- Full green gradient background -->
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-[#D8F4EB] via-[#EDF8F3] to-white overflow-hidden">
            <div class="absolute -top-24 right-0 w-full sm:w-3/5 h-[600px] bg-gradient-to-bl from-[#BFEFE0]/60 via-[#DCF6ED]/30 to-transparent blur-3xl"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Paket Kebaikan -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-foreground tracking-tight">
                        Hasil Donasi Yang Anda Berikan
                    </h2>
                    <p class="text-xs sm:text-sm text-muted-foreground-1 mt-1 max-w-2xl">
                        Pilih paket kebaikan untuk mendukung kebutuhan esensial para santri yatim dhuafa dengan penyaluran langsung.
                    </p>
                </div>

                <!-- Panah Carousel Slider Sesuai Mockup -->
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <button type="button" wire:click="prevSlide({{ $kampanyeList->count() }})"
                        class="w-9 h-9 rounded-xl border border-border bg-card hover:bg-muted flex items-center justify-center text-muted-foreground-2 hover:text-foreground transition shadow-2xs active:scale-95"
                        aria-label="Sebelumnya">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button type="button" wire:click="nextSlide({{ $kampanyeList->count() }})"
                        class="w-9 h-9 rounded-xl border border-border bg-card hover:bg-muted flex items-center justify-center text-muted-foreground-2 hover:text-foreground transition shadow-2xs active:scale-95"
                        aria-label="Selanjutnya">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Cards Grid (4 Paket Sesuai Mockup) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                @php
                    $packageConfigs = [
                        'paket-makan-nutrisi-harian' => [
                            'badge' => 'Nutrisi',
                            'badge_icon' => 'cutlery',
                            'nominal' => 25000,
                            'image' => asset('images/kampanye/nutrisi.svg'),
                        ],
                        'paket-alat-buku-tulis' => [
                            'badge' => 'Perlengkapan',
                            'badge_icon' => 'pencil',
                            'nominal' => 100000,
                            'image' => asset('images/kampanye/alat-tulis.svg'),
                        ],
                        'paket-beasiswa-pendidikan-santri' => [
                            'badge' => 'Pendidikan',
                            'badge_icon' => 'cap',
                            'nominal' => 250000,
                            'image' => asset('images/kampanye/beasiswa.svg'),
                        ],
                        'paket-khalafah-dakwah-tahfidz' => [
                            'badge' => 'Tahfidz Quran',
                            'badge_icon' => 'book',
                            'nominal' => 500000,
                            'image' => asset('images/kampanye/tahfidz.svg'),
                        ],
                    ];
                @endphp

                @foreach ($kampanyeList as $index => $item)
                    @php
                        $cfg = $packageConfigs[$item->slug] ?? [
                            'badge' => $item->kategori,
                            'badge_icon' => 'book',
                            'nominal' => 50000,
                            'image' => asset($item->banner_path ?: 'images/kampanye/nutrisi.svg'),
                        ];
                    @endphp

                    <div class="group relative rounded-[28px] p-5 sm:p-6 flex flex-col justify-between transition-all duration-300 hover:-translate-y-1 bg-white text-foreground border border-slate-200/80 shadow-sm hover:bg-brand-700 hover:text-white hover:border-brand-700 hover:shadow-xl cursor-pointer">

                        <div>
                            <!-- Header Card: Badge Kategori -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="badge badge-success border border-brand-200 group-hover:bg-white/15 group-hover:text-brand-100 group-hover:border-white/20 transition-colors duration-300">
                                    @if ($cfg['badge_icon'] === 'cutlery')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                        </svg>
                                    @elseif ($cfg['badge_icon'] === 'pencil')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    @elseif ($cfg['badge_icon'] === 'cap')
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    @endif
                                    <span>{{ $cfg['badge'] }}</span>
                                </span>
                            </div>

                            <!-- Gambar Paket -->
                            <div class="rounded-2xl overflow-hidden aspect-4/3 mb-4 bg-slate-50 group-hover:bg-white/60 flex items-center justify-center p-2 border border-slate-200/40 transition-colors duration-300">
                                <img src="{{ $cfg['image'] }}" alt="{{ $item->judul }}"
                                    class="w-full h-full object-contain drop-shadow-xs transition transform group-hover:scale-105 duration-200" />
                            </div>

                            <!-- Judul & Deskripsi -->
                            <h3 class="font-bold text-base sm:text-lg mb-1.5 leading-snug text-foreground group-hover:text-white transition-colors duration-300">
                                {{ $item->judul }}
                            </h3>
                            <p class="text-xs leading-relaxed mb-4 text-muted-foreground-1 group-hover:text-brand-100/90 transition-colors duration-300">
                                {{ $item->deskripsi }}
                            </p>
                        </div>

                        <!-- Nominal & Tombol Pilih Donasi -->
                        <div class="pt-4 border-t border-border group-hover:border-white/15 transition-colors duration-300">
                            <div class="mb-3">
                                <span class="block text-2xs font-medium text-muted-foreground-1 group-hover:text-brand-200 transition-colors duration-300">
                                    Nominal Donasi:
                                </span>
                                <span class="text-lg font-extrabold tracking-tight tabular text-foreground group-hover:text-white transition-colors duration-300">
                                    Rp {{ number_format($cfg['nominal'], 0, ',', '.') }}
                                </span>
                            </div>

                            <button type="button"
                                wire:click="selectKampanye({{ $item->id }}, {{ $cfg['nominal'] }})"
                                class="w-full py-3 px-4 rounded-xl text-xs sm:text-sm font-bold flex items-center justify-center gap-2 transition-all duration-300 active:scale-95 bg-brand-700 hover:bg-brand-800 text-white shadow-xs group-hover:bg-white group-hover:text-brand-700 group-hover:hover:bg-brand-50">
                                <span>Pilih Donasi</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </button>
                        </div>

                    </div>
                @endforeach

            </div>

            <!-- Dots Pagination Indikator Carousel Sesuai Mockup -->
            <div class="flex items-center justify-center gap-2 mt-10">
                <button type="button" wire:click="setSlide(0)" class="h-2.5 rounded-full transition-all {{ $activeSlide === 0 ? 'bg-brand-700 w-6' : 'bg-slate-300 w-2.5' }}" aria-label="Slide 1"></button>
                <button type="button" wire:click="setSlide(1)" class="h-2.5 rounded-full transition-all {{ $activeSlide === 1 ? 'bg-brand-700 w-6' : 'bg-slate-300 w-2.5' }}" aria-label="Slide 2"></button>
                <button type="button" wire:click="setSlide(2)" class="h-2.5 rounded-full transition-all {{ $activeSlide === 2 ? 'bg-brand-700 w-6' : 'bg-slate-300 w-2.5' }}" aria-label="Slide 3"></button>
            </div>
        </div>
    </section>