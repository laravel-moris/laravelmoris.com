@props([
    'speaker',
])

<div class="group lm-card lm-reveal rounded-3xl px-8 py-10 text-center group-hover:-translate-y-2 group-hover:border-teal" data-reveal>
    <div class="absolute inset-x-0 bottom-0 h-1 origin-left scale-x-0 bg-teal transition-transform duration-[400ms] ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-x-100"></div>

    <div class="mx-auto mb-6 size-[120px] overflow-hidden rounded-full bg-surface-2 border-4 border-surface transition-transform duration-300 group-hover:scale-[1.05]">
        <img src="{{ $speaker->avatarUrl }}" alt="{{ $speaker->name }}" class="h-full w-full object-cover" loading="lazy">
    </div>

    <h3 class="text-[20px] font-bold tracking-[-0.01em]">{{ $speaker->name }}</h3>

    @if (filled($speaker->title))
        <p class="mt-2 text-[13px] font-semibold uppercase tracking-[0.14em] text-teal">{{ $speaker->title }}</p>
    @endif

    @if (filled($speaker->bio))
        <p class="mt-4 text-[14px] leading-relaxed text-muted">{{ $speaker->bio }}</p>
    @endif
</div>
