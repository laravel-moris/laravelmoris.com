@extends('layouts.guest')

@section('title', 'Sponsors | Laravel Moris')

@section('body')
    <x-site.header />

    <main class="px-6 py-12">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <x-ui.text.h1>Our Sponsors</x-ui.text.h1>
                <x-ui.text.muted class="mt-2 max-w-2xl mx-auto">
                    Thanks to our amazing sponsors who make our meetups possible.
                </x-ui.text.muted>
            </div>

            @if (count($sponsors) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach ($sponsors as $sponsor)
                        <a href="{{ route('sponsors.show', $sponsor['id']) }}" class="group block">
                            <x-ui.card class="p-5 transition-all duration-300 hover:border-primary/50 h-full">
                                <div class="flex flex-col items-center text-center">
                                    <div
                                        class="w-full h-32 rounded-xl bg-white/5 border border-border/70 overflow-hidden mb-3 flex items-center justify-center p-4">
                                        <img src="{{ $sponsor['logoUrl'] }}" alt="{{ $sponsor['name'] }}"
                                            class="max-w-full max-h-full object-contain">
                                    </div>

                                    <x-ui.text.h3 class="text-lg group-hover:text-primary transition-colors">
                                        {{ $sponsor['name'] }}
                                    </x-ui.text.h3>

                                    @if ($sponsor['eventsCount'] > 0)
                                        <x-ui.text.muted class="mt-2 text-sm">
                                            {{ $sponsor['eventsCount'] }}
                                            {{ Str::plural('event', $sponsor['eventsCount']) }} sponsored
                                        </x-ui.text.muted>
                                    @endif

                                    @if ($sponsor['website'])
                                        <x-ui.text.muted class="mt-1 text-xs text-teal">
                                            {{ parse_url($sponsor['website'], PHP_URL_HOST) }}
                                        </x-ui.text.muted>
                                    @endif
                                </div>
                            </x-ui.card>
                        </a>
                    @endforeach
                </div>
            @else
                <x-ui.card class="p-12 text-center">
                    <x-ui.text.h3>No Sponsors Yet</x-ui.text.h3>
                    <x-ui.text.muted class="mt-2">
                        Interested in sponsoring our events? Get in touch!
                    </x-ui.text.muted>
                    <x-ui.button href="mailto:sponsors@laravelmoris.com" variant="primary" class="mt-6">
                        Become a Sponsor
                    </x-ui.button>
                </x-ui.card>
            @endif
        </div>
    </main>
@endsection
