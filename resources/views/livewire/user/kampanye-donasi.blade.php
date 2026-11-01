<div id="kampanye-page" class="relative overflow-hidden" x-data="{ copiedBank: null }">

    <!-- Scroll target helper -->
    <div id="formulir-donatur" class="scroll-mt-28"></div>

    <!-- Section 1: Hero, Formulir Donatur & Doa Harapan -->
    <section class="relative pt-6 pb-16 sm:pb-20">
        <!-- Ambient background glow matching Design System Brand Pastel -->
        <div class="absolute inset-0 -z-10 pointer-events-none overflow-hidden">
            <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-brand-100/40 blur-3xl"></div>
            <div class="absolute top-20 right-0 w-[500px] h-[500px] rounded-full bg-brand-50/70 blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

                <!-- Kolom Kiri: Formulir Donatur -->
                <div class="lg:col-span-6 xl:col-span-6">
                    <div class="bg-brand-50 border border-brand-200/80 rounded-[32px] p-6 sm:p-8 lg:p-9 shadow-xs relative">
                        <h2 class="text-2xl sm:text-[26px] font-bold text-foreground text-center mb-6 tracking-tight">
                            Formulir Donatur
                        </h2>

                        <form wire:submit.prevent="confirmDonasi" class="space-y-5">
                            <!-- Program Donasi Select -->
                            <div>
                                <label class="form-label text-xs sm:text-sm font-semibold">
                                    Program Donasi
                                </label>
                                <div class="relative">
                                    <select wire:model.live="selectedKampanyeId"
                                        class="form-input appearance-none bg-white py-3 pr-10 text-xs sm:text-sm font-medium">
                                        <option value="">-- Donasi Umum / Semua Program --</option>
                                        @foreach ($kampanyeList as $k)
                                            <option value="{{ $k->id }}">{{ $k->judul }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-muted-foreground">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('selectedKampanyeId')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pilih Nominal Donasi Preset Buttons -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="form-label text-xs sm:text-sm font-semibold mb-0">
                                        Pilih Nominal Donasi <span class="text-danger">*</span>
                                    </label>
                                    <span class="text-xs text-brand-700 font-semibold">Min. Rp 10.000</span>
                                </div>

                                <div class="grid grid-cols-4 gap-2 sm:gap-2.5">
                                    @php
                                        $presetNominals = [10000, 20000, 50000, 100000, 150000, 250000, 500000, 1000000];
                                    @endphp
                                    @foreach ($presetNominals as $nominal)
                                        <button type="button"
                                            wire:click="setNominal({{ $nominal }})"
                                            class="py-2.5 px-1 sm:px-2 rounded-full text-xs sm:text-sm font-semibold transition-all duration-150 border text-center tabular {{ $selectedNominal === $nominal ? 'bg-brand-700 text-white border-brand-700 shadow-xs ring-2 ring-brand-700/20' : 'bg-white hover:bg-brand-50 text-brand-700 border-brand-200 hover:border-brand-700' }}">
                                            Rp{{ number_format($nominal, 0, ',', '.') }}
                                        </button>
                                    @endforeach
                                </div>

                                <!-- Input Nominal Custom -->
                                <div class="mt-3 relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-xs sm:text-sm font-bold text-muted-foreground">
                                        Rp
                                    </div>
                                    <input type="number" wire:model.live.debounce.300ms="customNominal" min="10000" step="5000"
                                        placeholder="Nominal lainnya (contoh: 75000)"
                                        class="form-input bg-white py-2.5 pl-11 pr-4 text-xs sm:text-sm font-semibold tabular" />
                                </div>
                                @error('selectedNominal')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Row: Nama Lengkap & No Telepon -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="form-label text-xs sm:text-sm font-semibold">
                                        Nama Lengkap Donatur <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" wire:model="namaDonatur"
                                        {{ $isAnonim ? 'readonly' : '' }}
                                        placeholder="Masukan Teks"
                                        class="form-input bg-white py-3 text-xs sm:text-sm {{ $isAnonim ? 'bg-slate-100 text-muted-foreground' : '' }}" />
                                    @error('namaDonatur')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="form-label text-xs sm:text-sm font-semibold">
                                        No. Telepon / WA <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" wire:model="noTelepon"
                                        placeholder="Masukan Teks"
                                        class="form-input bg-white py-3 text-xs sm:text-sm" />
                                    @error('noTelepon')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Alamat Email (Opsional) -->
                            <div>
                                <label class="form-label text-xs sm:text-sm font-semibold">
                                    Alamat Email (Opsional)
                                </label>
                                <input type="email" wire:model="email"
                                    placeholder="Masukan Teks"
                                    class="form-input bg-white py-3 text-xs sm:text-sm" />
                                @error('email')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Checkbox: Rahasiakan Nama Anda Menjadi Hamba Allah -->
                            <div class="flex items-center gap-2.5 pt-1">
                                <input type="checkbox" id="hambaAllahCheckbox" wire:model.live="isAnonim"
                                    class="w-4 h-4 rounded text-brand-700 border-border focus:ring-brand-700" />
                                <label for="hambaAllahCheckbox" class="text-xs sm:text-sm font-medium text-foreground cursor-pointer select-none">
                                    Rahasiakan Nama Anda Menjadi Hamba Allah
                                </label>
                            </div>

                            <!-- Doa & Harapan Donatur -->
                            <div>
                                <label class="form-label text-xs sm:text-sm font-semibold">
                                    Doa & Harapan Donatur
                                </label>
                                <textarea wire:model="doaHarapan" rows="3"
                                    placeholder="Masukan Teks"
                                    class="form-input bg-white py-3 text-xs sm:text-sm resize-none"></textarea>
                                @error('doaHarapan')
                                    <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2 text-center">
                                <button type="submit"
                                    class="btn btn-primary rounded-full px-10 py-3.5 text-sm sm:text-base font-semibold shadow-md hover:shadow-lg w-full sm:w-auto">
                                    Konfirmasi Donasi Anda
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Kolom Kanan: Donasi Anda Membantu Kami & Doa Harapan -->
                <div class="lg:col-span-6 xl:col-span-6 space-y-8">
                    <!-- Heading & Intro Text Sesuai Mockup -->
                    <div>
                        <h1 class="text-3xl sm:text-4xl lg:text-[46px] font-extrabold text-foreground leading-[1.18] tracking-tight">
                            Donasi Anda<br/>Membantu Kami
                        </h1>
                        <p class="text-xs sm:text-sm lg:text-base text-muted-foreground-1 leading-relaxed mt-4">
                            Berapa pun yang Anda berikan sangat berarti bagi kami. Karena bagi kami, yang terpenting bukanlah jumlahnya, melainkan keikhlasan dan niat baik di balik setiap donasi. Donasi yang Anda berikan akan disalurkan untuk membantu mereka yang membutuhkan serta mendukung berbagai program sosial dan kemanusiaan Yayasan Himatun.
                        </p>
                    </div>

                    @php
                        $doaChunks = $recentDoa->chunk(2);
                    @endphp

                    <!-- Card Doa & Harapan Sesuai Mockup (Auto-rotating, No Manual Scroll) -->
                    <div class="bg-brand-50 border border-brand-200/90 rounded-[32px] p-6 sm:p-7 shadow-2xs relative overflow-hidden"
                        x-data="{
                            currentGroup: 0,
                            totalGroups: {{ max(1, $doaChunks->count()) }},
                            paused: false,
                            init() {
                                if (this.totalGroups > 1) {
                                    setInterval(() => {
                                        if (!this.paused) {
                                            this.currentGroup = (this.currentGroup + 1) % this.totalGroups;
                                        }
                                    }, 4000);
                                }
                            }
                        }"
                        @mouseenter="paused = true"
                        @mouseleave="paused = false">

                        <!-- Header Doa & Harapan -->
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h3 class="text-xl font-bold text-foreground tracking-tight">
                                    Doa & Harapan
                                </h3>
                                <p class="text-xs text-muted-foreground-1 mt-0.5 font-medium">
                                    Untaian kebaikan dari para donatur
                                </p>
                            </div>

                            @if ($doaChunks->count() > 1)
                                <!-- Live indicator & mini progress dots -->
                                <div class="flex items-center gap-1.5">
                                    <div class="flex items-center gap-1 ml-1">
                                        @foreach ($doaChunks as $idx => $chunk)
                                            <button type="button" @click="currentGroup = {{ $idx }}"
                                                :class="currentGroup === {{ $idx }} ? 'bg-brand-700 w-3' : 'bg-brand-300 w-1.5'"
                                                class="h-1.5 rounded-full transition-all duration-300"
                                                aria-label="Doa slide {{ $idx + 1 }}"></button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Area Kartu Doa Otomatis Berganti (Tanpa Scrollbar Manual) -->
                        <div class="relative min-h-[290px]">
                            @forelse ($doaChunks as $groupIndex => $chunk)
                                <div x-show="currentGroup === {{ $groupIndex }}"
                                    x-transition:enter="transition ease-out duration-500"
                                    x-transition:enter-start="opacity-0 translate-y-3"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-300 absolute inset-0"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 -translate-y-3"
                                    class="space-y-3.5 w-full">
                                    @foreach ($chunk as $doa)
                                        <div class="bg-card rounded-2xl p-4 sm:p-4.5 shadow-2xs border border-brand-100 transition hover:shadow-xs">
                                            <div class="flex items-center gap-3">
                                                <!-- Avatar Icon Bulat -->
                                                <div class="w-9 h-9 rounded-full bg-brand-700 text-white flex items-center justify-center shrink-0">
                                                    @if(file_exists(public_path('images/avatars/avatar-boy.svg')))
                                                        <img src="{{ asset('images/avatars/avatar-boy.svg') }}" alt="Avatar" class="w-5 h-5">
                                                    @else
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="font-bold text-xs sm:text-sm text-foreground leading-tight">
                                                        {{ $doa->nama_donatur }}
                                                    </div>
                                                    <div class="text-2xs text-muted-foreground-1 font-medium tabular">
                                                        Donasi Rp{{ number_format($doa->nominal, 2, ',', '.') }} • {{ $doa->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-3 text-xs sm:text-sm text-slate-700 leading-relaxed italic">
                                                &ldquo;{{ $doa->pesan_doa }}&rdquo;
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @empty
                                <div class="bg-card rounded-2xl p-6 text-center text-muted-foreground text-xs">
                                    Belum ada untaian doa. Jadilah donatur pertama yang mengirimkan doa!
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Section 2: Hasil Donasi Yang Anda Berikan (Paket Kebaikan) -->
    <section class="py-16 bg-card border-t border-border">
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
                            'is_featured' => false,
                            'nominal' => 25000,
                            'image' => asset('images/kampanye/nutrisi.svg'),
                        ],
                        'paket-alat-buku-tulis' => [
                            'badge' => 'Perlengkapan',
                            'badge_icon' => 'pencil',
                            'is_featured' => false,
                            'nominal' => 100000,
                            'image' => asset('images/kampanye/alat-tulis.svg'),
                        ],
                        'paket-beasiswa-pendidikan-santri' => [
                            'badge' => 'Pendidikan',
                            'badge_icon' => 'cap',
                            'is_featured' => true, // Featured card dengan brand-700
                            'nominal' => 250000,
                            'image' => asset('images/kampanye/beasiswa.svg'),
                        ],
                        'paket-khalafah-dakwah-tahfidz' => [
                            'badge' => 'Tahfidz Quran',
                            'badge_icon' => 'book',
                            'is_featured' => false,
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
                            'is_featured' => ($index === 2),
                            'nominal' => 50000,
                            'image' => asset($item->banner_path ?: 'images/kampanye/nutrisi.svg'),
                        ];
                        $isDark = $cfg['is_featured'];
                    @endphp

                    <div class="relative rounded-[28px] p-5 sm:p-6 flex flex-col justify-between transition-all duration-200 hover:-translate-y-1 hover:shadow-lg {{ $isDark ? 'bg-brand-700 text-white shadow-md' : 'bg-brand-50 text-foreground border border-brand-200/70' }}">

                        <div>
                            <!-- Header Card: Badge Kategori Sesuai Token -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="badge {{ $isDark ? 'bg-white/15 text-brand-100 border border-white/20' : 'badge-success border border-brand-200' }}">
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
                            <div class="rounded-2xl overflow-hidden aspect-4/3 mb-4 bg-white/60 flex items-center justify-center p-2 border border-border/40">
                                <img src="{{ $cfg['image'] }}" alt="{{ $item->judul }}"
                                    class="w-full h-full object-contain drop-shadow-xs transition transform hover:scale-105 duration-200" />
                            </div>

                            <!-- Judul & Deskripsi -->
                            <h3 class="font-bold text-base sm:text-lg mb-1.5 leading-snug {{ $isDark ? 'text-white' : 'text-foreground' }}">
                                {{ $item->judul }}
                            </h3>
                            <p class="text-xs leading-relaxed mb-4 {{ $isDark ? 'text-brand-100/90' : 'text-muted-foreground-1' }}">
                                {{ $item->deskripsi }}
                            </p>
                        </div>

                        <!-- Nominal & Tombol Pilih Donasi -->
                        <div class="pt-4 border-t {{ $isDark ? 'border-white/15' : 'border-border' }}">
                            <div class="mb-3">
                                <span class="block text-2xs font-medium {{ $isDark ? 'text-brand-200' : 'text-muted-foreground-1' }}">
                                    Nominal Donasi:
                                </span>
                                <span class="text-lg font-extrabold tracking-tight tabular {{ $isDark ? 'text-white' : 'text-foreground' }}">
                                    Rp {{ number_format($cfg['nominal'], 0, ',', '.') }}
                                </span>
                            </div>

                            <button type="button"
                                wire:click="selectKampanye({{ $item->id }}, {{ $cfg['nominal'] }})"
                                class="w-full py-3 px-4 rounded-xl text-xs sm:text-sm font-bold flex items-center justify-center gap-2 transition-all duration-150 active:scale-95 {{ $isDark ? 'bg-white hover:bg-brand-50 text-brand-700 shadow-sm' : 'bg-brand-700 hover:bg-brand-800 text-white shadow-xs' }}">
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

    <!-- Section 3: Call to Action Banner Media Sosial Sesuai Mockup -->
    <section class="py-8 bg-card">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative bg-brand-800 rounded-[24px] p-6 sm:p-8 lg:p-10 text-white shadow-md overflow-hidden">
                <!-- Background subtle radial glow -->
                <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-brand-500/20 blur-2xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <!-- Teks Kiri -->
                    <div class="space-y-2 max-w-3xl">
                        <div class="flex items-center gap-2 text-xs font-bold text-brand-200 tracking-wider">
                            <svg class="w-4 h-4 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                            <span>TERHUBUNG BERSAMA KAMI</span>
                        </div>
                        <h3 class="text-xl sm:text-2xl lg:text-[26px] font-extrabold leading-snug">
                            Ikuti Keseharian & Live Story Adik-adik Asuh di Media Sosial
                        </h3>
                        <p class="text-xs sm:text-sm text-brand-100/80 leading-relaxed">
                            Saksikan langsung penyaluran doa dan kebaikan Anda melalui akun resmi Instagram Yayasan Himmatun Ayat Bandung
                        </p>
                    </div>

                    <!-- Tombol Instagram Kanan -->
                    <div class="shrink-0">
                        <a href="https://instagram.com/himmatunayatbandung" target="_blank"
                            class="inline-flex items-center gap-2.5 px-6 py-3 rounded-full bg-white/10 hover:bg-white/20 border border-white/25 text-white font-semibold text-xs sm:text-sm shadow-xs hover:shadow-md transition backdrop-blur-xs">
                            <!-- Instagram Camera Icon -->
                            <svg class="w-4 h-4 text-brand-200" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                            <span>@himmatunayatbandung</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Konfirmasi Donasi & Pembayaran Sesuai Mockup & Design System Tokens -->
    @if ($showPaymentModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity animate-in fade-in overflow-y-auto">
            <div class="bg-layer rounded-[28px] sm:rounded-[32px] max-w-lg w-full p-3.5 sm:p-5 shadow-2xl border border-border space-y-3 sm:space-y-3.5 my-auto max-h-[95vh] overflow-y-auto text-foreground">

                @if ($paymentStep === 1)
                    <!-- STEP 1: POP-UP SESUAI MOCKUP -->

                    <!-- Header Card: [X] Yayasan Himatun -->
                    <div class="bg-card rounded-2xl px-4 sm:px-5 py-3.5 sm:py-4 flex items-center gap-4 shadow-2xs border border-border/50">
                        <button type="button" wire:click="closePaymentModal" class="text-danger hover:text-red-700 transition cursor-pointer p-0.5 flex items-center justify-center" aria-label="Tutup">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        <h3 class="text-base sm:text-lg font-bold text-brand-900 tracking-tight">
                            Yayasan Himatun
                        </h3>
                    </div>

                    <!-- Card 1: Selesaikan pembayaran anda + ID Pembayaran + Timer -->
                    <div class="bg-card rounded-2xl p-4 sm:p-5 shadow-2xs border border-border/50 flex items-center justify-between gap-3">
                        <div>
                            <h4 class="text-sm sm:text-base font-bold text-foreground leading-tight">
                                Selesaikan pembayaran anda
                            </h4>
                            <div class="flex items-center gap-1.5 mt-1.5 text-xs sm:text-sm" x-data="{ copiedId: false }">
                                <span class="text-muted-foreground font-medium">ID Pembayaran:</span>
                                <span class="font-bold text-foreground font-mono">{{ $paymentId }}</span>
                                <button type="button"
                                    @click="navigator.clipboard.writeText('{{ $paymentId }}'); copiedId = true; setTimeout(() => copiedId = false, 2000)"
                                    class="text-muted-foreground hover:text-foreground transition p-0.5"
                                    title="Salin ID Pembayaran">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                                    </svg>
                                </button>
                                <span x-show="copiedId" class="badge badge-success">Tersalin!</span>
                            </div>
                        </div>

                        <!-- Countdown Timer Badges [00] : [50] : [35] -->
                        <div class="flex items-center gap-1 shrink-0 font-mono text-xs sm:text-sm font-bold text-foreground"
                            x-data="{
                                timeLeft: 3035,
                                hh: '00',
                                mm: '50',
                                ss: '35',
                                init() {
                                    const pad = (n) => String(n).padStart(2, '0');
                                    const update = () => {
                                        if (this.timeLeft <= 0) return;
                                        const h = Math.floor(this.timeLeft / 3600);
                                        const m = Math.floor((this.timeLeft % 3600) / 60);
                                        const s = this.timeLeft % 60;
                                        this.hh = pad(h);
                                        this.mm = pad(m);
                                        this.ss = pad(s);
                                        this.timeLeft--;
                                    };
                                    update();
                                    setInterval(update, 1000);
                                }
                            }">
                            <span class="inline-flex items-center justify-center min-w-[28px] sm:min-w-[32px] h-7 sm:h-8 px-1.5 rounded-md bg-brand-700 text-white" x-text="hh">00</span>
                            <span class="text-foreground font-bold">:</span>
                            <span class="inline-flex items-center justify-center min-w-[28px] sm:min-w-[32px] h-7 sm:h-8 px-1.5 rounded-md bg-brand-700 text-white" x-text="mm">50</span>
                            <span class="text-foreground font-bold">:</span>
                            <span class="inline-flex items-center justify-center min-w-[28px] sm:min-w-[32px] h-7 sm:h-8 px-1.5 rounded-md bg-brand-700 text-white" x-text="ss">35</span>
                        </div>
                    </div>

                    <!-- Card 2: Pilih Jenis Pembayaran anda -->
                    <div class="bg-card rounded-2xl p-4 sm:p-5 shadow-2xs border border-border/50 space-y-3.5">
                        <h4 class="text-sm sm:text-base font-bold text-foreground">
                            Pilih Jenis Pembayaran anda
                        </h4>

                        <div class="space-y-2.5">
                            <!-- QRIS -->
                            <button type="button" wire:click="selectMetodePembayaran('QRIS')"
                                class="w-full py-3 sm:py-3.5 px-4 rounded-xl text-center text-xs sm:text-sm font-bold transition border {{ $metodePembayaran === 'QRIS' ? 'bg-brand-100 border-brand-700 text-brand-800 ring-2 ring-brand-700/25 shadow-xs' : 'bg-brand-50 hover:bg-brand-100 border-brand-200 text-brand-700' }}">
                                QRIS
                            </button>

                            <!-- Virrtual Account BNI -->
                            <button type="button" wire:click="selectMetodePembayaran('Virrtual Account BNI')"
                                class="w-full py-3 sm:py-3.5 px-4 rounded-xl text-center text-xs sm:text-sm font-bold transition border {{ $metodePembayaran === 'Virrtual Account BNI' ? 'bg-brand-100 border-brand-700 text-brand-800 ring-2 ring-brand-700/25 shadow-xs' : 'bg-brand-50 hover:bg-brand-100 border-brand-200 text-brand-700' }}">
                                Virrtual Account BNI
                            </button>

                            <!-- Virrtual Account MANDIRI -->
                            <button type="button" wire:click="selectMetodePembayaran('Virrtual Account MANDIRI')"
                                class="w-full py-3 sm:py-3.5 px-4 rounded-xl text-center text-xs sm:text-sm font-bold transition border {{ $metodePembayaran === 'Virrtual Account MANDIRI' ? 'bg-brand-100 border-brand-700 text-brand-800 ring-2 ring-brand-700/25 shadow-xs' : 'bg-brand-50 hover:bg-brand-100 border-brand-200 text-brand-700' }}">
                                Virrtual Account MANDIRI
                            </button>

                            <!-- Extended payment options -->
                            @if ($showAllPaymentMethods)
                                <div class="space-y-2.5 pt-1 animate-in fade-in duration-200">
                                    <button type="button" wire:click="selectMetodePembayaran('Virtual Account BCA')"
                                        class="w-full py-3 sm:py-3.5 px-4 rounded-xl text-center text-xs sm:text-sm font-bold transition border {{ $metodePembayaran === 'Virtual Account BCA' ? 'bg-brand-100 border-brand-700 text-brand-800 ring-2 ring-brand-700/25 shadow-xs' : 'bg-brand-50 hover:bg-brand-100 border-brand-200 text-brand-700' }}">
                                        Virtual Account BCA
                                    </button>
                                    <button type="button" wire:click="selectMetodePembayaran('Virtual Account BRI')"
                                        class="w-full py-3 sm:py-3.5 px-4 rounded-xl text-center text-xs sm:text-sm font-bold transition border {{ $metodePembayaran === 'Virtual Account BRI' ? 'bg-brand-100 border-brand-700 text-brand-800 ring-2 ring-brand-700/25 shadow-xs' : 'bg-brand-50 hover:bg-brand-100 border-brand-200 text-brand-700' }}">
                                        Virtual Account BRI
                                    </button>
                                    <button type="button" wire:click="selectMetodePembayaran('Virtual Account BSI')"
                                        class="w-full py-3 sm:py-3.5 px-4 rounded-xl text-center text-xs sm:text-sm font-bold transition border {{ $metodePembayaran === 'Virtual Account BSI' ? 'bg-brand-100 border-brand-700 text-brand-800 ring-2 ring-brand-700/25 shadow-xs' : 'bg-brand-50 hover:bg-brand-100 border-brand-200 text-brand-700' }}">
                                        Virtual Account BSI
                                    </button>
                                    <button type="button" wire:click="selectMetodePembayaran('Transfer Bank Manual')"
                                        class="w-full py-3 sm:py-3.5 px-4 rounded-xl text-center text-xs sm:text-sm font-bold transition border {{ $metodePembayaran === 'Transfer Bank Manual' ? 'bg-brand-100 border-brand-700 text-brand-800 ring-2 ring-brand-700/25 shadow-xs' : 'bg-brand-50 hover:bg-brand-100 border-brand-200 text-brand-700' }}">
                                        Transfer Bank Manual (BCA, Mandiri, BSI, BRI)
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Toggle link: Lihat semua jenis pembayaran lainnya -> -->
                        <div class="text-center pt-2">
                            <button type="button" wire:click="$toggle('showAllPaymentMethods')"
                                class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-brand-700 hover:text-brand-800 hover:underline transition">
                                <span>{{ $showAllPaymentMethods ? 'Sembunyikan jenis pembayaran lainnya' : 'Lihat semua jenis pembayaran lainnya' }}</span>
                                <span class="text-base leading-none">&rarr;</span>
                            </button>
                        </div>
                    </div>

                    <!-- Card 3: Rincian Pembayaran -->
                    <div class="bg-card rounded-2xl p-4 sm:p-5 shadow-2xs border border-border/50 space-y-2.5">
                        <h4 class="text-sm sm:text-base font-bold text-foreground">
                            Rincian Pembayaran
                        </h4>
                        <div class="space-y-2 text-xs sm:text-sm tabular">
                            <div class="flex items-center justify-between">
                                <span class="text-muted-foreground font-medium">Biaya Donasi</span>
                                <span class="font-bold text-foreground">Rp{{ number_format($currentDonasi?->nominal ?? $selectedNominal, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-muted-foreground font-medium">Biaya Admin</span>
                                <span class="font-bold text-foreground">Rp0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Footer Bar (Total & Tombol Bayar Sekarang) -->
                    <div class="bg-card rounded-2xl p-4 sm:p-5 shadow-2xs border border-border/50 flex items-center justify-between gap-4">
                        <div class="text-base sm:text-lg font-bold text-foreground tabular">
                            Rp{{ number_format($currentDonasi?->nominal ?? $selectedNominal, 2, ',', '.') }}
                        </div>
                        <button type="button" wire:click="payNow"
                            class="btn btn-primary rounded-xl px-6 sm:px-8 py-2.5 sm:py-3 shadow-xs hover:shadow active:scale-95">
                            Bayar Sekarang
                        </button>
                    </div>

                @else
                    <!-- STEP 2: DETAIL INSTRUKSI PEMBAYARAN & UPLOAD BUKTI -->

                    <!-- Header Card Step 2: [<- Kembali] [Yayasan Himatun] [X] -->
                    <div class="bg-card rounded-2xl px-4 sm:px-5 py-3.5 sm:py-4 flex items-center justify-between gap-2 shadow-2xs border border-border/50">
                        <button type="button" wire:click="backToStepOne"
                            class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-brand-700 hover:underline cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Kembali</span>
                        </button>
                        <h3 class="text-sm sm:text-base font-bold text-brand-900 text-center flex-1">
                            Instruksi Pembayaran
                        </h3>
                        <button type="button" wire:click="closePaymentModal" class="text-danger hover:text-red-700 transition cursor-pointer p-0.5" aria-label="Tutup">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Card ID & Timer Selesaikan Pembayaran -->
                    <div class="bg-card rounded-2xl p-4 sm:p-5 shadow-2xs border border-border/50 flex items-center justify-between gap-3">
                        <div>
                            <div class="text-xs text-muted-foreground-1 font-medium">ID Pembayaran:</div>
                            <div class="text-xs sm:text-sm font-bold text-foreground font-mono">{{ $paymentId }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-muted-foreground-1 font-medium">Metode:</div>
                            <span class="badge badge-success">
                                {{ $metodePembayaran }}
                            </span>
                        </div>
                    </div>

                    <!-- Card Detail Metode Pembayaran -->
                    <div class="bg-card rounded-2xl p-4 sm:p-5 shadow-2xs border border-border/50 space-y-4">
                        @if ($this->virtualAccountDetails['type'] === 'qris')
                            <!-- Tampilan QRIS -->
                            <div class="text-center space-y-3">
                                <div class="badge badge-success px-3 py-1">
                                    <span>QRIS Standar Nasional Bank Indonesia</span>
                                </div>

                                <div class="p-3 bg-white border border-border rounded-2xl inline-block shadow-xs">
                                    <img src="{{ asset('images/qris-code.svg') }}" alt="QRIS Yayasan Himatun Ayat" class="w-52 h-52 mx-auto" />
                                    <div class="text-2xs font-bold text-slate-700 mt-2">
                                        NMID: ID1024389201948 • Yayasan Himatun Ayat
                                    </div>
                                </div>

                                <div class="p-3 rounded-xl bg-brand-50 border border-brand-200">
                                    <div class="text-xs text-muted-foreground-1 font-medium">Total Nominal Donasi:</div>
                                    <div class="text-lg font-extrabold text-brand-900 tabular">
                                        Rp{{ number_format($currentDonasi?->nominal ?? $selectedNominal, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @elseif ($this->virtualAccountDetails['type'] === 'va')
                            <!-- Tampilan Virtual Account -->
                            <div class="space-y-3" x-data="{ copiedVa: false }">
                                <div class="flex items-center justify-between border-b border-border pb-2">
                                    <span class="text-xs font-semibold text-muted-foreground-1">Bank Tujuan:</span>
                                    <span class="badge badge-success">
                                        {{ $this->virtualAccountDetails['bank'] }}
                                    </span>
                                </div>

                                <div class="p-4 rounded-xl bg-brand-50 border border-brand-200 space-y-1">
                                    <div class="text-xs text-muted-foreground-1 font-medium">Nomor Virtual Account:</div>
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="text-base sm:text-lg font-mono font-bold text-brand-900 tracking-wider">
                                            {{ $this->virtualAccountDetails['va_number'] }}
                                        </div>
                                        <button type="button"
                                            @click="navigator.clipboard.writeText('{{ $this->virtualAccountDetails['va_number'] }}'); copiedVa = true; setTimeout(() => copiedVa = false, 2000)"
                                            class="btn btn-secondary btn-sm bg-white">
                                            <span x-show="!copiedVa">Salin</span>
                                            <span x-show="copiedVa" class="text-brand-700 font-bold">Tersalin!</span>
                                        </button>
                                    </div>
                                    <div class="text-2xs text-muted-foreground-1">
                                        Atas Nama: <strong class="text-foreground">{{ $this->virtualAccountDetails['atas_nama'] }}</strong>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between p-3 rounded-xl bg-layer border border-border">
                                    <span class="text-xs font-bold text-foreground">Total Pembayaran:</span>
                                    <span class="text-sm font-extrabold text-brand-900 tabular">
                                        Rp{{ number_format($currentDonasi?->nominal ?? $selectedNominal, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <!-- Tampilan Rekening Transfer Manual -->
                            <div class="space-y-2.5">
                                <div class="text-xs font-bold text-foreground">Pilih Rekening Resmi Yayasan:</div>
                                @foreach ($bankAccounts as $acc)
                                    <div class="p-3 rounded-xl border border-border bg-layer flex items-center justify-between">
                                        <div class="flex items-center gap-2.5">
                                            <span class="badge badge-success">
                                                {{ $acc['bank'] }}
                                            </span>
                                            <div>
                                                <div class="font-mono font-bold text-xs text-foreground">{{ $acc['no_rekening'] }}</div>
                                                <div class="text-2xs text-muted-foreground-1">a.n. {{ $acc['atas_nama'] }}</div>
                                            </div>
                                        </div>
                                        <button type="button"
                                            @click="navigator.clipboard.writeText('{{ $acc['no_rekening'] }}'); copiedBank = '{{ $acc['bank'] }}'; setTimeout(() => copiedBank = null, 2000)"
                                            class="btn btn-secondary btn-sm bg-white">
                                            <span x-show="copiedBank !== '{{ $acc['bank'] }}'">Salin</span>
                                            <span x-show="copiedBank === '{{ $acc['bank'] }}'" class="text-brand-700 font-bold">Tersalin!</span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Petunjuk Pembayaran -->
                        <div class="pt-2 border-t border-border">
                            <div class="text-xs font-bold text-foreground mb-2">Petunjuk Pembayaran:</div>
                            <ol class="space-y-1.5 text-xs text-muted-foreground-1 list-decimal list-inside pl-1">
                                @foreach ($this->virtualAccountDetails['cara_bayar'] as $step)
                                    <li>{{ $step }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>

                    <!-- Card Form Upload Bukti Transfer & Konfirmasi -->
                    <div class="bg-card rounded-2xl p-4 sm:p-5 shadow-2xs border border-border/50 space-y-3.5">
                        <label class="form-label text-xs font-bold mb-0">
                            Upload Bukti Transfer (Opsional / Dapat disusulkan):
                        </label>

                        <div class="relative border-2 border-dashed border-brand-300 hover:border-brand-500 rounded-xl p-3.5 text-center bg-brand-50/50 transition">
                            <input type="file" wire:model="buktiTransfer" accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                            <div class="space-y-1">
                                <svg class="w-6 h-6 mx-auto text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-xs font-medium text-foreground">
                                    @if ($buktiTransfer)
                                        <span class="text-brand-700 font-bold">{{ $buktiTransfer->getClientOriginalName() }}</span>
                                    @else
                                        Klik atau seret foto bukti transfer di sini (JPG/PNG max 5MB)
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div wire:loading wire:target="buktiTransfer" class="text-xs text-brand-700 font-semibold">
                            Mengunggah pratinjau berkas...
                        </div>
                        @error('buktiTransfer')
                            <p class="form-error">{{ $message }}</p>
                        @enderror

                        <!-- Tombol Aksi Step 2 -->
                        <div class="flex flex-col sm:flex-row gap-2 pt-1">
                            @if ($buktiTransfer)
                                <button type="button" wire:click="uploadBuktiTransfer"
                                    class="btn btn-primary rounded-xl w-full py-3 text-xs sm:text-sm font-bold shadow-sm transition">
                                    Kirim Bukti Pembayaran & Selesai
                                </button>
                            @else
                                <button type="button" wire:click="completeWithoutUpload"
                                    class="btn btn-primary rounded-xl w-full py-3 text-xs sm:text-sm font-bold shadow-sm transition">
                                    Saya Sudah Bayar / Selesai
                                </button>
                            @endif

                            <a href="{{ $this->whatsappLink }}" target="_blank"
                                class="btn btn-secondary rounded-xl w-full py-3 text-brand-700 hover:text-brand-800 text-xs sm:text-sm font-bold text-center border-brand-200 transition">
                                Konfirmasi via WhatsApp ↗
                            </a>
                        </div>
                    </div>

                @endif

            </div>
        </div>
    @endif

    <!-- Modal Sukses Donasi -->
    @if ($showSuccessModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity animate-in fade-in">
            <div class="bg-card rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-border text-center space-y-4">
                <div class="w-16 h-16 rounded-full bg-brand-100 text-brand-700 mx-auto flex items-center justify-center">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h3 class="text-xl font-extrabold text-foreground">
                    Jazakumullah Khairan Katsiran!
                </h3>

                <p class="text-xs sm:text-sm text-muted-foreground-1 leading-relaxed">
                    Terima kasih atas kebaikan dan ketulusan Anda. Donasi Anda telah kami catat dan akan disalurkan dengan penuh amanah untuk kebutuhan para santri yatim dan dhuafa.
                </p>

                <div class="p-3.5 rounded-2xl bg-brand-50 border border-brand-200 text-xs text-brand-800 font-medium">
                    Doa dan harapan Anda telah tersimpan dan akan diaminkan bersama adik-adik santri binaan.
                </div>

                <div class="pt-2 flex flex-col gap-2">
                    <a href="{{ $this->whatsappLink }}" target="_blank"
                        class="btn btn-primary rounded-full w-full py-3 text-xs sm:text-sm font-bold shadow-md transition">
                        Kirim Bukti ke WhatsApp Admin Yayasan
                    </a>
                    <button type="button" wire:click="closeSuccessModal"
                        class="btn btn-ghost rounded-full w-full py-2.5 text-xs font-semibold transition">
                        Kembali ke Halaman Program
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Script Smooth Scroll listener -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('scroll-to-form', () => {
                const target = document.getElementById('formulir-donatur');
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</div>
