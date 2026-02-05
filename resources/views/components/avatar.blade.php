@props(['src', 'alt' => '', 'size' => 'md', 'border' => 'none', 'loading' => 'lazy'])

@php
    $base = 'relative inline-flex shrink-0 items-center justify-center overflow-hidden rounded-full bg-surface-2';

    $sizes = [
        'xs' => 'size-8',
        'sm' => 'size-10',
        'md' => 'size-12',
        'lg' => 'size-14',
        'xl' => 'size-avatar-lg',
        '2xl' => 'size-32',
    ];

    $borders = [
        'none' => '',
        'subtle' => 'border border-border/70',
        'stack' => 'border-[3px] border-surface',
        'speaker' => 'border-4 border-surface',
    ];
@endphp

<div {{ $attributes->class([$base, $sizes[$size] ?? $sizes['md'], $borders[$border] ?? '']) }}>
    <img src="{{ $src }}" alt="{{ $alt }}" class="h-full w-full object-cover"
        loading="{{ $loading }}">
</div>
