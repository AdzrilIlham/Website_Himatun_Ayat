<div id="kampanye-page" class="relative overflow-hidden" x-data="{ copiedBank: null }">

    <!-- Scroll target helper -->
    <div id="formulir-donatur" class="scroll-mt-28"></div>

    <!-- Section 1: Hero, Formulir Donatur & Doa Harapan -->
    <section class="relative pt-6 pb-16 sm:pb-20">
        <!-- Ambient background glow matching mockup -->
        <div class="absolute inset-0 -z-10 pointer-events-none overflow-hidden">
            <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-emerald-100/40 blur-3xl"></div>
            <div class="absolute top-20 right-0 w-[500px] h-[500px] rounded-full bg-[#e8f5ec]/60 blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

                <!-- Kolom Kiri: Formulir Donatur -->
                <div class="lg:col-span-6 xl:col-span-6">
                    <div class="bg-[#edf5f0] border border-[#d7e9dd] rounded-[32px] p-6 sm:p-8 lg:p-9 shadow-sm relative">
                        <h2 class="text-2xl sm:text-[26px] font-bold text-slate-900 text-center mb-6 tracking-tight">
                            Formulir Donatur
                        </h2>

                        <form wire:submit.prevent="confirmDonasi" class="space-y-5">
                            <!-- Program Donasi Select -->
                            <div>
                                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                                    Program Donasi
                                </label>
                                <div class="relative">
                                    <select wire:model.live="selectedKampanyeId"
                                        class="w-full appearance-none rounded-xl border border-slate-200/90 bg-white px-4 py-3 text-xs sm:text-sm font-medium text-slate-800 shadow-xs focus:border-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-700/20 pr-10">
                                        <option value="">-- Donasi Umum / Semua Program --</option>
                                        @foreach ($kampanyeList as $k)
                                            <option value="{{ $k->id }}">{{ $k->judul }}</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5 text-slate-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('selectedKampanyeId')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pilih Nominal Donasi Preset Buttons -->
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs sm:text-sm font-semibold text-slate-700">
                                        Pilih Nominal Donasi <span class="text-red-500">*</span>
                                    </label>
                                    <span class="text-xs text-emerald-800 font-medium">Min. Rp 10.000</span>
                                </div>

                                <div class="grid grid-cols-4 gap-2 sm:gap-2.5">
                                    @php
                                        $presetNominals = [10000, 20000, 50000, 100000, 150000, 250000, 500000, 1000000];
                                    @endphp
                                    @foreach ($presetNominals as $nominal)
                                        <button type="button"
                                            wire:click="setNominal({{ $nominal }})"
                                            class="py-2.5 px-1 sm:px-2 rounded-full text-xs sm:text-sm font-semibold transition-all duration-150 border text-center {{ $selectedNominal === $nominal ? 'bg-[#244b37] text-white border-[#244b37] shadow-xs' : 'bg-white/90 hover:bg-white text-[#244b37] border-[#d1e5d8] hover:border-[#244b37]' }}">
                                            Rp{{ number_format($nominal, 0, ',', '.') }}
                                        </button>
                                    @endforeach
                                </div>

                                <!-- Input Nominal Custom -->
                                <div class="mt-3 relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-xs sm:text-sm font-bold text-slate-400">
                                        Rp
                                    </div>
                                    <input type="number" wire:model.live.debounce.300ms="customNominal" min="10000" step="5000"
                                        placeholder="Nominal lainnya (contoh: 75000)"
                                        class="w-full rounded-xl border border-slate-200/90 bg-white py-2.5 pl-11 pr-4 text-xs sm:text-sm font-semibold text-slate-800 shadow-xs focus:border-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-700/20" />
                                </div>
                                @error('selectedNominal')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Row: Nama Lengkap & No Telepon -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                                        Nama Lengkap Donatur <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="namaDonatur"
                                        {{ $isAnonim ? 'readonly' : '' }}
                                        placeholder="Masukan Teks"
                                        class="w-full rounded-xl border border-slate-200/90 bg-white px-4 py-3 text-xs sm:text-sm text-slate-800 shadow-xs focus:border-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-700/20 {{ $isAnonim ? 'bg-slate-100 text-slate-500' : '' }}" />
                                    @error('namaDonatur')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                                        No. Telepon / WA <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" wire:model="noTelepon"
                                        placeholder="Masukan Teks"
                                        class="w-full rounded-xl border border-slate-200/90 bg-white px-4 py-3 text-xs sm:text-sm text-slate-800 shadow-xs focus:border-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-700/20" />
                                    @error('noTelepon')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Alamat Email (Opsional) -->
                            <div>
                                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                                    Alamat Email (Opsional)
                                </label>
                                <input type="email" wire:model="email"
                                    placeholder="Masukan Teks"
                                    class="w-full rounded-xl border border-slate-200/90 bg-white px-4 py-3 text-xs sm:text-sm text-slate-800 shadow-xs focus:border-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-700/20" />
                                @error('email')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Checkbox: Rahasiakan Nama Anda Menjadi Hamba Allah -->
                            <div class="flex items-center gap-2.5 pt-1">
                                <input type="checkbox" id="hambaAllahCheckbox" wire:model.live="isAnonim"
                                    class="w-4 h-4 rounded text-emerald-800 border-slate-300 focus:ring-emerald-700" />
                                <label for="hambaAllahCheckbox" class="text-xs sm:text-sm font-medium text-slate-700 cursor-pointer select-none">
                                    Rahasiakan Nama Anda Menjadi Hamba Allah
                                </label>
                            </div>

                            <!-- Doa & Harapan Donatur -->
                            <div>
                                <label class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">
                                    Doa & Harapan Donatur
                                </label>
                                <textarea wire:model="doaHarapan" rows="3"
                                    placeholder="Masukan Teks"
                                    class="w-full rounded-xl border border-slate-200/90 bg-white px-4 py-3 text-xs sm:text-sm text-slate-800 shadow-xs focus:border-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-700/20 resize-none"></textarea>
                                @error('doaHarapan')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-2 text-center">
                                <button type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-10 py-3.5 rounded-full bg-[#27533d] hover:bg-[#1d402e] text-white text-sm sm:text-base font-semibold shadow-md hover:shadow-lg transition-all duration-200 transform active:scale-95">
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
                        <h1 class="text-3xl sm:text-4xl lg:text-[46px] font-extrabold text-slate-900 leading-[1.18] tracking-tight">
                            Donasi Anda<br/>Membantu Kami
                        </h1>
                        <p class="text-xs sm:text-sm lg:text-base text-slate-600 leading-relaxed mt-4">
                            Berapa pun yang Anda berikan sangat berarti bagi kami. Karena bagi kami, yang terpenting bukanlah jumlahnya, melainkan keikhlasan dan niat baik di balik setiap donasi. Donasi yang Anda berikan akan disalurkan untuk membantu mereka yang membutuhkan serta mendukung berbagai program sosial dan kemanusiaan Yayasan Himatun.
                        </p>
                    </div>

                    @php
                        $doaChunks = $recentDoa->chunk(2);
                    @endphp

                    <!-- Card Doa & Harapan Sesuai Mockup (Auto-rotating, No Manual Scroll) -->
                    <div class="bg-[#e4f3eb] border border-[#d0e9db] rounded-[32px] p-6 sm:p-7 shadow-xs relative overflow-hidden"
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
                                <h3 class="text-xl font-bold text-slate-900 tracking-tight">
                                    Doa & Harapan
                                </h3>
                                <p class="text-xs text-slate-600 mt-0.5 font-medium">
                                    Untaian kebaikan dari para donatur
                                </p>
                            </div>

                            @if ($doaChunks->count() > 1)
                                <!-- Live indicator & mini progress dots -->
                                <div class="flex items-center gap-1.5">
                                    <div class="flex items-center gap-1 ml-1">
                                        @foreach ($doaChunks as $idx => $chunk)
                                            <button type="button" @click="currentGroup = {{ $idx }}"
                                                :class="currentGroup === {{ $idx }} ? 'bg-emerald-800 w-3' : 'bg-emerald-300 w-1.5'"
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
                                        <div class="bg-white rounded-2xl p-4 sm:p-4.5 shadow-2xs border border-emerald-100/70 transition hover:shadow-xs">
                                            <div class="flex items-center gap-3">
                                                <!-- Avatar Icon Bulat -->
                                                <div class="w-9 h-9 rounded-full bg-[#2d5c45] text-white flex items-center justify-center shrink-0">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="font-bold text-xs sm:text-sm text-slate-900 leading-tight">
                                                        {{ $doa->nama_donatur }}
                                                    </div>
                                                    <div class="text-[11px] text-slate-500 font-medium">
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
                                <div class="bg-white rounded-2xl p-6 text-center text-slate-400 text-xs">
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
    <section class="py-16 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Paket Kebaikan -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Hasil Donasi Yang Anda Berikan
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
                        Pilih paket kebaikan untuk mendukung kebutuhan esensial para santri yatim dhuafa dengan penyaluran langsung.
                    </p>
                </div>

                <!-- Panah Carousel Slider Sesuai Mockup -->
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <button type="button" wire:click="prevSlide({{ $kampanyeList->count() }})"
                        class="w-9 h-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 flex items-center justify-center text-slate-600 transition shadow-xs active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button type="button" wire:click="nextSlide({{ $kampanyeList->count() }})"
                        class="w-9 h-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 flex items-center justify-center text-slate-600 transition shadow-xs active:scale-95">
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
                            'is_featured' => true, // Featured card with dark green styling
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

                    <div class="relative rounded-[28px] p-5 sm:p-6 flex flex-col justify-between transition-all duration-200 hover:-translate-y-1 hover:shadow-lg {{ $isDark ? 'bg-[#244b37] text-white shadow-md' : 'bg-[#eaf4ee] text-slate-800 border border-[#d6e7dc]/70' }}">

                        <div>
                            <!-- Header Card: Badge Kategori -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold {{ $isDark ? 'bg-white/15 text-emerald-100 border border-white/20' : 'bg-white/80 text-emerald-800 border border-emerald-200/50' }}">
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
                            <div class="rounded-2xl overflow-hidden aspect-4/3 mb-4 bg-white/40 flex items-center justify-center p-2">
                                <img src="{{ $cfg['image'] }}" alt="{{ $item->judul }}"
                                    class="w-full h-full object-contain drop-shadow-xs transition transform hover:scale-105 duration-200" />
                            </div>

                            <!-- Judul & Deskripsi -->
                            <h3 class="font-bold text-base sm:text-lg mb-1.5 leading-snug {{ $isDark ? 'text-white' : 'text-slate-900' }}">
                                {{ $item->judul }}
                            </h3>
                            <p class="text-xs leading-relaxed mb-4 {{ $isDark ? 'text-emerald-100/90' : 'text-slate-600' }}">
                                {{ $item->deskripsi }}
                            </p>
                        </div>

                        <!-- Nominal & Tombol Pilih Donasi -->
                        <div class="pt-4 border-t {{ $isDark ? 'border-white/15' : 'border-slate-200/60' }}">
                            <div class="mb-3">
                                <span class="block text-[11px] font-medium {{ $isDark ? 'text-emerald-200' : 'text-slate-500' }}">
                                    Nominal Donasi:
                                </span>
                                <span class="text-lg font-extrabold tracking-tight {{ $isDark ? 'text-white' : 'text-slate-900' }}">
                                    Rp {{ number_format($cfg['nominal'], 0, ',', '.') }}
                                </span>
                            </div>

                            <button type="button"
                                wire:click="selectKampanye({{ $item->id }}, {{ $cfg['nominal'] }})"
                                class="w-full py-3 px-4 rounded-xl text-xs sm:text-sm font-bold flex items-center justify-center gap-2 transition-all duration-150 active:scale-95 {{ $isDark ? 'bg-[#e5f3ea] hover:bg-white text-[#244b37] shadow-sm' : 'bg-[#27533d] hover:bg-[#1d402e] text-white shadow-xs' }}">
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
                <button type="button" wire:click="setSlide(0)" class="w-2.5 h-2.5 rounded-full transition-all {{ $activeSlide === 0 ? 'bg-emerald-800 w-6' : 'bg-slate-300' }}"></button>
                <button type="button" wire:click="setSlide(1)" class="w-2.5 h-2.5 rounded-full transition-all {{ $activeSlide === 1 ? 'bg-emerald-800 w-6' : 'bg-slate-300' }}"></button>
                <button type="button" wire:click="setSlide(2)" class="w-2.5 h-2.5 rounded-full transition-all {{ $activeSlide === 2 ? 'bg-emerald-800 w-6' : 'bg-slate-300' }}"></button>
            </div>
        </div>
    </section>

    <!-- Section 3: Call to Action Banner Media Sosial Sesuai Mockup -->
    <section class="py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative bg-[#204533] rounded-[24px] p-6 sm:p-8 lg:p-10 text-white shadow-md overflow-hidden">
                <!-- Background subtle radial glow -->
                <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-emerald-700/20 blur-2xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <!-- Teks Kiri -->
                    <div class="space-y-2 max-w-3xl">
                        <div class="flex items-center gap-2 text-xs font-bold text-emerald-200 tracking-wider">
                            <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                            <span>TERHUBUNG BERSAMA KAMI</span>
                        </div>
                        <h3 class="text-xl sm:text-2xl lg:text-[26px] font-extrabold leading-snug">
                            Ikuti Keseharian & Live Story Adik-adik Asuh di Media Sosial
                        </h3>
                        <p class="text-xs sm:text-sm text-emerald-100/80 leading-relaxed">
                            Saksikan langsung penyaluran doa dan kebaikan Anda melalui akun resmi Instagram Yayasan Himmatun Ayat Bandung
                        </p>
                    </div>

                    <!-- Tombol Instagram Kanan -->
                    <div class="shrink-0">
                        <a href="https://instagram.com/himmatunayatbandung" target="_blank"
                            class="inline-flex items-center gap-2.5 px-6 py-3 rounded-full bg-white/10 hover:bg-white/20 border border-white/25 text-white font-semibold text-xs sm:text-sm shadow-xs hover:shadow-md transition backdrop-blur-xs">
                            <!-- Instagram Camera Icon -->
                            <svg class="w-4 h-4 text-emerald-200" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                            <span>@himmatunayatbandung</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Konfirmasi Donasi & Pembayaran / Upload Bukti -->
    @if ($showPaymentModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity animate-in fade-in">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-slate-100 max-h-[92vh] overflow-y-auto">

                <!-- Header Modal -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Instruksi Pembayaran Donasi</h3>
                        <p class="text-xs text-slate-500">Selesaikan donasi Anda dengan transfer bank atau QRIS</p>
                    </div>
                    <button type="button" wire:click="$set('showPaymentModal', false)" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Ringkasan Donasi -->
                <div class="mt-4 p-4 rounded-2xl bg-[#edf6f1] border border-[#d3e8dc] space-y-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500 font-medium">Program:</span>
                        <span class="font-bold text-slate-800">{{ $currentDonasi?->kampanye?->judul ?? 'Donasi Umum Santri' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500 font-medium">Atas Nama:</span>
                        <span class="font-bold text-slate-800">{{ $currentDonasi?->nama_donatur }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs pt-1 border-t border-[#c6e0d0]">
                        <span class="text-slate-600 font-bold">Total Nominal:</span>
                        <span class="text-base font-extrabold text-emerald-800">
                            Rp {{ number_format($currentDonasi?->nominal ?? $selectedNominal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Pilihan Rekening Pembayaran -->
                <div class="mt-5 space-y-3">
                    <label class="block text-xs font-bold text-slate-700">
                        Transfer ke Rekening Resmi Yayasan:
                    </label>

                    <div class="space-y-2">
                        @foreach ($bankAccounts as $acc)
                            <div class="p-3 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-[#244b37] text-white">
                                        {{ $acc['bank'] }}
                                    </span>
                                    <div>
                                        <div class="font-bold text-xs text-slate-800 tracking-wider">
                                            {{ $acc['no_rekening'] }}
                                        </div>
                                        <div class="text-[10px] text-slate-500">
                                            a.n. {{ $acc['atas_nama'] }}
                                        </div>
                                    </div>
                                </div>
                                <button type="button"
                                    @click="navigator.clipboard.writeText('{{ $acc['no_rekening'] }}'); copiedBank = '{{ $acc['bank'] }}'; setTimeout(() => copiedBank = null, 2000)"
                                    class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-100 transition shadow-2xs">
                                    <span x-show="copiedBank !== '{{ $acc['bank'] }}'">Salin</span>
                                    <span x-show="copiedBank === '{{ $acc['bank'] }}'" class="text-emerald-700 font-bold">Tersalin!</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Form Upload Bukti Transfer -->
                <div class="mt-6 pt-5 border-t border-slate-100">
                    <label class="block text-xs font-bold text-slate-700 mb-2">
                        Upload Bukti Transfer (Opsional / Dapat disusulkan)
                    </label>

                    <div class="relative border-2 border-dashed border-emerald-300 hover:border-emerald-500 rounded-2xl p-4 text-center bg-emerald-50/40 transition">
                        <input type="file" wire:model="buktiTransfer" accept="image/*"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                        <div class="space-y-1">
                            <svg class="w-7 h-7 mx-auto text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-xs font-medium text-slate-700">
                                @if ($buktiTransfer)
                                    <span class="text-emerald-700 font-bold">{{ $buktiTransfer->getClientOriginalName() }}</span>
                                @else
                                    Klik atau seret foto bukti transfer di sini (JPG/PNG max 5MB)
                                @endif
                            </p>
                        </div>
                    </div>

                    <div wire:loading wire:target="buktiTransfer" class="text-xs text-emerald-700 font-semibold mt-1">
                        Mengunggah pratinjau berkas...
                    </div>
                    @error('buktiTransfer')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Action Buttons Modal -->
                    <div class="mt-6 flex flex-col sm:flex-row gap-2.5">
                        @if ($buktiTransfer)
                            <button type="button" wire:click="uploadBuktiTransfer"
                                class="w-full py-3 rounded-full bg-[#244b37] hover:bg-[#1a3829] text-white text-xs sm:text-sm font-bold shadow-md transition">
                                Kirim Bukti Transfer & Selesai
                            </button>
                        @else
                            <button type="button" wire:click="completeWithoutUpload"
                                class="w-full py-3 rounded-full bg-[#244b37] hover:bg-[#1a3829] text-white text-xs sm:text-sm font-bold shadow-md transition">
                                Saya Sudah Transfer / Selesai
                            </button>
                        @endif

                        <a href="{{ $this->whatsappLink }}" target="_blank"
                            class="w-full py-3 rounded-full bg-emerald-100 hover:bg-emerald-200 text-emerald-900 text-xs sm:text-sm font-bold text-center border border-emerald-300 transition">
                            Konfirmasi via WhatsApp ↗
                        </a>
                    </div>
                </div>

            </div>
        </div>
    @endif

    <!-- Modal Sukses Donasi -->
    @if ($showSuccessModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity animate-in fade-in">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100 text-center space-y-4">
                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-700 mx-auto flex items-center justify-center">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h3 class="text-xl font-extrabold text-slate-900">
                    Jazakumullah Khairan Katsiran!
                </h3>

                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Terima kasih atas kebaikan dan ketulusan Anda. Donasi Anda telah kami catat dan akan disalurkan dengan penuh amanah untuk kebutuhan para santri yatim dan dhuafa.
                </p>

                <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-xs text-emerald-900 font-medium">
                    Doa dan harapan Anda telah tersimpan dan akan diaminkan bersama adik-adik santri binaan.
                </div>

                <div class="pt-2 flex flex-col gap-2">
                    <a href="{{ $this->whatsappLink }}" target="_blank"
                        class="w-full py-3 rounded-full bg-[#244b37] hover:bg-[#1a3829] text-white text-xs sm:text-sm font-bold shadow-md transition">
                        Kirim Bukti ke WhatsApp Admin Yayasan
                    </a>
                    <button type="button" wire:click="closeSuccessModal"
                        class="w-full py-2.5 rounded-full text-slate-600 hover:bg-slate-100 text-xs font-semibold transition">
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
