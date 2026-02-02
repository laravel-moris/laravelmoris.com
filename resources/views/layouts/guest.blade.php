<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="Mauritius Laravel Meetup Community - Join local Laravel developers for talks, networking, and learning.">

    <title>@yield('title', 'Laravel Moris')</title>

    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/images/logo.webp') }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            :root {
                font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
                background: #0a0908;
                color: #faf9f7;
            }
        </style>
    @endif

    @stack('head')
</head>

<body
    class="min-h-dvh bg-background text-foreground font-sans overflow-x-hidden relative transition-colors duration-[400ms] ease-[cubic-bezier(0.4,0,0.2,1)]">
    <div aria-hidden="true"
        class="pointer-events-none fixed inset-0 -z-10 opacity-20 [background-image:radial-gradient(var(--color-primary)_0.8px,transparent_0.8px)] [background-size:12px_12px]">
    </div>
    <div aria-hidden="true"
        class="pointer-events-none fixed inset-0 -z-10 [background:radial-gradient(ellipse_600px_400px_at_15%_30%,var(--color-background)_0%,transparent_70%),radial-gradient(ellipse_500px_350px_at_85%_25%,var(--color-background)_0%,transparent_65%),radial-gradient(ellipse_700px_500px_at_50%_80%,var(--color-background)_0%,transparent_75%)]">
    </div>

    @yield('body')

    <x-site.footer />

    @stack('scripts')
</body>

</html>
