@props([
    'id' => null,
    'label' => 'Dialog',
])

@php
    $uniqueId = $id ?? 'modal-' . uniqid();
@endphp

<div x-data="accessibleDialog" x-show="showModal" x-cloak x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" id="{{ $uniqueId }}-container"
    class="fixed inset-0 z-[100] flex items-center justify-center" role="dialog" aria-modal="true"
    @if (isset($header)) aria-labelledby="{{ $uniqueId }}-title" @else aria-label="{{ $label }}" @endif
    @keydown.escape.stop.prevent="closeDialog" @keydown.tab="trapFocus" {{ $attributes->except(['id', 'label']) }}>
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeDialog"></div>

    <!-- Modal Content -->
    <div class="relative w-full max-w-md mx-4 bg-surface border border-border rounded-2xl shadow-2xl transform transition-all"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100">
        <!-- Close Button -->
        <button type="button" @click="closeDialog"
            class="absolute top-4 right-4 z-10 grid size-11 place-items-center rounded-full bg-surface-2 border border-border/70 text-muted transition-colors hover:text-foreground hover:border-border focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/60"
            aria-label="Close modal">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Icon Slot -->
        @isset($icon)
            <div class="flex justify-center pt-6">
                {{ $icon }}
            </div>
        @endisset

        <!-- Header Slot -->
        @isset($header)
            <div id="{{ $uniqueId }}-title" class="text-center mt-4 px-6">
                {{ $header }}
            </div>
        @endisset

        <!-- Message Slot (default slot) -->
        <div class="px-6 {{ isset($header) || isset($icon) ? 'mt-2' : 'pt-6' }}">
            {{ $message ?? $slot }}
        </div>

        <!-- Footer Slot -->
        @isset($footer)
            <div class="px-6 pb-6 mt-6">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
