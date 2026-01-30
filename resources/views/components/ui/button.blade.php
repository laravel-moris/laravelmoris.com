@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
])

@php
    $base = 'lm-btn';
    $sizes = [
        'sm' => 'lm-btn-sm',
        'md' => 'lm-btn-md',
    ];
    $variants = [
        'primary' => 'lm-btn-primary',
        'secondary' => 'lm-btn-secondary',
    ];

    $classes = implode(' ', array_filter([
        $base,
        $sizes[$size] ?? $sizes['md'],
        $variants[$variant] ?? $variants['primary'],
        $attributes->get('class'),
    ]));
@endphp

@if ($href)
    <a href="{{ $href }}" class="{{ $classes }}">
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" class="{{ $classes }}">
        {{ $slot }}
    </button>
@endif
