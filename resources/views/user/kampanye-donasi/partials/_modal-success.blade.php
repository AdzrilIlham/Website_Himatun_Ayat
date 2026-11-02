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