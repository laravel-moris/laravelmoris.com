@props([
    'title' => 'LARAVEL',
    'highlight' => 'MORIS',
    'subtitle' => 'Mauritius Laravel Meetup Community',
])

<section class="relative z-10 flex min-h-[85vh] flex-col items-center justify-center text-center px-6 py-12 md:px-12">
    <h1 class="font-display text-[clamp(56px,12vw,140px)] font-bold leading-[0.95] tracking-[-0.02em] opacity-0 animate-[lm-hero-title_1.2s_cubic-bezier(0.16,1,0.3,1)_forwards] [animation-delay:200ms]">
        {{ $title }} <span class="text-coral inline-block">{{ $highlight }}</span>
    </h1>

    <p class="relative mt-6 max-w-[500px] text-muted text-[16px] font-medium uppercase tracking-[0.2em] opacity-0 animate-[lm-hero-subtitle_1s_cubic-bezier(0.16,1,0.3,1)_forwards] [animation-delay:400ms] before:content-[''] before:absolute before:top-1/2 before:right-[calc(100%+24px)] before:h-px before:w-[60px] before:bg-border/70 before:opacity-70 after:content-[''] after:absolute after:top-1/2 after:left-[calc(100%+24px)] after:h-px after:w-[60px] after:bg-border/70 after:opacity-70 max-md:before:hidden max-md:after:hidden">
        {{ $subtitle }}
    </p>

    <div class="mt-12 flex flex-wrap justify-center gap-5 opacity-0 animate-[lm-hero-cta_1s_cubic-bezier(0.16,1,0.3,1)_forwards] [animation-delay:600ms] max-md:w-full max-md:max-w-[320px] max-md:flex-col">
        <x-ui.button href="#" variant="primary">Join Next Meetup</x-ui.button>
        <x-ui.button href="#" variant="secondary">Become a Speaker</x-ui.button>
        <x-ui.button href="#" variant="secondary">Become a Sponsor</x-ui.button>
    </div>
</section>
