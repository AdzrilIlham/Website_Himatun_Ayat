    <!-- Modal Sukses Donasi -->
    @if ($showSuccessModal)
        <div x-data="{ show: false }"
            x-init="requestAnimationFrame(() => show = true)"
            :class="show ? 'opacity-100' : 'opacity-0'"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-300 ease-out">
            <div :class="show ? 'opacity-100 scale-100 translate-y-0' : 'opacity-0 scale-90 translate-y-4'"
                class="bg-card rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-border text-center space-y-4 transition-all duration-300 ease-out transform">
                <div :class="show ? 'scale-100' : 'scale-50'"
                    class="w-16 h-16 rounded-full bg-brand-100 text-brand-700 mx-auto flex items-center justify-center transition-transform duration-500 delay-75 ease-out">
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

                <div class="pt-2">
                    <button type="button"
                        @click="show = false; setTimeout(() => $wire.closeSuccessModal(), 250)"
                        class="btn btn-primary rounded-full w-full py-3 text-xs sm:text-sm font-bold shadow-md transition cursor-pointer">
                        Kembali ke Halaman Program
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Gagal Donasi (Waktu Pembayaran Habis) -->
    @if ($showFailedModal)
        <div x-data="{ show: false }"
            x-init="requestAnimationFrame(() => show = true)"
            :class="show ? 'opacity-100' : 'opacity-0'"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-300 ease-out">
            <div :class="show ? 'opacity-100 scale-100 translate-y-0' : 'opacity-0 scale-90 translate-y-4'"
                class="bg-card rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-border text-center space-y-4 transition-all duration-300 ease-out transform">
                <div :class="show ? 'scale-100' : 'scale-50'"
                    class="w-16 h-16 rounded-full bg-danger-soft text-danger mx-auto flex items-center justify-center transition-transform duration-500 delay-75 ease-out">
                    <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>

                <h3 class="text-xl font-extrabold text-foreground">
                    Transaksi Donasi Gagal
                </h3>

                <p class="text-xs sm:text-sm text-muted-foreground-1 leading-relaxed">
                    Maaf, batas waktu pembayaran Anda telah habis. Silakan ulangi proses konfirmasi donasi untuk melanjutkan kebaikan Anda.
                </p>

                <div class="pt-2">
                    <button type="button"
                        @click="show = false; setTimeout(() => $wire.closeFailedModal(), 250)"
                        class="btn btn-primary rounded-full w-full py-3 text-xs sm:text-sm font-bold shadow-md transition cursor-pointer">
                        Kembali ke Halaman Program
                    </button>
                </div>
            </div>
        </div>
    @endif