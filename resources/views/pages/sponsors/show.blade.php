@extends('layouts.guest')

@section('title', $sponsor->name . ' | Laravel Moris Sponsors')

@section('body')
    <x-site.header />

    <main class="px-6 py-12">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Column: Sponsor Info --}}
                <div class="lg:col-span-1">
                    <x-card class="p-6">
                        <div class="flex flex-col items-center text-center">
                            <div
                                class="w-full h-48 rounded-xl bg-white overflow-hidden mb-4 flex items-center justify-center p-6">
                                <img src="{{ $logoUrl }}" alt="{{ $sponsor->name }}"
                                    class="max-w-full max-h-full object-contain">
                            </div>

                            <x-heading level="2">{{ $sponsor->name }}</x-heading>

                            @if ($sponsor->events->count() > 0)
                                <x-text variant="muted" class="mt-2">
                                    {{ $sponsor->events->count() }} {{ Str::plural('event', $sponsor->events->count()) }}
                                    sponsored
                                </x-text>
                            @endif

                            @if ($sponsor->website)
                                <x-button href="{{ $sponsor->website }}" variant="primary" class="mt-6 w-full"
                                    target="_blank" rel="noopener noreferrer">
                                    Visit Website
                                </x-button>
                            @endif

                            <x-button href="{{ route('sponsors.index') }}" variant="secondary" class="mt-3 w-full">
                                ← Back to Sponsors
                            </x-button>
                        </div>
                    </x-card>
                </div>

                {{-- Right Column: Events Sponsored --}}
                <div class="lg:col-span-2">
                    <x-card class="p-6">
                        <x-heading level="3">Events Sponsored</x-heading>

                        @if ($sponsor->events->count() > 0)
                            <div class="mt-4 space-y-3">
                                @foreach ($sponsor->events as $event)
                                    <a href="{{ route('events.show', $event) }}" class="block group">
                                        <div
                                            class="flex items-center justify-between p-4 bg-surface-2 rounded-lg transition-all duration-300 group-hover:border-primary/50 border border-transparent">
                                            <div>
                                                <x-text size="md"
                                                    class="font-medium group-hover:text-primary transition-colors">{{ $event->title }}</x-text>
                                                <x-text variant="muted" class="text-sm">
                                                    {{ $event->starts_at->format('M d, Y') }} • {{ $event->type->label() }}
                                                </x-text>
                                            </div>
                                            <x-badge color="{{ $event->starts_at->isPast() ? 'coral' : 'green' }}">
                                                {{ $event->starts_at->isPast() ? 'Past' : 'Upcoming' }}
                                            </x-badge>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <x-text variant="muted" class="mt-4">
                                {{ $sponsor->name }} hasn't sponsored any events yet.
                            </x-text>
                        @endif
                    </x-card>
                </div>
            </div>
        </div>
    </main>
@endsection
