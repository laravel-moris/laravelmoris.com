@props([
    'brand' => 'Laravel Moris',
    'year' => '2026',
    'location' => 'Mauritius, Indian Ocean',
])

<footer class="relative z-10 mt-24 bg-surface border-t border-border/70 px-6 py-10 md:px-12 md:py-20 text-center">
    <p class="text-muted text-[14px] font-medium max-md:text-[12px]">
        &copy; {{ $year }}
        <a href="#"
            class="relative text-coral font-bold no-underline after:content-[''] after:absolute after:left-0 after:-bottom-0.5 after:h-0.5 after:w-0 after:bg-coral after:transition-[width] after:duration-300 hover:after:w-full">{{ $brand }}</a>
        &mdash; Built with love in Mauritius
    </p>

    <div
        class="mx-auto mt-4 inline-flex items-center gap-2 rounded-full bg-surface-2 border border-border/70 px-4 py-2 text-[12px] font-semibold text-muted">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
            class="size-4 text-orange">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span>{{ $location }}</span>
    </div>
</footer>
