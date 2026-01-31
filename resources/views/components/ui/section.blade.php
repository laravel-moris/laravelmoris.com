@props([
    'id' => null,
])

<section @if ($id) id="{{ $id }}" @endif
    {{ $attributes->merge(['class' => 'lm-section']) }}>
    <div class="lm-container">
        {{ $slot }}
    </div>
</section>
