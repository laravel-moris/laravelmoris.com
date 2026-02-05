@use('App\Enums\Permissions')

@props([
    'links' => [],
])

@php
    $defaultLinks = [
        ['label' => 'Events', 'href' => route('events.index')],
        ['label' => 'Members', 'href' => route('members.index')],
        ['label' => 'Sponsors', 'href' => route('sponsors.index')],
        ['label' => 'Join Community', 'href' => route('join.index')],
    ];
    $navLinks = count($links) ? $links : $defaultLinks;
@endphp

<header class="relative z-[100]">
    <div class="mx-auto max-w-7xl px-6 md:px-12 flex items-center justify-between py-6">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="inline-flex items-center">
            <picture>
                <source srcset="{{ Vite::asset('resources/images/logo.webp') }}" type="image/webp">
                <img fetchpriority="high" src="{{ Vite::asset('resources/images/logo.png') }}"
                    alt="Laravel Moris - Mauritius Laravel Meetup Community" width="88" height="88"
                    class="h-11 w-auto transition-transform duration-300 ease-out hover:scale-[1.05]">
            </picture>
        </a>

        {{-- Mobile: Theme toggle + Hamburger --}}
        <div class="flex items-center gap-3 md:hidden">
            <x-icon-button data-theme-toggle aria-label="Toggle theme" aria-pressed="true">
                <svg data-theme-icon="moon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2" class="absolute size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <svg data-theme-icon="sun" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2" class="absolute size-5 hidden">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </x-icon-button>

            <x-icon-button data-mobile-menu-toggle aria-label="Open menu" aria-expanded="false"
                aria-controls="mobile-menu">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </x-icon-button>
        </div>

        {{-- Desktop Navigation --}}
        <nav class="hidden md:flex items-center gap-10">
            @foreach ($navLinks as $link)
                <a href="{{ $link['href'] }}"
                    class="relative text-muted text-sm font-semibold uppercase tracking-wide py-1 transition-colors hover:text-foreground after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-0 after:bg-primary after:transition-[width] after:duration-300 hover:after:w-full">
                    {{ $link['label'] }}
                </a>
            @endforeach

            @auth
                @can(Permissions::AccessAdminPanel->value)
                    <x-rainbow-nav-link href="{{ route('filament.admin.pages.dashboard') }}" text="Admin" />
                @endcan
                <a href="{{ route('profile.index') }}"
                    class="relative text-muted text-sm font-semibold uppercase tracking-wide py-1 transition-colors hover:text-foreground after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-0.5 after:w-0 after:bg-primary after:transition-[width] after:duration-300 hover:after:w-full">
                    Profile
                </a>
                <form action="{{ route('logout') }}" method="POST" class="flex items-center">
                    @csrf
                    <x-button type="submit" variant="secondary" size="sm" class="rounded-lg">
                        Log Out
                    </x-button>
                </form>
            @else
                <x-button href="{{ route('login') }}" variant="primary" size="sm" class="rounded-lg">
                    Log In
                </x-button>
            @endauth

            <x-icon-button data-theme-toggle aria-label="Toggle theme" aria-pressed="true" class="ml-2 size-12">
                <svg data-theme-icon="moon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2" class="absolute size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <svg data-theme-icon="sun" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2" class="absolute size-6 hidden">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </x-icon-button>
        </nav>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="group md:hidden hidden" data-mobile-menu data-open="false">
        <div class="fixed inset-0 z-[110]" aria-modal="true" role="dialog">
            <button type="button"
                class="absolute inset-0 bg-background/30 backdrop-blur-sm opacity-0 transition-opacity duration-300 group-data-[open=true]:opacity-100"
                data-mobile-menu-close aria-label="Close menu"></button>

            <div class="absolute right-0 top-0 h-full w-[min(360px,92vw)] bg-surface border-l border-border/70 shadow-2xl translate-x-full transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] will-change-transform group-data-[open=true]:translate-x-0"
                data-mobile-menu-panel>
                <div class="flex items-center justify-between px-6 py-5 border-b border-border/70">
                    <span class="text-sm font-semibold uppercase tracking-wider text-muted">Menu</span>
                    <x-icon-button class="size-10" data-mobile-menu-close aria-label="Close menu">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </x-icon-button>
                </div>

                <div class="px-6 py-6">
                    <div class="flex flex-col gap-2">
                        @foreach ($navLinks as $link)
                            <a href="{{ $link['href'] }}"
                                class="rounded-xl px-4 py-3 text-sm font-semibold tracking-snug text-foreground border border-transparent hover:border-border/70 hover:bg-surface-2 transition"
                                data-mobile-menu-close>
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-6 pt-6 border-t border-border/70">
                        @auth
                            @can(Permissions::AccessAdminPanel->value)
                                <x-rainbow-nav-link href="{{ route('filament.admin.pages.dashboard') }}" text="Admin"
                                    class="flex items-center gap-3 px-4 py-3 rounded-xl border border-transparent hover:border-border/70 hover:bg-surface-2 transition"
                                    data-mobile-menu-close />
                            @endcan
                            <a href="{{ route('profile.index') }}"
                                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold tracking-snug text-foreground border border-transparent hover:border-border/70 hover:bg-surface-2 transition"
                                data-mobile-menu-close>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profile
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold tracking-snug text-foreground border border-transparent hover:border-border/70 hover:bg-surface-2 transition"
                                    data-mobile-menu-close>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Log Out
                                </button>
                            </form>
                        @else
                            <x-button href="{{ route('login') }}" variant="primary" size="md"
                                class="w-full rounded-xl">Log In</x-button>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
