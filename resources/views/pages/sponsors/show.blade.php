@extends('layouts.guest')

@section('title', $sponsor->name . ' | Laravel Moris Sponsors')

@section('body')
    <x-site.header />

    <main class="px-6 py-12">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Column: Sponsor Info --}}
                <div class="lg:col-span-1">
                    <x-ui.card class="p-6">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="w-full h-48 rounded-xl bg-white/5 border border-border/70 overflow-hidden mb-4 flex items-center justify-center p-6">
                                <img src="{{ $logoUrl }}" alt="{{ $sponsor->name }}"
                                    class="max-w-full max-h-full object-contain">
                            </div>

                            <x-ui.text.h2>{{ $sponsor->name }}</x-ui.text.h2>

                            @if ($sponsor->events->count() > 0)
                                <x-ui.text.muted class="mt-2">
                                    {{ $sponsor->events->count() }} {{ Str::plural('event', $sponsor->events->count()) }}
                                    sponsored
                                </x-ui.text.muted>
                            @endif

                            @if ($sponsor->website)
                                <x-ui.button href="{{ $sponsor->website }}" variant="primary" class="mt-6 w-full"
                                    target="_blank" rel="noopener noreferrer">
                                    Visit Website
                                </x-ui.button>
                            @endif

                            <x-ui.button href="{{ route('sponsors.index') }}" variant="secondary" class="mt-3 w-full">
                                ← Back to Sponsors
                            </x-ui.button>
                        </div>
                    </x-ui.card>
                </div>

                {{-- Right Column: Events Sponsored --}}
                <div class="lg:col-span-2">
                    <x-ui.card class="p-6">
                        <x-ui.text.h3>Events Sponsored</x-ui.text.h3>

                        @if ($sponsor->events->count() > 0)
                            <div class="mt-4 space-y-3">
                                @foreach ($sponsor->events as $event)
                                    <a href="{{ route('events.show', $event) }}" class="block group">
                                        <div
                                            class="flex items-center justify-between p-4 bg-surface-2 rounded-lg transition-all duration-300 group-hover:border-primary/50 border border-transparent">
                                            <div>
                                                <x-ui.text.body
                                                    class="font-medium group-hover:text-primary transition-colors">{{ $event->title }}</x-ui.text.body>
                                                <x-ui.text.muted class="text-sm">
                                                    {{ $event->starts_at->format('M d, Y') }} • {{ $event->type->label() }}
                                                </x-ui.text.muted>
                                            </div>
                                            <x-ui.chip color="{{ $event->starts_at->isPast() ? 'coral' : 'green' }}">
                                                {{ $event->starts_at->isPast() ? 'Past' : 'Upcoming' }}
                                            </x-ui.chip>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <x-ui.text.muted class="mt-4">
                                {{ $sponsor->name }} hasn't sponsored any events yet.
                            </x-ui.text.muted>
                        @endif
                    </x-ui.card>
                </div>
            </div>
        </div>
    </main>

    <x-site.footer />
@endsection
