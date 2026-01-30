@props([
    'src',
    'alt' => '',
    'size' => 'md',
    'border' => 'none',
    'loading' => 'lazy',
])

@php
    $sizes = [
        'xs' => 'size-8',
        'sm' => 'size-10',
        'md' => 'size-12',
        'lg' => 'size-14',
        'xl' => 'size-[120px]',
    ];

    $borders = [
        'none' => null,
        'subtle' => 'border border-border/70',
        'stack' => 'border-[3px] border-surface',
        'speaker' => 'border-4 border-surface',
    ];

    $classes = array_filter([
        'lm-avatar',
        'shrink-0',
        $sizes[$size] ?? $sizes['md'],
        $borders[$border] ?? $borders['none'],
    ]);
@endphp

<div {{ $attributes->class($classes) }}>
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        class="h-full w-full object-cover"
        loading="{{ $loading }}"
    >
</div>
