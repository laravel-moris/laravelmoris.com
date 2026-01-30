@props([
    'title' => null,
    'padded' => true,
])

<div {{ $attributes->class('lm-card') }}>
    @if ($title || isset($header))
        <div class="border-b border-border/70 px-6 py-4">
            @if ($title)
                <x-ui.text.h6>{{ $title }}</x-ui.text.h6>
            @endif

            @if (isset($header))
                <div class="mt-3">{{ $header }}</div>
            @endif
        </div>
    @endif

    <div class="{{ $padded ? 'p-6' : '' }}">
        {{ $slot }}
    </div>
</div>
