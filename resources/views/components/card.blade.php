@props([
    'title' => null,
    'padded' => true,
])

<div
    {{ $attributes->class('relative overflow-hidden rounded-3xl bg-surface border border-border/70 transition-all duration-400') }}>
    @if ($title || isset($header))
        <div class="border-b border-border/70 px-6 py-4">
            @if ($title)
                <x-heading level="6">{{ $title }}</x-heading>
            @endif

            @isset($header)
                <div @class(['mt-3' => $title])>{{ $header }}</div>
            @endisset
        </div>
    @endif

    <div @class(['p-6' => $padded])>
        {{ $slot }}
    </div>
</div>
