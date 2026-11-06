    <!-- Section 1: Hero, Formulir Donatur & Doa Harapan -->
    <section class="relative pt-6 pb-16 sm:pb-20">
        <!-- Ambient Radial Glows (Mint di kiri atas, Warm Amber Sunshine di kanan atas) sesuai beranda -->
        <div class="absolute -top-24 left-0 w-full sm:w-2/3 h-[600px] bg-gradient-to-br from-[#BFEFE0]/85 via-[#DCF6ED]/45 to-transparent pointer-events-none blur-3xl -z-10"></div>
        <div class="absolute -top-20 right-0 w-full sm:w-3/5 h-[650px] bg-gradient-to-bl from-[#FDE899]/70 via-[#FEF3C7]/40 to-transparent pointer-events-none blur-3xl -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

                <!-- Kolom Kiri: Formulir Donatur -->
                <div class="lg:col-span-6 xl:col-span-6">
                    <div class="bg-white/95 backdrop-blur-sm border border-slate-200/80 rounded-[32px] p-6 sm:p-8 lg:p-9 shadow-lg relative">
                        <h2 class="text-2xl sm:text-[26px] font-bold text-foreground text-center mb-6 tracking-tight">
                            Formulir Donatur
                        </h2>

                        <form wire:submit.prevent="confirmDonasi" class="space-y-5">
                            <!-- Program Donasi Custom Select Dropdown (Sesuai Design System & UI Website) -->
                            <div>
                                <label class="form-label text-xs sm:text-sm font-semibold">
                                    Program Donasi
                                </label>
                                @php
                                    $activeKampanyeTitle = $selectedKampanyeId
                                        ? ($kampanyeList->firstWhere('id', $selectedKampanyeId)?->judul ?? 'Donasi Umum')
                                        : 'Donasi Umum';
                                @endphp
                                <div class="relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
                                    <!-- Toggle Button -->
                                    <button type="button"
                                        @click="open = !open"
                                        :aria-expanded="open"
                                        :class="open ? 'border-brand-700 ring-2 ring-brand-700/20' : 'border-border hover:border-brand-300'"
                                        class="form-input bg-white py-3 ps-4 pe-10 text-xs sm:text-sm font-medium text-left flex items-center justify-between cursor-pointer transition-all">
                                        <span class="truncate {{ $selectedKampanyeId ? 'text-foreground font-semibold' : 'text-muted-foreground-1' }}">
                                            {{ $activeKampanyeTitle }}
                                        </span>
                                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3.5">
                                            <svg class="w-4 h-4 transition-transform duration-200"
                                                :class="open ? 'rotate-180 text-brand-700' : 'text-muted-foreground'"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </span>
                                    </button>

                                    <!-- Dropdown Options Menu -->
                                    <div x-show="open"
                                        x-cloak
                                        x-transition:enter="transition ease-out duration-150"
                                        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-100"
                                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                        class="absolute left-0 right-0 mt-2 z-50 w-full max-h-72 p-1.5 space-y-0.5 bg-white border border-slate-200/90 rounded-2xl shadow-xl overflow-hidden overflow-y-auto"
                                        role="listbox">

                                        <!-- Opsi Default: Donasi Umum / Semua Program -->
                                        <button type="button"
                                            wire:click="$set('selectedKampanyeId', null)"
                                            @click="open = false"
                                            class="py-2.5 px-3.5 w-full text-left text-xs sm:text-sm rounded-xl cursor-pointer flex items-center justify-between transition-colors {{ is_null($selectedKampanyeId) ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-slate-700 font-medium hover:bg-brand-50/70 hover:text-brand-700' }}"
                                            role="option"
                                            aria-selected="{{ is_null($selectedKampanyeId) ? 'true' : 'false' }}">
                                            <span class="truncate"> Donasi Umum </span>
                                            @if (is_null($selectedKampanyeId))
                                                <svg class="shrink-0 size-4 text-brand-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="20 6 9 17 4 12"/>
                                                </svg>
                                            @endif
                                        </button>

                                        <!-- Daftar Program Kampanye Aktif -->
                                        @foreach ($kampanyeList as $k)
                                            @php
                                                $isSelected = (int) $selectedKampanyeId === (int) $k->id;
                                            @endphp
                                            <button type="button"
                                                wire:click="$set('selectedKampanyeId', {{ $k->id }})"
                                                @click="open = false"
                                                class="py-2.5 px-3.5 w-full text-left text-xs sm:text-sm rounded-xl cursor-pointer flex items-center justify-between transition-colors {{ $isSelected ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-slate-700 font-medium hover:bg-brand-50/70 hover:text-brand-700' }}"
                                                role="option"
                                                aria-selected="{{ $isSelected ? 'true' : 'false' }}">
                                                <span class="truncate">{{ $k->judul }}</span>
                                                @if ($isSelected)
                                                    <svg class="shrink-0 size-4 text-brand-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="20 6 9 17 4 12"/>
                                                    </svg>
                                                @endif
                                            </button>
                                        @endforeach
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
                                        class="form-input bg-white py-2.5 pl-11 pr-4 text-xs sm:text-sm font-semibold tabular [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:m-0 [&::-webkit-inner-spin-button]:m-0" />
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
                                    <input type="text" wire:model.live.debounce.300ms="namaDonatur"
                                        @readonly($isAnonim)
                                        placeholder="Masukan Teks"
                                        class="form-input py-3 text-xs sm:text-sm {{ $isAnonim ? '!bg-slate-100 !text-slate-600 !border-slate-200 cursor-not-allowed select-none' : 'bg-white text-foreground' }}" />
                                    @error('namaDonatur')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="form-label text-xs sm:text-sm font-semibold">
                                        No. Telepon / WA <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" wire:model.live.debounce.300ms="noTelepon"
                                        autocomplete="off"
                                        placeholder="Masukan Teks"
                                        class="form-input bg-white py-3 text-xs sm:text-sm text-foreground" />
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
                                <input type="email" wire:model.live.debounce.300ms="email"
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
                                <textarea wire:model.live.debounce.300ms="doaHarapan" rows="3"
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
                        $doaItems = $recentDoa->values();
                        $doaCount = $doaItems->count();
                        $visibleCount = 3;
                        $canScrollDoa = $doaCount > $visibleCount;
                        $displayDoaItems = $canScrollDoa
                            ? $doaItems->concat($doaItems->take($visibleCount))
                            : $doaItems;
                    @endphp

                    <!-- Card Doa & Harapan Sesuai Mockup (Smooth Auto-Scrolling Vertical Carousel, Tanpa Pagination Dots) -->
                    <div class="bg-white/95 backdrop-blur-sm border border-slate-200/80 rounded-[32px] p-6 sm:p-7 shadow-lg relative overflow-hidden"
                        x-data="{
                            currentIndex: 0,
                            totalItems: {{ $doaCount }},
                            canScroll: {{ $canScrollDoa ? 'true' : 'false' }},
                            paused: false,
                            isTransitioning: true,
                            offsetY: 0,
                            viewportHeight: 310,
                            intervalId: null,
                            init() {
                                this.$nextTick(() => {
                                    this.updateMeasurements();
                                });
                                if (this.canScroll) {
                                    this.intervalId = setInterval(() => {
                                        if (!this.paused) {
                                            this.next();
                                        }
                                    }, 3200);
                                }
                            },
                            updateMeasurements() {
                                const track = this.$refs.doaTrack;
                                if (!track || !track.children.length) return;
                                const firstChild = track.children[this.currentIndex];
                                if (!firstChild) return;
                                this.offsetY = firstChild.offsetTop;

                                const lastVisibleIdx = Math.min(this.currentIndex + 2, track.children.length - 1);
                                const lastChild = track.children[lastVisibleIdx];
                                if (lastChild) {
                                    this.viewportHeight = (lastChild.offsetTop + lastChild.offsetHeight) - firstChild.offsetTop + 4;
                                }
                            },
                            next() {
                                if (!this.canScroll) return;
                                this.isTransitioning = true;
                                this.currentIndex++;
                                this.updateMeasurements();

                                if (this.currentIndex >= this.totalItems) {
                                    setTimeout(() => {
                                        this.isTransitioning = false;
                                        this.currentIndex = 0;
                                        this.updateMeasurements();
                                    }, 700);
                                }
                            },
                            destroy() {
                                if (this.intervalId) clearInterval(this.intervalId);
                            }
                        }"
                        @resize.window.debounce.150ms="updateMeasurements()"
                        @mouseenter="paused = true"
                        @mouseleave="paused = false">

                        <!-- Header Doa & Harapan (Tanpa Pagination Dots) -->
                        <div class="mb-5">
                            <h3 class="text-xl font-bold text-foreground tracking-tight">
                                Doa & Harapan
                            </h3>
                            <p class="text-xs text-muted-foreground-1 mt-0.5 font-medium">
                                Untaian kebaikan dari para donatur
                            </p>
                        </div>

                        <!-- Area Kartu Doa Otomatis Bergulir Halus (Smooth Vertical Scroll) -->
                        @if ($doaCount > 0)
                            <div class="relative overflow-hidden transition-[height] duration-500 ease-out px-0.5 -mx-0.5"
                                :style="`height: ${viewportHeight}px`">
                                <div x-ref="doaTrack"
                                    :class="isTransitioning ? 'transition-transform duration-700 ease-[cubic-bezier(0.22,1,0.36,1)]' : 'transition-none'"
                                    :style="`transform: translateY(-${offsetY}px)`"
                                    class="space-y-3.5 w-full will-change-transform">
                                    @foreach ($displayDoaItems as $doa)
                                        <div class="bg-white rounded-2xl p-4 sm:p-4.5 shadow-2xs border border-slate-200/80 transition hover:shadow-xs">
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
                            </div>
                        @else
                            <div class="bg-card rounded-2xl p-6 text-center text-muted-foreground text-xs">
                                Belum ada untaian doa. Jadilah donatur pertama yang mengirimkan doa!
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>