@props([
    'value' => '',
    'label' => '',
    'sublabel' => null,
])

<div {{ $attributes->merge(['class' => 'rounded-2xl bg-brand-50/70 border border-brand-100 p-4 sm:p-5 flex flex-col justify-center transition-all duration-200 hover:bg-brand-50 hover:border-brand-200 hover:shadow-xs']) }}>
    <p class="text-2xl sm:text-3xl font-extrabold text-brand-700 tracking-tight leading-none">
        {{ $value }}
    </p>
    <p class="text-xs sm:text-[13px] font-semibold text-slate-700 mt-1.5 leading-snug">
        {{ $label }}
    </p>
    @if($sublabel)
        <p class="text-2xs text-slate-500 mt-0.5">
            {{ $sublabel }}
        </p>
    @endif
</div>
