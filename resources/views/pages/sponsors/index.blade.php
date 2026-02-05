@extends('layouts.guest')

@section('title', 'Sponsors | Laravel Moris')

@section('body')
    <x-site.header />

    <main class="px-6 py-12">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <x-heading level="1">Our Sponsors</x-heading>
                <x-text variant="muted" class="mt-2 max-w-2xl mx-auto">
                    Thanks to our amazing sponsors who make our meetups possible.
                </x-text>
            </div>

            @if (count($sponsors) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach ($sponsors as $sponsor)
                        <a href="{{ route('sponsors.show', $sponsor['id']) }}" class="group block">
                            <x-card class="p-5 transition-all duration-300 hover:border-primary/50 h-full">
                                <div class="flex flex-col items-center text-center">
                                    <div
                                        class="w-full h-32 rounded-xl bg-white/5 border border-border/70 overflow-hidden mb-3 flex items-center justify-center p-4">
                                        <img src="{{ $sponsor['logoUrl'] }}" alt="{{ $sponsor['name'] }}"
                                            class="max-w-full max-h-full object-contain">
                                    </div>

                                    <x-heading level="3" class="text-lg group-hover:text-primary transition-colors">
                                        {{ $sponsor['name'] }}
                                    </x-heading>

                                    @if ($sponsor['eventsCount'] > 0)
                                        <x-text variant="muted" class="mt-2 text-sm">
                                            {{ $sponsor['eventsCount'] }}
                                            {{ Str::plural('event', $sponsor['eventsCount']) }} sponsored
                                        </x-text>
                                    @endif

                                    @if ($sponsor['website'])
                                        <x-text variant="muted" class="mt-1 text-xs text-teal">
                                            {{ parse_url($sponsor['website'], PHP_URL_HOST) }}
                                        </x-text>
                                    @endif
                                </div>
                            </x-card>
                        </a>
                    @endforeach
                </div>
            @else
                <x-card class="p-12 text-center">
                    <x-heading level="3">No Sponsors Yet</x-heading>
                    <x-text variant="muted" class="mt-2">
                        Interested in sponsoring our events? Get in touch!
                    </x-text>
                    <x-button href="mailto:sponsors@laravelmoris.com" variant="primary" class="mt-6">
                        Become a Sponsor
                    </x-button>
                </x-card>
            @endif
        </div>
    </main>
@endsection
