@props([
    'title' => null,
    'accent' => null,
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'mb-12 md:mb-16']) }}>
    @if ($title)
        <h2 class="text-[clamp(32px,5vw,48px)] font-bold tracking-[-0.02em]">
            {{ $title }}
            @if ($accent)
                <span class="text-coral">{{ $accent }}</span>
            @endif
        </h2>
    @endif

    @if ($subtitle)
        <p class="mt-4 text-muted text-[14px] font-medium uppercase tracking-[0.14em]">{{ $subtitle }}</p>
    @endif

    @if (trim($slot) !== '')
        <div class="mt-6">{{ $slot }}</div>
    @endif
</div>
