@props([
    'id' => null,
    'title' => null,
])

@php
    $uniqueId = $id ?? 'modal-' . uniqid();
@endphp

<div x-show="showModal" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" id="{{ $uniqueId }}-container"
    class="fixed inset-0 z-[100] flex items-center justify-center" role="dialog" aria-modal="true"
    aria-labelledby="{{ $uniqueId }}-title" {{ $attributes->except(['id', 'title']) }}>
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal = false"></div>

    <!-- Modal Content -->
    <div class="relative w-full max-w-md mx-4 bg-surface border border-border rounded-2xl shadow-2xl transform transition-all"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        <!-- Close Button -->
        <x-ui.icon-button @click="showModal = false"
            class="absolute top-4 right-4 text-muted hover:text-foreground transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/60 rounded-full p-1"
            aria-label="Close modal">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </x-ui.icon-button>

        <!-- Icon Slot -->
        @isset($icon)
            <div class="flex justify-center pt-6">
                {{ $icon }}
            </div>
        @endisset

        <!-- Title -->
        @if ($title)
            <h3 id="{{ $uniqueId }}-title" class="text-xl font-bold text-center text-foreground mt-4 px-6">
                {{ $title }}
            </h3>
        @endif

        <!-- Message Slot -->
        <div class="px-6 {{ $title ? 'mt-2' : 'pt-6' }}">
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
