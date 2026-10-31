@props([
    'number' => '01',
    'name' => '',
    'role' => 'Pengurus',
    'image' => '',
    'imageAlt' => '',
    'icon' => null,
    'featured' => false,
])

<div {{ $attributes->merge(['class' => 'group relative rounded-3xl bg-[#E8F3EE] border border-white/40 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between overflow-hidden hover:-translate-y-1']) }}>
    
    <!-- Subtle top aura / radial shine -->
    <div class="absolute -top-12 -right-12 w-32 h-32 bg-brand-200/40 rounded-full blur-2xl pointer-events-none group-hover:bg-brand-300/50 transition-all duration-300"></div>

    <!-- Illustration Image Container -->
    <div class="relative w-full {{ $featured ? 'h-52 sm:h-60' : 'h-48 sm:h-52' }} flex items-end justify-center pt-4 px-4 overflow-hidden">
        @if($image)
            <img src="{{ $image }}" 
                 alt="{{ $imageAlt ?: $name }}" 
                 class="h-full w-auto max-w-[85%] object-contain object-bottom drop-shadow-md group-hover:scale-105 transition-transform duration-300">
        @else
            <!-- Placeholder Silhouette -->
            <div class="w-24 h-24 rounded-full bg-brand-200 flex items-center justify-center text-brand-700">
                <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
        @endif
    </div>

    <!-- Info Box Bottom -->
    <div class="bg-white/95 backdrop-blur-sm p-4 sm:p-5 rounded-2xl m-2 sm:m-2.5 border border-slate-100 shadow-2xs">
        <!-- Top Row: Number & Icon Badge -->
        <div class="flex items-center justify-between gap-2">
            <span class="text-xs sm:text-sm font-extrabold text-brand-900 tracking-tight">
                {{ $number }}
            </span>

            <!-- Category / Role Icon Pill -->
            <div class="w-7 h-7 rounded-lg bg-brand-50 border border-brand-100/80 flex items-center justify-center text-brand-700 shrink-0">
                @if($icon === 'leader' || $number === '01')
                    <!-- Handshake / Leader Icon -->
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.05 4.575a1.575 1.575 0 1 0-3.15 0v3m3.15-3v-1.5a1.575 1.575 0 0 1 3.15 0v1.5m-3.15 0h3.15m-3.15 3a1.575 1.575 0 0 1-3.15 0m3.15 0h3.15m0 0v-3m0 3v3m0-3h3.15a1.575 1.575 0 0 1 3.15 0v3a1.575 1.575 0 0 1-3.15 0h-3.15" />
                    </svg>
                @elseif($number === '04' || $icon === 'book')
                    <!-- Book / Al-Quran Icon -->
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                @else
                    <!-- Mosque / Institution Icon -->
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12 3v1m0 17.5v-1.5m-7.5 1.5h4.5V3.545M12 3c-1.657 0-3 1.343-3 3v.545m6-3.545c1.657 0 3 1.343 3 3v.545" />
                    </svg>
                @endif
            </div>
        </div>

        <!-- Name -->
        <h4 class="text-sm sm:text-base font-bold text-slate-900 tracking-tight mt-1.5 line-clamp-1">
            {{ $name }}
        </h4>

        <!-- Role -->
        <p class="text-xs font-medium text-slate-500 mt-0.5">
            {{ $role }}
        </p>
    </div>
</div>
