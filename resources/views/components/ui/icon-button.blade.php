@props([
    'type' => 'button',
])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'lm-icon-btn']) }}>
    {{ $slot }}
</button>
