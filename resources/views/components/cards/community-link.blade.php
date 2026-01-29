@props([
    'link',
])

<a href="{{ $link->url }}" class="group lm-card lm-reveal lm-card-accent-top rounded-3xl px-8 py-12 text-center no-underline text-foreground group-hover:-translate-y-2 group-hover:border-green before:bg-green" data-reveal>
    <div class="mx-auto mb-6 grid size-[90px] place-items-center rounded-full bg-surface-2 border border-border/70 transition duration-300 group-hover:border-green group-hover:scale-[1.05] group-hover:rotate-[-3deg]">
        <i class="ci {{ $link->iconKey }} ci-3x"></i>
    </div>

    <h3 class="text-[20px] font-bold tracking-[-0.01em]">{{ $link->name }}</h3>
    <p class="mt-3 text-[14px] text-muted leading-relaxed">{{ $link->description }}</p>
</a>
