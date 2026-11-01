@props([
    'value' => '',
    'label' => '',
    'sublabel' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-2xl bg-[#E2ECE6]/80 border border-[#CEE0D7] p-4 sm:p-5 flex flex-col justify-center transition-all duration-200 hover:bg-[#E2ECE6] hover:shadow-xs']) }}>
    <p class="text-2xl sm:text-3xl font-extrabold text-[#1C4E3D] tracking-tight leading-none">
        {{ $value }}
    </p>
    <p class="text-xs sm:text-[13px] font-semibold text-slate-700 mt-2 leading-snug">
        {{ $label }}
    </p>
    @if($sublabel)
        <p class="text-2xs text-slate-500 mt-0.5">
            {{ $sublabel }}
        </p>
    @endif
</div>
