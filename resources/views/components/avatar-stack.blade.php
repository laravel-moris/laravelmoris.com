@props([
    'items' => [],
    'size' => 'sm',
    'border' => 'stack',
])

@php
    $items = collect($items);
@endphp

<div {{ $attributes->class(['flex']) }}>
    @foreach ($items as $item)
        @php
            $src = is_array($item)
                ? $item['src'] ?? ($item['avatarUrl'] ?? null)
                : data_get($item, 'src') ?? data_get($item, 'avatarUrl');

            $alt = is_array($item)
                ? $item['alt'] ?? ($item['name'] ?? '')
                : data_get($item, 'alt') ?? (data_get($item, 'name') ?? '');
        @endphp

        @if (filled($src))
            <x-avatar :src="$src" :alt="$alt" :size="$size" :border="$border"
                class="-ml-3 first:ml-0 overflow-hidden transition-transform duration-300 group-hover:-translate-y-1 hover:z-10 hover:-translate-y-1.5 hover:scale-[1.05]" />
        @endif
    @endforeach
</div>
