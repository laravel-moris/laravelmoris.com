@props([
    'href' => '#',
    'text' => 'Special',
    'duration' => 1.2,
])

@php
    $characters = mb_str_split($text);
    $charCount = count($characters);
    // Stagger each character by a fraction of the duration
    $staggerFactor = 0.08;
@endphp

<a href="{{ $href }}"
    {{ $attributes->class(['relative text-sm font-semibold uppercase tracking-wide py-1 lm-rainbow-text']) }}>
    @foreach ($characters as $index => $char)
        @php
            $delay = round($index * $staggerFactor, 3);
            $style = "animation-delay: {$delay}s";
        @endphp
        @if ($char === ' ')
            <span class="inline-block w-1"></span>
        @else
            <span class="inline-block" style="{{ $style }}">{{ $char }}</span>
        @endif
    @endforeach
</a>
