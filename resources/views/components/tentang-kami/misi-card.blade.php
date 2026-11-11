@props([
    'number' => '01',
    'title' => '',
    'description' => '',
    'tag' => '',
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'group rounded-3xl bg-white border border-slate-200/80 shadow-2xs hover:shadow-md hover:border-emerald-300 transition-all duration-300 p-5 sm:p-6 flex flex-col justify-between']) }}>
    <div>
        <!-- Number Badge Sesuai UI Prototype -->
        <span class="inline-block px-3 py-1 rounded-lg bg-[#E2ECE6] text-[#1C4E3D] font-bold text-xs tracking-tight">
            {{ $number }}
        </span>

        <!-- Title -->
        <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-900 transition-colors duration-200 mt-3 leading-snug">
            {{ $title }}
        </h3>

        <!-- Description -->
        <p class="text-xs sm:text-[13px] text-slate-600 leading-relaxed mt-2 font-normal">
            {{ $description }}
        </p>
    </div>

    <!-- Bottom Tag Pill -->
    @if($tag)
        <div class="mt-4 pt-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-2xs font-semibold text-slate-700 bg-slate-50 border border-slate-200/80 group-hover:bg-[#E2ECE6]/70 group-hover:border-emerald-200 group-hover:text-[#1C4E3D] transition-colors duration-200 w-fit">
                @if($icon === 'education' || $number === '01')
                    <!-- Graduation Cap Icon -->
                    <svg class="w-3.5 h-3.5 text-[#1C4E3D] shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                    </svg>
                @elseif($icon === 'character' || $number === '02')
                    <!-- Heart / Counseling Icon -->
                    <svg class="w-3.5 h-3.5 text-[#1C4E3D] shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                    </svg>
                @elseif($icon === 'quran' || $number === '03')
                    <!-- Book / Quran Icon -->
                    <svg class="w-3.5 h-3.5 text-[#1C4E3D] shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                @elseif($icon === 'business' || $number === '04')
                    <!-- Briefcase / Career Icon -->
                    <svg class="w-3.5 h-3.5 text-[#1C4E3D] shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                @endif
                <span>{{ $tag }}</span>
            </span>
        </div>
    @endif
</div>
