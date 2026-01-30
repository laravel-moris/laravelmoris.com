@props([
    'logoSrc' => 'https://laravelmoris.com/build/assets/logo-CaoYWjBH.png',
    'logoAlt' => 'Laravel Moris - Mauritius Laravel Meetup Community',
    'links' => [],
])

@php
    $defaultLinks = [
        ['label' => 'Meetups', 'href' => '#meetups'],
        ['label' => 'Speakers', 'href' => '#speakers'],
        ['label' => 'Sponsors', 'href' => '#sponsors'],
        ['label' => 'Join Community', 'href' => '#community'],
    ];
    $navLinks = count($links) ? $links : $defaultLinks;
@endphp

<header class="relative z-[100]">
    <div class="lm-container flex items-center justify-between py-6">
        <div class="flex items-center">
            <x-site.header.logo :src="$logoSrc" :alt="$logoAlt" />
        </div>

        <div class="flex items-center gap-3 md:hidden">
            <x-site.header.theme-toggle size="sm" />

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
            @foreach ($navLinks as $link)
                <x-site.header.nav-link
                    :href="$link['href']"
                    :label="$link['label']"
                />
            @endforeach

            @auth
                <x-site.header.nav-link
                    href="{{ route('profile.index') }}"
                    label="Profile"
                />
                <form action="{{ route('logout') }}" method="POST" class="flex items-center">
                    @csrf
                    <x-ui.button type="submit" variant="secondary" size="sm" class="rounded-lg">
                        Log Out
                    </x-ui.button>
                </form>
            @else
                <x-ui.button href="{{ route('login') }}" variant="primary" size="sm" class="rounded-lg">
                    Log In
                </x-ui.button>
            @endauth

            <x-site.header.theme-toggle size="md" class="ml-2 size-12" />
        </nav>
    </div>

    <x-site.header.mobile-menu :links="$navLinks" />
</header>
