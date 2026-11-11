@props([
    'title' => '',
    'nomor' => '',
    'deskripsi' => '',
    'badgeText' => 'Resmi Terverifikasi',
    'badgeVariant' => 'sage', // 'success' (brand-700) | 'sage' (brand-200) | 'warning'
    'image' => null,
    'imageAlt' => 'Pratinjau Dokumen Legalitas',
    'footerText' => '',
    'icon' => 'shield',
    'modalId' => null,
])

@php
    $uniqueId = $modalId ?: 'modal-dokumen-' . md5($title . $nomor);
@endphp

<div {{ $attributes->merge(['class' => 'group rounded-3xl bg-white border border-slate-200/90 shadow-2xs hover:shadow-lg hover:border-brand-300 transition-all duration-300 p-5 sm:p-6 flex flex-col justify-between']) }}>
    
    <div>
        <!-- Card Header: Icon & Verification Badge -->
        <div class="flex items-start justify-between gap-3">
            <!-- Icon Box -->
            <div class="w-10 h-10 rounded-xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-700 shrink-0 group-hover:bg-brand-100 transition-colors duration-200">
                @if($icon === 'shield')
                    <!-- Shield Icon -->
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                @elseif($icon === 'notary')
                    <!-- Feather / Notary Document Icon -->
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                @elseif($icon === 'tax')
                    <!-- Tax / Identification Card Icon -->
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                    </svg>
                @else
                    <!-- Generic Document Icon -->
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                @endif
            </div>

            <!-- Status Badge -->
            @if($badgeVariant === 'success')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-2xs font-semibold bg-brand-700 text-white shadow-2xs">
                    {{ $badgeText }}
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-2xs font-semibold bg-brand-100/90 border border-brand-200 text-brand-900 shadow-2xs">
                    {{ $badgeText }}
                </span>
            @endif
        </div>

        <!-- Title & Registration Number -->
        <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-brand-900 transition-colors duration-200 mt-4 leading-snug">
            {{ $title }}
        </h3>

        @if($nomor)
            <p class="text-xs font-mono font-medium text-slate-500 mt-1">
                {{ $nomor }}
            </p>
        @endif

        <!-- Description -->
        <p class="text-xs sm:text-[13px] text-slate-600 leading-relaxed mt-2.5 font-normal">
            {{ $deskripsi }}
        </p>

        <!-- Document Image / Thumbnail Preview -->
        @if($image)
            <div class="mt-4 relative rounded-2xl overflow-hidden border border-slate-200/90 bg-slate-50 aspect-[16/7] group/thumb">
                <img src="{{ $image }}" 
                     alt="{{ $imageAlt }}" 
                     class="w-full h-full object-cover group-hover/thumb:scale-105 transition-transform duration-300">
                
                <!-- Zoom Overlay Button -->
                <button type="button" 
                        data-hs-overlay="#{{ $uniqueId }}"
                        aria-haspopup="dialog"
                        aria-expanded="false"
                        aria-controls="{{ $uniqueId }}"
                        class="absolute inset-0 bg-slate-950/20 backdrop-blur-2xs opacity-0 group-hover/thumb:opacity-100 transition-opacity duration-200 flex items-center justify-center text-white gap-1.5 text-xs font-semibold">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607ZM10.5 7.5v6m3-3h-6" />
                    </svg>
                    <span>Lihat Dokumen</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Card Footer -->
    @if($footerText)
        <div class="border-t border-slate-100 mt-5 pt-3.5 text-center">
            <p class="text-xs font-medium text-slate-500 tracking-wide">
                {{ $footerText }}
            </p>
        </div>
    @endif

</div>

<!-- Modal Lightbox Preview (Preline UI HSOverlay) -->
@if($image)
    <div id="{{ $uniqueId }}" 
         class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none" 
         role="dialog" 
         tabindex="-1" 
         aria-labelledby="{{ $uniqueId }}-label">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-300 mt-0 opacity-0 ease-out transition-all sm:max-w-3xl sm:w-full m-3 sm:mx-auto">
            <div class="relative flex flex-col bg-white border border-slate-200 shadow-2xl rounded-3xl pointer-events-auto overflow-hidden">
                <!-- Modal Header -->
                <div class="flex justify-between items-center py-3.5 px-5 border-b border-slate-100 bg-slate-50">
                    <div>
                        <h4 id="{{ $uniqueId }}-label" class="font-bold text-sm sm:text-base text-slate-900">
                            {{ $title }}
                        </h4>
                        @if($nomor)
                            <p class="text-xs text-slate-500 font-mono mt-0.5">{{ $nomor }}</p>
                        @endif
                    </div>
                    <button type="button" 
                            class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-slate-200 text-slate-700 hover:bg-slate-300 focus:outline-hidden transition text-sm" 
                            data-hs-overlay="#{{ $uniqueId }}">
                        <span class="sr-only">Tutup</span>
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <!-- Modal Body -->
                <div class="p-4 sm:p-6 bg-slate-900/5 flex items-center justify-center">
                    <img src="{{ $image }}" alt="{{ $imageAlt }}" class="max-h-[70vh] w-auto max-w-full rounded-xl shadow-md object-contain">
                </div>
                <!-- Modal Footer -->
                <div class="py-3 px-5 border-t border-slate-100 bg-white flex justify-between items-center text-xs text-slate-500">
                    <span>{{ $footerText }}</span>
                    <button type="button" 
                            class="py-1.5 px-3.5 inline-flex items-center gap-x-2 text-xs font-semibold rounded-lg border border-slate-200 bg-white text-slate-700 shadow-2xs hover:bg-slate-50 transition" 
                            data-hs-overlay="#{{ $uniqueId }}">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
