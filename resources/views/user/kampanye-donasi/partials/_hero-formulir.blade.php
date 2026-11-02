    <!-- Section 1: Hero, Formulir Donatur & Doa Harapan -->
    <section class="relative pt-6 pb-16 sm:pb-20">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

                <!-- Kolom Kiri: Formulir Donatur -->
                <div class="lg:col-span-6 xl:col-span-6">
                    <div class="bg-white/95 backdrop-blur-sm border border-slate-200/80 rounded-[32px] p-6 sm:p-8 lg:p-9 shadow-lg relative">
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
                    <div class="bg-white/95 backdrop-blur-sm border border-slate-200/80 rounded-[32px] p-6 sm:p-7 shadow-lg relative overflow-hidden"
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