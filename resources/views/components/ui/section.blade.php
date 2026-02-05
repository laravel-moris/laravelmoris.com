@props([
    'id' => null,
])

<section @if ($id) id="{{ $id }}" @endif
    {{ $attributes->class('relative z-10 py-16 md:py-24') }}>
    <div class="mx-auto max-w-7xl px-6 md:px-12">
        {{ $slot }}
    </div>
</section>
