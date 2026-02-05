@props([
    'caption' => null,
])

<div class="overflow-x-auto rounded-3xl border border-border/70 bg-surface">
    <table
        {{ $attributes->class('w-full border-collapse text-left [&_thead]:border-b [&_thead]:border-border/70 [&_thead]:text-sm [&_thead]:uppercase [&_thead]:tracking-wider [&_thead]:text-muted [&_th]:px-4 [&_th]:py-4 [&_th]:font-semibold [&_tbody_tr]:border-b [&_tbody_tr]:border-border/40 [&_tbody_tr]:transition-colors [&_tbody_tr]:duration-200 [&_tbody_tr:hover]:bg-surface-2/50 [&_td]:px-4 [&_td]:py-4') }}>
        @if ($caption)
            <caption class="sr-only">{{ $caption }}</caption>
        @endif

        {{ $slot }}
    </table>
</div>
