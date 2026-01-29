@props([
    'href' => '#',
    'icon' => null,
    'name' => null,
    'description' => null,
])

<a href="{{ $href }}" class="group lm-card lm-reveal lm-card-accent-top rounded-3xl px-8 py-12 text-center no-underline text-foreground group-hover:-translate-y-2 group-hover:border-green before:bg-green" data-reveal>
    <div class="mx-auto mb-6 grid size-[90px] place-items-center rounded-full bg-surface-2 text-primary font-black text-[24px] border border-border/70 transition duration-300 group-hover:bg-green group-hover:text-white group-hover:border-transparent group-hover:scale-[1.05] group-hover:rotate-[-3deg]">
        {{ $icon }}
    </div>

    <h3 class="text-[20px] font-bold tracking-[-0.01em]">{{ $name }}</h3>
    <p class="mt-3 text-[14px] text-muted leading-relaxed">{{ $description }}</p>
</a>
