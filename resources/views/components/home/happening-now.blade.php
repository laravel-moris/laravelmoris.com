@props(['event'])

@php
    $startsAt = $event->startsAt;
    $endsAt = $event->endsAt;
    $meta = array_values(
        array_filter([
            $startsAt?->format('F j'),
            $startsAt && $endsAt ? sprintf('%s-%s', $startsAt->format('H:i'), $endsAt->format('H:i')) : null,
            $event->speakersCount ? sprintf('%d speakers', $event->speakersCount) : null,
        ]),
    );
@endphp

<div {{ $attributes->merge(['class' => 'lm-reveal relative overflow-hidden rounded-3xl border border-border/70 bg-surface p-6 md:p-8']) }}
    data-reveal>
    <div aria-hidden="true"
        class="pointer-events-none absolute -inset-px opacity-80 [background:radial-gradient(ellipse_600px_240px_at_20%_0%,color-mix(in_oklab,var(--color-coral)_35%,transparent)_0%,transparent_60%),radial-gradient(ellipse_520px_240px_at_80%_0%,color-mix(in_oklab,var(--color-teal)_35%,transparent)_0%,transparent_65%)]">
    </div>
    <div aria-hidden="true"
        class="pointer-events-none absolute inset-0 opacity-25 [background-image:radial-gradient(color-mix(in_oklab,var(--color-primary)_80%,transparent)_1px,transparent_1px)] [background-size:10px_10px]">
    </div>

    <div class="relative flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
        <div class="min-w-0">
            <div class="inline-flex items-center gap-2 rounded-full border border-border/70 bg-surface-2 px-3 py-1.5">
                <span class="relative flex size-2">
                    <span class="absolute inline-flex size-2 animate-ping rounded-full bg-coral/60"></span>
                    <span class="relative inline-flex size-2 rounded-full bg-coral"></span>
                </span>
                <span class="text-xs font-bold uppercase tracking-widest text-muted">Happening Now</span>
            </div>

            <x-ui.text.h4 class="mt-4">
                {{ $event->title }}
            </x-ui.text.h4>

            @if (count($meta))
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    @foreach ($meta as $item)
                        <span
                            class="inline-flex items-center gap-2 rounded-full border border-border/70 bg-surface-2 px-3 py-1.5 text-xs font-semibold text-muted">
                            <span class="inline-block size-1.5 rounded-full bg-primary"></span>
                            <span>{{ $item }}</span>
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex flex-wrap gap-3 md:flex-nowrap">
            @if (filled($event->ctaUrl))
                <x-ui.button :href="$event->ctaUrl" variant="primary" size="sm" class="rounded-xl" target="_blank"
                    rel="noopener">Join the meetup</x-ui.button>
            @endif
        </div>
    </div>
</div>
