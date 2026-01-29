@props([
    'initials' => null,
    'name' => null,
    'role' => null,
    'bio' => null,
    'links' => [],
])

<div class="group lm-card lm-reveal rounded-3xl px-8 py-10 text-center group-hover:-translate-y-2 group-hover:border-teal" data-reveal>
    <div class="absolute inset-x-0 bottom-0 h-1 origin-left scale-x-0 bg-teal transition-transform duration-[400ms] ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-x-100"></div>

    <div class="mx-auto mb-6 grid size-[120px] place-items-center rounded-full bg-surface-2 text-primary font-extrabold text-[40px] border-4 border-surface transition-transform duration-300 group-hover:scale-[1.05]">
        {{ $initials }}
    </div>

    <h3 class="text-[20px] font-bold tracking-[-0.01em]">{{ $name }}</h3>

    <p class="mt-2 text-[13px] font-semibold uppercase tracking-[0.14em] text-teal">{{ $role }}</p>

    <p class="mt-4 text-[14px] leading-relaxed text-muted">{{ $bio }}</p>

    @if (count($links))
        <div class="mt-6 flex justify-center gap-3">
            @foreach ($links as $link)
                <a
                    href="{{ $link['href'] }}"
                    class="grid size-[44px] place-items-center rounded-full border border-border/70 text-muted text-[12px] font-bold transition duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] hover:-translate-y-0.5 hover:scale-[1.05] hover:bg-teal hover:border-teal hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-teal/50"
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>
    @endif
</div>
