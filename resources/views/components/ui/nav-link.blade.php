@props([
    'href',
    'active' => false,
])

@php
    $classes = array_filter([
        'inline-flex items-center rounded-xl px-4 py-2 text-[13px] font-semibold uppercase tracking-[0.08em] text-muted',
        'border border-transparent transition-[background-color,border-color,color] duration-200',
        'hover:text-foreground hover:bg-surface-2 hover:border-border/70',
        $active ? 'text-foreground bg-surface-2 border-border/70' : null,
    ]);
@endphp

<a
    href="{{ $href }}"
    @if ($active) aria-current="page" @endif
    {{ $attributes->class($classes) }}
>
    {{ $slot }}
</a>
