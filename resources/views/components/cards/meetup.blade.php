@props([
    'card',
])

@php
    $featured = $card->featured;
    $title = $card->title;
    $speakers = $card->speakers;

    $startsAt = $card->startsAt;
    $isUpcoming = $startsAt->isFuture();
    $month = $startsAt->format('F');
    $day = $startsAt->format('j');

    $rootClasses = implode(' ', array_filter([
        'group lm-card lm-reveal lm-card-accent-top p-9',
        'before:bg-coral',
        $featured ? '-translate-y-2 border-coral before:scale-x-100' : 'group-hover:-translate-y-2 group-hover:border-coral',
    ]));
@endphp

<div class="{{ $rootClasses }}" data-reveal>
    <span
        class="absolute right-6 top-6 rounded-full px-4 py-2 text-[10px] font-bold uppercase tracking-[0.12em] border {{ $isUpcoming ? ($featured ? 'bg-coral text-white border-transparent' : 'bg-coral/10 text-coral border-coral/20') : 'bg-muted/10 text-muted border-muted/20' }}"
    >
        {{ $isUpcoming ? 'Upcoming' : 'Past' }}
    </span>

    <div class="inline-flex flex-col items-center rounded-2xl bg-surface-2 border border-border/70 px-5 py-4 transition-transform duration-300 {{ $featured ? 'bg-coral border-transparent scale-[1.05]' : 'group-hover:scale-[1.05] group-hover:bg-coral group-hover:border-transparent' }}">
        <span class="text-[11px] font-bold uppercase tracking-[0.14em] {{ $featured ? 'text-white/85' : 'text-muted group-hover:text-white/85' }}">{{ $month }}</span>
        <span class="mt-1 text-[32px] font-bold leading-none {{ $featured ? 'text-white' : 'text-foreground group-hover:text-white' }}">{{ $day }}</span>
    </div>

    <x-ui.text.h3 class="mt-6 text-[20px] font-bold leading-snug tracking-[-0.01em]">{{ $title }}</x-ui.text.h3>

    <div class="mt-5 flex border-t border-border/70 pt-5">
        @foreach ($speakers as $speaker)
            <div class="size-10 rounded-full bg-surface-2 border-[3px] border-surface -ml-3 first:ml-0 overflow-hidden transition-transform duration-300 group-hover:-translate-y-1 hover:z-10 hover:-translate-y-1.5 hover:scale-[1.05]">
                <img
                    src="{{ $speaker->avatarUrl }}"
                    alt="{{ $speaker->name }}"
                    class="h-full w-full object-cover"
                    loading="lazy"
                >
            </div>
        @endforeach
    </div>
</div>
