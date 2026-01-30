@props([
    'src' => asset('images/logo.png'),
    'alt' => 'Laravel Moris - Mauritius Laravel Meetup Community',
])

<a href="{{ route('home') }}" class="inline-flex items-center">
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        class="h-11 w-auto transition-transform duration-300 ease-out hover:scale-[1.05]"
    >
</a>
