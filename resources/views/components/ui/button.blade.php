@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
])

@php
    $base =
        'inline-flex items-center justify-center gap-2 rounded-full font-bold uppercase tracking-wider transition-all duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/60 disabled:pointer-events-none disabled:opacity-50';

    $sizes = [
        'sm' => 'px-6 py-2.5 text-sm',
        'md' => 'px-10 py-4.5 text-sm',
        'lg' => 'px-12 py-5 text-base',
    ];

    $variants = [
        'primary' => 'bg-primary text-white hover:bg-primary-hover hover:-translate-y-0.5',
        'secondary' =>
            'bg-surface-2 text-foreground border-2 border-border/70 hover:border-teal hover:text-teal hover:-translate-y-0.5',
        'outline' =>
            'bg-transparent text-foreground border-2 border-border/70 hover:border-primary hover:text-primary hover:-translate-y-0.5',
        'ghost' => 'bg-transparent text-muted hover:text-foreground hover:bg-surface-2',
        'danger' => 'bg-coral text-white hover:bg-coral/90 hover:-translate-y-0.5',
    ];
@endphp

@if ($href)
    <a href="{{ $href }}"
        {{ $attributes->class([$base, $sizes[$size] ?? $sizes['md'], $variants[$variant] ?? $variants['primary']]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}"
        {{ $attributes->class([$base, $sizes[$size] ?? $sizes['md'], $variants[$variant] ?? $variants['primary']]) }}>
        {{ $slot }}
    </button>
@endif
