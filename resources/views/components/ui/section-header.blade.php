@props([
    'title' => null,
    'accent' => null,
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'mb-12 md:mb-16']) }}>
    @if ($title)
        <x-ui.text.h2>
            {{ $title }}
            @if ($accent)
                <span class="text-coral">{{ $accent }}</span>
            @endif
        </x-ui.text.h2>
    @endif

    @if ($subtitle)
        <x-ui.text.muted class="mt-4">{{ $subtitle }}</x-ui.text.muted>
    @endif

    @if (trim($slot) !== '')
        <div class="mt-6">{{ $slot }}</div>
    @endif
</div>
