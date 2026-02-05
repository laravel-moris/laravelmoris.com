@props([
    'variant' => null,
    'size' => 'base',
])

@php
    $variants = [
        'muted' => 'text-sm text-muted',
        'strong' => 'text-base text-foreground font-semibold',
        'subtitle' => 'text-md font-medium uppercase tracking-caps text-muted',
    ];

    $sizes = [
        'sm' => 'text-sm text-foreground',
        'base' => 'text-base text-foreground',
        'md' => 'text-md leading-relaxed text-foreground',
        'lg' => 'text-lg text-foreground',
    ];

    $classes = $variant !== null ? $variants[$variant] ?? $sizes['base'] : $sizes[$size] ?? $sizes['base'];
@endphp

<p {{ $attributes->class($classes) }}>
    {{ $slot }}
</p>
