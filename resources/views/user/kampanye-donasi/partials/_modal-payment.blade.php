    <!-- Modal Konfirmasi Donasi & Pembayaran Sesuai Mockup & Design System Tokens -->
    @if ($showPaymentModal)
        <div wire:key="payment-modal-{{ $paymentId }}"
            x-data="{
                show: false,
                expiresAt: {{ $paymentExpiresAt ?? (now()->addMinutes(5)->timestamp) }},
                hh: '00',
                mm: '05',
                ss: '00',
                expired: false,
                intervalId: null,
                init() {
                    requestAnimationFrame(() => {
                        this.show = true;
                    });
                    const pad = (n) => String(Math.max(0, n)).padStart(2, '0');
                    const update = () => {
                        const nowSec = Math.floor(Date.now() / 1000);
                        const remaining = this.expiresAt - nowSec;
                        if (remaining <= 0) {
                            this.hh = '00';
                            this.mm = '00';
                            this.ss = '00';
                            if (!this.expired) {
                                this.expired = true;
                                if (this.intervalId) clearInterval(this.intervalId);
                                this.show = false;
                                setTimeout(() => {
                                    $wire.expirePayment();
                                }, 280);
                            }
                            return;
                        }
                        const h = Math.floor(remaining / 3600);
                        const m = Math.floor((remaining % 3600) / 60);
                        const s = remaining % 60;
                        this.hh = pad(h);
                        this.mm = pad(m);
                        this.ss = pad(s);
                    };
                    update();
                    this.intervalId = setInterval(update, 1000);
                },
                closeModal() {
                    if (this.intervalId) clearInterval(this.intervalId);
                    this.show = false;
                    setTimeout(() => {
                        $wire.closePaymentModal();
                    }, 250);
                },
                destroy() {
                    if (this.intervalId) clearInterval(this.intervalId);
                }
            }"
            :class="show ? 'opacity-100' : 'opacity-0'"
            class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-300 ease-out overflow-y-auto no-scrollbar [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden">
            <div :class="show ? 'opacity-100 scale-100 translate-y-0' : 'opacity-0 scale-95 translate-y-3'"
                class="bg-layer rounded-[28px] sm:rounded-[32px] max-w-lg w-full p-3.5 sm:p-5 shadow-2xl border border-border space-y-3 sm:space-y-3.5 my-auto max-h-[95vh] overflow-y-auto no-scrollbar [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden text-foreground transition-all duration-300 ease-out transform">

                @if ($paymentStep === 1)
                    <!-- STEP 1: POP-UP SESUAI MOCKUP -->

                    <!-- Header Card: [X] Yayasan Himatun -->
                    <div class="bg-card rounded-2xl px-4 sm:px-5 py-3.5 sm:py-4 flex items-center gap-4 shadow-2xs border border-border/50">
                        <button type="button" @click="closeModal()" class="text-danger hover:text-red-700 transition cursor-pointer p-0.5 flex items-center justify-center" aria-label="Tutup">
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

                        <!-- Countdown Timer Badges [00] : [05] : [00] -->
                        <div class="flex items-center gap-1 shrink-0 font-mono text-xs sm:text-sm font-bold text-foreground">
                            <span class="inline-flex items-center justify-center min-w-[28px] sm:min-w-[32px] h-7 sm:h-8 px-1.5 rounded-md bg-brand-700 text-white" x-text="hh">00</span>
                            <span class="text-foreground font-bold">:</span>
                            <span class="inline-flex items-center justify-center min-w-[28px] sm:min-w-[32px] h-7 sm:h-8 px-1.5 rounded-md bg-brand-700 text-white" x-text="mm">05</span>
                            <span class="text-foreground font-bold">:</span>
                            <span class="inline-flex items-center justify-center min-w-[28px] sm:min-w-[32px] h-7 sm:h-8 px-1.5 rounded-md bg-brand-700 text-white" x-text="ss">00</span>
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
                                class="w-full py-3 sm:py-3.5 px-4 rounded-xl text-center text-xs sm:text-sm font-bold transition border cursor-pointer {{ $metodePembayaran === 'QRIS' ? 'bg-brand-100 border-brand-700 text-brand-800 ring-2 ring-brand-700/25 shadow-xs' : 'bg-brand-50 hover:bg-brand-100 border-brand-200 text-brand-700' }}">
                                QRIS
                            </button>

                            <!-- Transfer Bank BRI -->
                            <button type="button" wire:click="selectMetodePembayaran('Transfer Bank BRI')"
                                class="w-full py-3 sm:py-3.5 px-4 rounded-xl text-center text-xs sm:text-sm font-bold transition border cursor-pointer {{ $metodePembayaran === 'Transfer Bank BRI' ? 'bg-brand-100 border-brand-700 text-brand-800 ring-2 ring-brand-700/25 shadow-xs' : 'bg-brand-50 hover:bg-brand-100 border-brand-200 text-brand-700' }}">
                                Transfer Bank BRI
                            </button>

                            <!-- Transfer Bank BCA -->
                            <button type="button" wire:click="selectMetodePembayaran('Transfer Bank BCA')"
                                class="w-full py-3 sm:py-3.5 px-4 rounded-xl text-center text-xs sm:text-sm font-bold transition border cursor-pointer {{ $metodePembayaran === 'Transfer Bank BCA' ? 'bg-brand-100 border-brand-700 text-brand-800 ring-2 ring-brand-700/25 shadow-xs' : 'bg-brand-50 hover:bg-brand-100 border-brand-200 text-brand-700' }}">
                                Transfer Bank BCA
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
                        <button type="button" @click="closeModal()" class="text-danger hover:text-red-700 transition cursor-pointer p-0.5" aria-label="Tutup">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Card ID & Timer Selesaikan Pembayaran -->
                    <div class="bg-card rounded-2xl p-4 sm:p-5 shadow-2xs border border-border/50 flex items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-muted-foreground-1 font-medium">ID Pembayaran:</span>
                                <span class="badge badge-success">{{ $metodePembayaran }}</span>
                            </div>
                            <div class="text-xs sm:text-sm font-bold text-foreground font-mono mt-1">{{ $paymentId }}</div>
                        </div>

                        <!-- Countdown Timer Badges [00] : [05] : [00] -->
                        <div class="flex items-center gap-1 shrink-0 font-mono text-xs sm:text-sm font-bold text-foreground">
                            <span class="inline-flex items-center justify-center min-w-[28px] sm:min-w-[32px] h-7 sm:h-8 px-1.5 rounded-md bg-brand-700 text-white" x-text="hh">00</span>
                            <span class="text-foreground font-bold">:</span>
                            <span class="inline-flex items-center justify-center min-w-[28px] sm:min-w-[32px] h-7 sm:h-8 px-1.5 rounded-md bg-brand-700 text-white" x-text="mm">05</span>
                            <span class="text-foreground font-bold">:</span>
                            <span class="inline-flex items-center justify-center min-w-[28px] sm:min-w-[32px] h-7 sm:h-8 px-1.5 rounded-md bg-brand-700 text-white" x-text="ss">00</span>
                        </div>
                    </div>

                    <!-- Card Detail Metode Pembayaran -->
                    <div class="bg-card rounded-2xl p-4 sm:p-5 shadow-2xs border border-border/50 space-y-4">
                        @if ($this->virtualAccountDetails['type'] === 'qris')
                            <!-- Tampilan QRIS -->
                            <div class="text-center space-y-2.5">
                                <div class="badge badge-success px-3 py-1">
                                    <span>QRIS Standar Nasional Bank Indonesia</span>
                                </div>

                                <div class="p-2.5 bg-white border border-border rounded-2xl inline-block shadow-xs">
                                    <img src="{{ asset('images/qris-code.svg') }}" alt="QRIS Yayasan Himatun Ayat" class="w-40 h-40 mx-auto" />
                                    <div class="text-2xs font-bold text-slate-700 mt-1.5">
                                        NMID: ID1024389201948 • Yayasan Himatun Ayat
                                    </div>
                                </div>

                                <div class="p-2.5 rounded-xl bg-brand-50 border border-brand-200">
                                    <div class="text-xs text-muted-foreground-1 font-medium">Total Nominal Donasi:</div>
                                    <div class="text-base sm:text-lg font-extrabold text-brand-900 tabular">
                                        Rp{{ number_format($currentDonasi?->nominal ?? $selectedNominal, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Tampilan Transfer Bank BRI / BCA -->
                            <div class="space-y-3" x-data="{ copiedRek: false }">
                                <div class="flex items-center justify-between border-b border-border pb-2">
                                    <span class="text-xs font-semibold text-muted-foreground-1">Bank Tujuan:</span>
                                    <span class="badge badge-success">
                                        {{ $this->virtualAccountDetails['bank'] }}
                                    </span>
                                </div>

                                <div class="p-4 rounded-xl bg-brand-50 border border-brand-200 space-y-1">
                                    <div class="text-xs text-muted-foreground-1 font-medium">Nomor Rekening Resmi:</div>
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="text-base sm:text-lg font-mono font-bold text-brand-900 tracking-wider">
                                            {{ $this->virtualAccountDetails['va_number'] }}
                                        </div>
                                        <button type="button"
                                            @click="navigator.clipboard.writeText('{{ $this->virtualAccountDetails['va_number'] }}'); copiedRek = true; setTimeout(() => copiedRek = false, 2000)"
                                            class="btn btn-secondary btn-sm bg-white">
                                            <span x-show="!copiedRek">Salin</span>
                                            <span x-show="copiedRek" class="text-brand-700 font-bold">Tersalin!</span>
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
                            Upload Bukti Transfer <span class="text-danger">*</span>
                        </label>

                        <div class="relative border-2 border-dashed {{ $errors->has('buktiTransfer') ? 'border-danger bg-red-50/40' : 'border-brand-300 hover:border-brand-500 bg-brand-50/50' }} rounded-xl p-3.5 text-center transition">
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
                        <div class="pt-1">
                            <button type="button" wire:click="uploadBuktiTransfer"
                                class="btn btn-primary rounded-xl w-full py-3 text-xs sm:text-sm font-bold shadow-sm transition cursor-pointer">
                                Saya Sudah Bayar / Selesai
                            </button>
                        </div>
                    </div>

                @endif

            </div>
        </div>
    @endif