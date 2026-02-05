@props([
    'level' => 2,
    'size' => null,
])

@php
    $level = max(1, min(6, (int) $level));

    $styles = [
        1 => 'text-display-lg font-bold tracking-tighter text-foreground',
        2 => 'text-display font-bold tracking-tight text-foreground',
        3 => 'text-3xl font-bold tracking-tight text-foreground',
        4 => 'text-2xl font-bold tracking-snug text-foreground',
        5 => 'text-lg font-bold text-foreground',
        6 => 'text-base font-bold uppercase tracking-wider text-muted',
    ];

    $sizeStyles = [
        'display-lg' => $styles[1],
        'display' => $styles[2],
        '3xl' => $styles[3],
        '2xl' => $styles[4],
        'lg' => $styles[5],
        'base' => $styles[6],
    ];

    $classes = $size !== null ? $sizeStyles[$size] ?? $styles[$level] : $styles[$level];
@endphp

<h{{ $level }} {{ $attributes->class($classes) }}>
    {{ $slot }}
    </h{{ $level }}>
