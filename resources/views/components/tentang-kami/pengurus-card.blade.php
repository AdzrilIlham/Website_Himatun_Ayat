@props([
    'number' => '01',
    'name' => '',
    'role' => 'Pengurus',
    'image' => '',
    'imageAlt' => '',
    'icon' => null,
    'featured' => false,
])

@php
    // Sesuai UI Prototype:
    // Nomor 01 (Ketua Yayasan) menggunakan Icon-ketua.png
    // Pengurus lainnya (02, 03, 04) menggunakan Icon-pengurus.png
    $isKetua = ($number === '01' || $icon === 'leader' || str_contains(strtolower($role), 'ketua'));
    $iconSrc = $isKetua ? asset('images/Asset/Icon-ketua.png') : asset('images/Asset/Icon-pengurus.png');
@endphp

<div {{ $attributes->merge(['class' => 'group relative w-full max-w-[280px] sm:max-w-[310px] mx-auto flex flex-col items-center select-none']) }}>
    
    <!-- Avatar Image Container (Diletakkan di belakang bahu Shape-pengurus, masuk ke notch) -->
    <div class="relative w-full flex justify-center -mb-[26%] sm:-mb-[27%] z-0 pointer-events-none">
        @if($image)
            <img src="{{ $image }}" 
                 alt="{{ $imageAlt ?: $name }}" 
                 class="h-44 sm:h-52 md:h-56 w-auto object-contain drop-shadow-md group-hover:scale-105 transition-transform duration-300">
        @else
            <!-- Placeholder Silhouette -->
            <div class="w-24 h-24 rounded-full bg-emerald-200 flex items-center justify-center text-emerald-800">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
        @endif
    </div>

    <!-- Shape Card Container: z-10 agar bahu shape menutupi sisi avatar -->
    <div class="relative w-full aspect-[310/176] z-10 drop-shadow-lg group-hover:-translate-y-1 transition-transform duration-300">
        <!-- PNG Shape Elemen Resmi Sesuai Desain UI -->
        <img src="{{ asset('images/Asset/Shape-pengurus.png') }}" 
             alt="" 
             class="absolute inset-0 w-full h-full object-fill select-none pointer-events-none">

        <!-- Nomor Pengurus di Bahu Kiri -->
        <div class="absolute left-[7%] top-[12%] flex items-center justify-center">
            <span class="text-xl sm:text-2xl font-extrabold text-[#1C4E3D] leading-none tracking-tight">
                {{ $number }}
            </span>
        </div>

        <!-- Ikon Jabatan di Bahu Kanan -->
        <div class="absolute right-[7%] top-[12%] flex items-center justify-center">
            <img src="{{ $iconSrc }}" 
                 alt="Icon {{ $role }}" 
                 class="w-6 h-6 sm:w-7 sm:h-7 object-contain">
        </div>

        <!-- Nama & Jabatan Pengurus di Area Bawah Notch -->
        <div class="absolute left-[7%] bottom-[14%] right-[7%] flex flex-col justify-end text-left">
            <h4 class="text-base sm:text-lg lg:text-xl font-bold text-slate-900 tracking-tight leading-snug line-clamp-1">
                {{ $name }}
            </h4>
            <p class="text-xs sm:text-sm font-medium text-slate-600 mt-0.5 leading-none">
                {{ $role }}
            </p>
        </div>
    </div>

</div>
