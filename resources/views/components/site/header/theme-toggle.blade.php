@props([
    'size' => 'md',
    'class' => '',
])

@php
    $sizeClasses = match ($size) {
        'sm' => 'size-5',
        'lg' => 'size-8',
        default => 'size-6',
    };
@endphp

<x-ui.icon-button data-theme-toggle aria-label="Toggle theme" aria-pressed="true" :class="$class">
    <svg data-theme-icon="moon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
        stroke-width="2" class="absolute {{ $sizeClasses }}">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
    <svg data-theme-icon="sun" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
        stroke="currentColor" stroke-width="2" class="absolute {{ $sizeClasses }} hidden">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
</x-ui.icon-button>
