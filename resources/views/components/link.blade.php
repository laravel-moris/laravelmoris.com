@props([
    'href' => '#',
    'variant' => 'default',
    'external' => false,
])

@php
    $base = 'transition-colors duration-200';

    $variants = [
        'default' => 'text-primary hover:text-primary-hover underline underline-offset-4',
        'muted' => 'text-muted hover:text-foreground',
        'subtle' => 'text-foreground hover:text-primary',
    ];

    $classes = [$base, $variants[$variant] ?? $variants['default']];
@endphp

<a href="{{ $href }}" @if ($external) target="_blank" rel="noopener noreferrer" @endif
    {{ $attributes->class($classes) }}>{{ $slot }}</a>
