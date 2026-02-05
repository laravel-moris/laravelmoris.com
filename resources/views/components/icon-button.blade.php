@props([
    'type' => 'button',
])

<button type="{{ $type }}"
    {{ $attributes->class('relative grid size-11 place-items-center rounded-full bg-surface-2 border border-border/70 text-muted transition-all duration-300 hover:border-primary hover:text-primary hover:scale-105 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/60') }}>
    {{ $slot }}
</button>
