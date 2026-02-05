@props([
    'color' => 'muted',
    'variant' => 'soft',
    'size' => 'sm',
    'caps' => false,
])

@php
    $sizes = [
        'xs' => 'px-4 py-2 text-2xs',
        'sm' => 'px-3 py-1 text-xs',
        'md' => 'px-4 py-1.5 text-xs',
    ];

    $colors = [
        'soft' => [
            'green' => 'bg-green/10 border-green/20 text-foreground',
            'gold' => 'bg-gold/10 border-gold/20 text-foreground',
            'coral' => 'bg-coral/10 border-coral/20 text-foreground',
            'muted' => 'bg-muted/10 border-muted/20 text-foreground',
            'teal' => 'bg-teal/10 border-teal/20 text-foreground',
            'cyan' => 'bg-cyan/10 border-cyan/20 text-foreground',
            'primary' => 'bg-primary/10 border-primary/20 text-foreground',
        ],
        'solid' => [
            'green' => 'bg-green border-transparent text-white',
            'gold' => 'bg-gold border-transparent text-white',
            'coral' => 'bg-coral border-transparent text-white',
            'muted' => 'bg-muted border-transparent text-white',
            'teal' => 'bg-teal border-transparent text-white',
            'cyan' => 'bg-cyan border-transparent text-white',
            'primary' => 'bg-primary border-transparent text-white',
        ],
    ];

    $base = 'inline-flex items-center gap-1.5 rounded-full border font-semibold';
    $capsClass = $caps ? 'font-bold uppercase tracking-wider' : '';
@endphp

<span
    {{ $attributes->class([
        $base,
        $sizes[$size] ?? $sizes['sm'],
        $colors[$variant][$color] ?? $colors['soft']['muted'],
        $capsClass,
    ]) }}>
    {{ $slot }}
</span>
