@props([
    'logoSrc' => 'https://laravelmoris.com/build/assets/logo-CaoYWjBH.png',
    'logoAlt' => 'Laravel Moris - Mauritius Laravel Meetup Community',
    'links' => [],
])

@php
    $links = count($links) ? $links : [
        ['label' => 'Meetups', 'href' => '#meetups'],
        ['label' => 'Speakers', 'href' => '#speakers'],
        ['label' => 'Sponsors', 'href' => '#sponsors'],
        ['label' => 'Join Community', 'href' => '#community'],
    ];
@endphp

<header class="relative z-[100]">
    <div class="lm-container flex items-center justify-between py-6">
        <div class="flex items-center">
            <a href="#" class="inline-flex items-center">
                <img src="{{ $logoSrc }}" alt="{{ $logoAlt }}" class="h-11 w-auto transition-transform duration-300 ease-out hover:scale-[1.05]">
            </a>
        </div>

        <nav class="hidden md:flex items-center gap-10">
            @foreach ($links as $link)
                <a
                    href="{{ $link['href'] }}"
                    class="relative text-muted text-[13px] font-semibold uppercase tracking-[0.08em] py-1 transition-colors hover:text-foreground after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-0 after:bg-primary after:transition-[width] after:duration-300 hover:after:w-full"
                >
                    {{ $link['label'] }}
                </a>
            @endforeach

            <x-ui.button href="#" variant="primary" size="sm" class="rounded-lg">Log In</x-ui.button>

            <button
                type="button"
                class="relative ml-2 grid size-12 place-items-center rounded-full bg-surface-2 border border-border/70 text-muted transition hover:border-primary hover:text-primary hover:scale-[1.05] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/60"
                data-theme-toggle
                aria-label="Toggle theme"
                aria-pressed="true"
            >
                <svg data-theme-icon="moon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="absolute size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <svg data-theme-icon="sun" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="absolute size-6 hidden">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </button>
        </nav>
    </div>
</header>
