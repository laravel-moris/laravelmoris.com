@props([
    'title' => null,
    'accent' => null,
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'mb-12 md:mb-16']) }}>
    @if ($title)
        <x-heading level="2">
            {{ $title }}
            @if ($accent)
                <span class="text-coral">{{ $accent }}</span>
            @endif
        </x-heading>
    @endif

    @if ($subtitle)
        <x-text variant="muted" class="mt-4">{{ $subtitle }}</x-text>
    @endif

    @if (trim($slot) !== '')
        <div class="mt-6">{{ $slot }}</div>
    @endif
</div>
