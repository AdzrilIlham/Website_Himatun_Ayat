@props([
    'id' => 'global-confirm-modal',
    'type' => 'danger',
    'title' => 'Konfirmasi Tindakan',
    'message' => 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
    'confirmText' => null,
    'cancelText' => 'Batal',
    'subtext' => null,
])

@php
    $isGlobal = ($id === 'global-confirm-modal');

    // Default teks konfirmasi berdasarkan tipe jika tidak ditentukan
    if (! $confirmText) {
        $confirmText = match ($type) {
            'danger' => 'Ya, Hapus',
            'success' => 'Ya, Lanjutkan',
            'warning' => 'Ya, Lanjutkan',
            'info' => 'Ya, Simpan',
            default => 'Ya, Lanjutkan',
        };
    }

    // Pemetaan tema warna badge ikon & tombol konfirmasi
    $badgeStyles = [
        'danger' => 'bg-red-50 text-red-600 border-red-200/80 ring-red-500/10 dark:bg-red-950/40 dark:text-red-400 dark:border-red-900/40',
        'success' => 'bg-brand-50 text-brand-700 border-brand-200/80 ring-brand-500/10 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/40',
        'warning' => 'bg-amber-50 text-amber-600 border-amber-200/80 ring-amber-500/10 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-900/40',
        'info' => 'bg-sky-50 text-sky-600 border-sky-200/80 ring-sky-500/10 dark:bg-sky-950/40 dark:text-sky-400 dark:border-sky-900/40',
    ];

    $buttonStyles = [
        'danger' => 'bg-danger hover:bg-red-700 active:bg-red-800 text-white focus:ring-danger/30 shadow-xs',
        'success' => 'bg-brand-700 hover:bg-brand-800 active:bg-brand-900 text-white focus:ring-brand-700/30 shadow-xs',
        'warning' => 'bg-amber-600 hover:bg-amber-700 active:bg-amber-800 text-white focus:ring-amber-600/30 shadow-xs',
        'info' => 'bg-brand-700 hover:bg-brand-800 active:bg-brand-900 text-white focus:ring-brand-700/30 shadow-xs',
    ];

    $cancelStyles = [
        'danger' => 'border border-border bg-layer hover:bg-muted text-foreground',
        'success' => 'border border-red-200 dark:border-red-900/60 bg-red-50/70 hover:bg-red-100 dark:bg-red-950/30 text-red-600 dark:text-red-400 hover:border-red-300',
        'warning' => 'border border-red-200 dark:border-red-900/60 bg-red-50/70 hover:bg-red-100 dark:bg-red-950/30 text-red-600 dark:text-red-400 hover:border-red-300',
        'info' => 'border border-red-200 dark:border-red-900/60 bg-red-50/70 hover:bg-red-100 dark:bg-red-950/30 text-red-600 dark:text-red-400 hover:border-red-300',
    ];

    $activeBadgeClass = $badgeStyles[$type] ?? $badgeStyles['danger'];
    $activeBtnClass = $buttonStyles[$type] ?? $buttonStyles['danger'];
    $activeCancelClass = $cancelStyles[$type] ?? $cancelStyles['danger'];
@endphp

<!-- Preline Confirmation Modal -->
<div id="{{ $id }}"
     class="hs-overlay hidden size-full fixed top-0 start-0 z-[100] overflow-x-hidden overflow-y-auto pointer-events-none"
     data-hs-overlay-options='{"isClosePrev": false}'
     role="dialog"
     tabindex="-1"
     aria-labelledby="{{ $id }}-title"
     aria-describedby="{{ $id }}-message">

    <div class="hs-overlay-open:opacity-100 hs-overlay-open:duration-300 opacity-0 ease-out transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center justify-center">
        <div class="relative w-full flex flex-col bg-card border border-border shadow-2xl rounded-2xl sm:rounded-3xl pointer-events-auto p-6 sm:p-7 text-center overflow-hidden">
            
            <!-- Tombol Tutup (X) di Pojok Kanan Atas -->
            <button type="button"
                    data-hs-overlay="#{{ $id }}"
                    class="absolute top-3.5 right-3.5 size-8 inline-flex justify-center items-center rounded-full text-muted-foreground hover:text-foreground hover:bg-muted transition cursor-pointer"
                    aria-label="Tutup modal">
                <span class="sr-only">Tutup</span>
                <svg class="shrink-0 size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Badge Ikon Status Dinamis -->
            <div id="{{ $id }}-badge"
                 data-confirm-badge
                 class="size-14 sm:size-16 rounded-2xl mx-auto mb-4.5 flex items-center justify-center border ring-8 transition-colors duration-200 {{ $activeBadgeClass }}">
                
                <!-- Ikon Danger / Hapus -->
                <svg id="{{ $id }}-icon-danger"
                     class="size-7 sm:size-8 shrink-0 {{ $type === 'danger' ? '' : 'hidden' }}"
                     fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>

                <!-- Ikon Success / Tambah / Verifikasi -->
                <svg id="{{ $id }}-icon-success"
                     class="size-7 sm:size-8 shrink-0 {{ $type === 'success' ? '' : 'hidden' }}"
                     fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>

                <!-- Ikon Warning / Peringatan / Tolak / Tinggalkan -->
                <svg id="{{ $id }}-icon-warning"
                     class="size-7 sm:size-8 shrink-0 {{ $type === 'warning' ? '' : 'hidden' }}"
                     fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>

                <!-- Ikon Info / Konfirmasi Umum -->
                <svg id="{{ $id }}-icon-info"
                     class="size-7 sm:size-8 shrink-0 {{ $type === 'info' ? '' : 'hidden' }}"
                     fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                </svg>
            </div>

            <!-- Judul Konfirmasi -->
            <h3 id="{{ $id }}-title"
                data-confirm-title
                class="text-lg sm:text-xl font-bold text-foreground leading-snug tracking-tight px-2">
                {{ $title }}
            </h3>

            <!-- Pesan / Deskripsi Konfirmasi -->
            <p id="{{ $id }}-message"
               data-confirm-message
               class="text-xs sm:text-sm text-muted-foreground-1 mt-2 leading-relaxed px-2">
                {{ $message }}
            </p>

            <!-- Catatan Tambahan (Subtext Opsional) -->
            <p id="{{ $id }}-subtext"
               data-confirm-subtext
               class="text-2xs font-medium text-muted-foreground mt-2 {{ $subtext ? '' : 'hidden' }}">
                {{ $subtext ?? '' }}
            </p>

            <!-- Grup Tombol Aksi (Responsive: Stack di mobile kecil, Row di tablet/desktop) -->
            <div class="mt-6 sm:mt-7 flex flex-col-reverse sm:flex-row items-center justify-center gap-2.5 sm:gap-3 w-full">
                <!-- Tombol Batal -->
                <button type="button"
                        id="{{ $id }}-cancel-btn"
                        data-hs-overlay="#{{ $id }}"
                        data-confirm-cancel
                        class="w-full sm:w-1/2 py-2.5 px-4 rounded-xl text-xs sm:text-sm font-semibold transition cursor-pointer flex items-center justify-center focus:outline-hidden focus:ring-2 focus:ring-primary/20 {{ $activeCancelClass }}">
                    <span id="{{ $id }}-cancel-text">{{ $cancelText }}</span>
                </button>

                <!-- Tombol Konfirmasi Aksi -->
                <button type="button"
                        id="{{ $id }}-confirm-btn"
                        data-confirm-submit
                        class="w-full sm:w-1/2 py-2.5 px-4 rounded-xl text-xs sm:text-sm font-bold shadow-xs transition cursor-pointer flex items-center justify-center gap-1.5 focus:outline-hidden focus:ring-2 {{ $activeBtnClass }}">
                    <span id="{{ $id }}-confirm-text">{{ $confirmText }}</span>
                </button>
            </div>
        </div>
    </div>
</div>

@if ($isGlobal)
    <!-- Trigger cadangan tak terlihat untuk memastikan pembukaan modal selalu berhasil via event klik Preline -->
    <button type="button"
            id="{{ $id }}-trigger"
            data-hs-overlay="#{{ $id }}"
            class="hidden"
            aria-hidden="true"
            tabindex="-1"></button>
@endif
