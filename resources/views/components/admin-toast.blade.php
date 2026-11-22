@php
    $toastTypes = ['success', 'error', 'warning', 'info'];
    $flashes = [];

    foreach ($toastTypes as $type) {
        $message = session()->get($type);

        if (is_string($message) && $message !== '') {
            $flashes[] = [
                'id' => 'hs-toast-session-' . $type,
                'type' => $type,
                'message' => $message,
            ];
        }
    }

    $iconConfigs = [
        'success' => [
            'color' => 'text-teal-500',
            'path' => 'M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z',
        ],
        'error' => [
            'color' => 'text-red-500',
            'path' => 'M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z',
        ],
        'warning' => [
            'color' => 'text-amber-500',
            'path' => 'M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z',
        ],
        'info' => [
            'color' => 'text-blue-500',
            'path' => 'M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z',
        ],
    ];
@endphp

<!-- Global Preline Toast Container -->
<div id="admin-toast-container"
     class="fixed bottom-5 end-5 z-90 flex flex-col gap-2.5 max-w-xs w-full pointer-events-none"
     aria-live="polite"
     aria-atomic="false">
    @foreach($flashes as $flash)
        @php
            $toastId = $flash['id'];
            $icon = $iconConfigs[$flash['type']] ?? $iconConfigs['success'];
        @endphp
        <div id="{{ $toastId }}"
             data-admin-toast
             data-auto-dismiss="4500"
             class="hs-removing:translate-x-5 hs-removing:opacity-0 transition duration-300 max-w-xs w-full p-4 bg-layer border border-layer-line rounded-xl shadow-lg pointer-events-auto"
             role="alert"
             tabindex="-1"
             aria-labelledby="{{ $toastId }}-label">
            <div class="flex">
                <div class="shrink-0">
                    <svg class="shrink-0 size-4 {{ $icon['color'] }} mt-0.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="{{ $icon['path'] }}"/>
                    </svg>
                </div>
                <div class="ms-3 grow">
                    <p id="{{ $toastId }}-label" class="text-sm text-layer-foreground">{{ $flash['message'] }}</p>
                </div>
                <div class="ms-3">
                    <button type="button"
                            class="inline-flex shrink-0 justify-center items-center size-5 rounded-lg text-layer-foreground hover:bg-layer-hover opacity-50 hover:opacity-100 focus:outline-hidden focus:opacity-100 cursor-pointer"
                            data-hs-remove-element="#{{ $toastId }}"
                            aria-label="Tutup notifikasi">
                        <span class="sr-only">Close</span>
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endforeach
</div>
