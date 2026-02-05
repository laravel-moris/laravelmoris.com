@props([
    'items' => [],
])

@php
    $items = count($items)
        ? $items
        : [
            ['label' => 'Mauritius', 'value' => 'Tech Hub'],
            ['label' => 'Join', 'value' => 'Our Community'],
            ['label' => 'Call for', 'value' => 'Speakers'],
            ['label' => '100+', 'value' => 'Members'],
            ['label' => 'Free', 'value' => 'Pizza & Drinks'],
            ['label' => 'Tropical', 'value' => 'Tech Vibes'],
            ['label' => 'Learn', 'value' => 'Laravel Together'],
            ['label' => 'Network', 'value' => 'with Devs'],
        ];

    $loopItems = array_merge($items, $items);
@endphp

<div class="w-full overflow-hidden bg-surface border-b border-border/70 py-2.5 relative z-10">
    <div class="flex w-max motion-reduce:animate-none animate-ticker">
        @foreach ($loopItems as $item)
            @php
                $label = is_array($item) ? $item['label'] ?? null : null;
                $value = is_array($item) ? $item['value'] ?? '' : $item;
            @endphp
            <div
                class="shrink-0 px-12 text-xs font-semibold uppercase tracking-ultra text-muted whitespace-nowrap flex items-center gap-2">
                <span class="inline-block size-1 rounded-full bg-primary"></span>
                @if ($label)
                    <span class="text-primary font-bold">{{ $label }}</span>
                @endif
                <span>{{ $value }}</span>
            </div>
        @endforeach
    </div>
</div>
