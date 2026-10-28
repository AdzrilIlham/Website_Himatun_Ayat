@props([
    'placeholder' => 'Pilih opsi...',
    'wrapperClasses' => 'relative w-full',
    'toggleClasses' => 'hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2.5 ps-3.5 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-layer border border-border rounded-xl text-start text-sm text-foreground shadow-2xs focus:outline-hidden focus:border-primary focus:ring-2 focus:ring-primary/20',
    'dropdownClasses' => 'mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-dropdown border border-dropdown-line rounded-xl shadow-lg overflow-hidden overflow-y-auto',
    'optionClasses' => 'py-2 px-3 w-full text-sm text-dropdown-item-foreground cursor-pointer hover:bg-dropdown-item-hover rounded-lg focus:outline-hidden focus:bg-dropdown-item-focus hs-selected:bg-primary/10 hs-selected:text-primary',
    'mode' => null,
])

@php
    $selectConfig = [
        'placeholder' => $placeholder,
        'wrapperClasses' => $wrapperClasses,
        'toggleTag' => '<button type="button" aria-expanded="false"></button>',
        'toggleClasses' => $toggleClasses,
        'dropdownClasses' => $dropdownClasses,
        'optionClasses' => $optionClasses,
        // Agar opsi placeholder (value="") tetap dikenali Preline, sehingga toggle
        // menampilkan placeholder dan nilai native tetap kosong sampai dipilih.
        'optionAllowEmptyOption' => true,
        'optionTemplate' => '<div class="flex justify-between items-center w-full"><span data-title></span><span class="hidden hs-selected:block"><svg class="shrink-0 size-3.5 text-primary" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span></div>',
        'extraMarkup' => '<div class="absolute top-1/2 inset-e-3 -translate-y-1/2 pointer-events-none"><svg class="shrink-0 size-3.5 text-muted-foreground" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7 15 5 5 5-5"/><path d="m7 9 5-5 5 5"/></svg></div>',
    ];

    if ($mode) {
        $selectConfig['mode'] = $mode;
    }
@endphp

<select {{ $attributes->merge(['class' => 'hidden']) }} data-hs-select="{{ json_encode($selectConfig) }}">
    {{ $slot }}
</select>
