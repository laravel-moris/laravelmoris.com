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

        <div class="flex items-center gap-3 md:hidden">
            <x-ui.icon-button
                data-theme-toggle
                aria-label="Toggle theme"
                aria-pressed="true"
            >
                <svg data-theme-icon="moon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="absolute size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <svg data-theme-icon="sun" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="absolute size-5 hidden">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </x-ui.icon-button>

            <x-ui.icon-button
                data-mobile-menu-toggle
                aria-label="Open menu"
                aria-expanded="false"
                aria-controls="mobile-menu"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </x-ui.icon-button>
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

            <x-ui.icon-button
                class="ml-2 size-12"
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
            </x-ui.icon-button>
        </nav>
    </div>

    <div id="mobile-menu" class="group md:hidden hidden" data-mobile-menu data-open="false">
        <div class="fixed inset-0 z-[110]" aria-modal="true" role="dialog">
            <button type="button" class="absolute inset-0 bg-background/30 backdrop-blur-sm opacity-0 transition-opacity duration-300 group-data-[open=true]:opacity-100" data-mobile-menu-close aria-label="Close menu"></button>

            <div class="absolute right-0 top-0 h-full w-[min(360px,92vw)] bg-surface border-l border-border/70 shadow-2xl translate-x-full transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] will-change-transform group-data-[open=true]:translate-x-0" data-mobile-menu-panel>
                <div class="flex items-center justify-between px-6 py-5 border-b border-border/70">
                    <span class="text-[13px] font-semibold uppercase tracking-[0.12em] text-muted">Menu</span>
                    <x-ui.icon-button
                        class="size-10"
                        data-mobile-menu-close
                        aria-label="Close menu"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </x-ui.icon-button>
                </div>

                <div class="px-6 py-6">
                    <div class="flex flex-col gap-2">
                        @foreach ($links as $link)
                            <a
                                href="{{ $link['href'] }}"
                                class="rounded-xl px-4 py-3 text-[14px] font-semibold tracking-[-0.01em] text-foreground border border-transparent hover:border-border/70 hover:bg-surface-2 transition"
                                data-mobile-menu-close
                            >
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <x-ui.button href="#" variant="primary" size="md" class="w-full rounded-xl">Log In</x-ui.button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
