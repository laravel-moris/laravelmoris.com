@props([
    'color' => 'muted',
    'variant' => 'soft',
    'size' => 'sm',
    'caps' => false,
    'text' => 'auto',
])

@php
    $sizes = [
        'xs' => 'px-4 py-2 text-[10px]',
        'sm' => 'px-3 py-1 text-[12px]',
        'md' => 'px-4 py-1.5 text-[12px]',
    ];

    $palettes = [
        'soft' => [
            'green' => 'bg-green/10 border-green/20',
            'gold' => 'bg-gold/10 border-gold/20',
            'coral' => 'bg-coral/10 border-coral/20',
            'muted' => 'bg-muted/10 border-muted/20',
            'teal' => 'bg-teal/10 border-teal/20',
            'cyan' => 'bg-cyan/10 border-cyan/20',
            'primary' => 'bg-primary/10 border-primary/20',
        ],
        'solid' => [
            'coral' => 'bg-coral border-transparent',
            'primary' => 'bg-primary border-transparent',
            'green' => 'bg-green border-transparent',
            'gold' => 'bg-gold border-transparent',
            'teal' => 'bg-teal border-transparent',
            'cyan' => 'bg-cyan border-transparent',
            'muted' => 'bg-muted border-transparent',
        ],
    ];

    $textColors = [
        'auto' => null,
        'foreground' => 'text-foreground',
        'muted' => 'text-muted',
        'coral' => 'text-coral',
        'green' => 'text-green',
        'gold' => 'text-gold',
        'teal' => 'text-teal',
        'cyan' => 'text-cyan',
        'primary' => 'text-primary',
        'white' => 'text-white',
    ];

    $resolvedText = $text;

    if ($resolvedText === 'auto') {
        $resolvedText = $variant === 'solid'
            ? 'white'
            : 'foreground';
    }

    $classes = array_filter([
        'lm-chip',
        $sizes[$size] ?? $sizes['sm'],
        $palettes[$variant][$color] ?? $palettes['soft']['muted'],
        $caps ? 'font-bold uppercase tracking-[0.12em]' : 'font-semibold',
        $textColors[$resolvedText] ?? $textColors['foreground'],
    ]);
@endphp

<span {{ $attributes->class($classes) }}>
    {{ $slot }}
</span>
