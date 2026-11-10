@props([
    'icon' => null,
    'variant' => 'light', // 'light' | 'dark'
])

@php
    $baseClasses = "inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-2xs font-bold uppercase tracking-wider transition";
    $variantClasses = match($variant) {
        'dark' => "bg-brand-800/80 border border-brand-700/80 text-brand-200 shadow-2xs",
        default => "bg-brand-100/80 border border-brand-200/70 text-brand-800 shadow-2xs",
    };
@endphp

<div {{ $attributes->merge(['class' => "{$baseClasses} {$variantClasses}"]) }}>
    @if($icon === 'history')
        <!-- History / Compass Icon -->
        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
    @elseif($icon === 'compass' || $icon === 'direction')
        <!-- Compass / Direction Icon -->
        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m6.115 5.19 11.298 3.195a1.125 1.125 0 0 1 .802.802l3.195 11.298a.75.75 0 0 1-.95.95l-11.298-3.195a1.125 1.125 0 0 1-.802-.802L5.165 6.14a.75.75 0 0 1 .95-.95Z" />
        </svg>
    @elseif($icon === 'users' || $icon === 'organization')
        <!-- Organization / Users Icon -->
        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
        </svg>
    @elseif($icon === 'shield' || $icon === 'legal')
        <!-- Shield / Legal Icon -->
        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
        </svg>
    @else
        <!-- Generic Sparkle / Dot Icon -->
        <span class="w-1.5 h-1.5 rounded-full {{ $variant === 'dark' ? 'bg-brand-300' : 'bg-brand-600' }}"></span>
    @endif
    
    <span>{{ $slot }}</span>
</div>
