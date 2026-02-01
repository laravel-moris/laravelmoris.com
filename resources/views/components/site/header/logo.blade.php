<a href="{{ route('home') }}" class="inline-flex items-center">
    <picture>
        <source srcset="{{ Vite::asset('resources/images/logo.webp') }}" type="image/webp">
        <img fetchpriority="high" src="{{ Vite::asset('resources/images/logo.png') }}"
            alt="Laravel Moris - Mauritius Laravel Meetup Community" width="88" height="88"
            class="h-11 w-auto transition-transform duration-300 ease-out hover:scale-[1.05]">
    </picture>
</a>
