@props([
    'variant' => 'info',
    'title' => null,
    'dismissible' => false,
])

@php
    $base = 'relative rounded-2xl border border-border/70 bg-surface px-4 py-3 text-sm leading-relaxed text-foreground';
    $variants = [
        'success' => 'border-green/25 bg-green/10',
        'info' => 'border-cyan/25 bg-cyan/10',
        'warning' => 'border-gold/25 bg-gold/10',
        'danger' => 'border-coral/25 bg-coral/10',
    ];

    $classes = array_filter([$base, $variants[$variant] ?? $variants['info']]);
@endphp

<div role="alert" data-ui-alert {{ $attributes->class($classes) }}>
    @if ($dismissible)
        <button type="button" aria-label="Dismiss alert" data-ui-alert-dismiss
            class="absolute right-2 top-2 grid size-8 place-items-center rounded-full text-muted transition-[background-color,color] hover:bg-surface-2 hover:text-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/60">
            <svg viewBox="0 0 20 20" fill="currentColor" class="size-4" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M4.47 4.47a.75.75 0 0 1 1.06 0L10 8.94l4.47-4.47a.75.75 0 1 1 1.06 1.06L11.06 10l4.47 4.47a.75.75 0 0 1-1.06 1.06L10 11.06l-4.47 4.47a.75.75 0 0 1-1.06-1.06L8.94 10 4.47 5.53a.75.75 0 0 1 0-1.06Z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    @endif

    @if ($title)
        <x-ui.text.h6>{{ $title }}</x-ui.text.h6>
    @endif

    <div class="{{ $title ? 'mt-2' : '' }}">
        {{ $slot }}
    </div>
</div>
