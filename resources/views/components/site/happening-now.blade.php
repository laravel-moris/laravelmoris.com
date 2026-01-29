@props([
    'title' => 'Happening Now',
    'headline' => null,
    'meta' => [],
])

@php
    $meta = count($meta) ? $meta : [];
@endphp

<div {{ $attributes->merge(['class' => 'lm-reveal relative overflow-hidden rounded-3xl border border-border/70 bg-surface p-6 md:p-8']) }} data-reveal>
    <div aria-hidden="true" class="pointer-events-none absolute -inset-px opacity-80 [background:radial-gradient(ellipse_600px_240px_at_20%_0%,color-mix(in_oklab,var(--color-coral)_35%,transparent)_0%,transparent_60%),radial-gradient(ellipse_520px_240px_at_80%_0%,color-mix(in_oklab,var(--color-teal)_35%,transparent)_0%,transparent_65%)]"></div>
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 opacity-25 [background-image:radial-gradient(color-mix(in_oklab,var(--color-primary)_80%,transparent)_1px,transparent_1px)] [background-size:10px_10px]"></div>

    <div class="relative flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
        <div class="min-w-0">
            <div class="inline-flex items-center gap-2 rounded-full border border-border/70 bg-surface-2 px-3 py-1.5">
                <span class="relative flex size-2">
                    <span class="absolute inline-flex size-2 animate-ping rounded-full bg-coral/60"></span>
                    <span class="relative inline-flex size-2 rounded-full bg-coral"></span>
                </span>
                <span class="text-[11px] font-bold uppercase tracking-[0.14em] text-muted">{{ $title }}</span>
            </div>

            <p class="mt-4 text-balance text-[22px] font-bold tracking-[-0.02em] md:text-[26px]">
                {{ $headline }}
            </p>

            @if (count($meta))
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    @foreach ($meta as $item)
                        <span class="inline-flex items-center gap-2 rounded-full border border-border/70 bg-surface-2 px-3 py-1.5 text-[12px] font-semibold text-muted">
                            <span class="inline-block size-1.5 rounded-full bg-primary"></span>
                            <span>{{ $item }}</span>
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex flex-wrap gap-3 md:flex-nowrap">
            <x-ui.button href="#" variant="primary" size="sm" class="rounded-xl">Join the meetup</x-ui.button>
        </div>
    </div>
</div>
