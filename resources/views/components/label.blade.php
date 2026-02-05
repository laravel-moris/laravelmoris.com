@props([
    'for' => null,
])

<label @if ($for) for="{{ $for }}" @endif
    {{ $attributes->class('text-label uppercase text-muted') }}>
    {{ $slot }}
</label>
