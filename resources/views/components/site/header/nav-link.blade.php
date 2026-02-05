@props([
    'href' => '#',
    'active' => false,
    'label' => '',
])

<a href="{{ $href }}" @class([
    'relative text-muted text-sm font-semibold uppercase tracking-wide py-1 transition-colors hover:text-foreground',
    'after:content-[""] after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-full after:bg-primary after:transition-[width] after:duration-300' => $active,
    'after:content-[""] after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-0 after:bg-primary after:transition-[width] after:duration-300 hover:after:w-full' => !$active,
])>
    {{ $label }}
</a>
