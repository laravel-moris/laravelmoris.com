@props([
    'caption' => null,
])

<div class="overflow-x-auto rounded-3xl border border-border/70 bg-surface">
    <table {{ $attributes->class('lm-table') }}>
        @if ($caption)
            <caption class="sr-only">{{ $caption }}</caption>
        @endif

        {{ $slot }}
    </table>
</div>
