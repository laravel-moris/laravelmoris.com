@extends('layouts.guest')

@section('title', 'Events | Laravel Moris')

@section('body')
    <x-site.header />

    <main class="px-6 py-12">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <x-heading level="1">All Events</x-heading>
                <x-text variant="muted" class="mt-2 max-w-2xl mx-auto">
                    Join our upcoming meetups or explore past events.
                </x-text>
            </div>

            {{-- Upcoming Events --}}
            @if ($upcomingEvents->count() > 0)
                <div class="mb-12">
                    <x-heading level="2" class="mb-6">Upcoming Events</x-heading>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($upcomingEvents as $event)
                            <a href="{{ route('events.show', $event) }}" class="group block">
                                <x-card class="p-6 h-full transition-all duration-300 hover:border-primary/50">
                                    <div class="flex items-start justify-between mb-4">
                                        <x-badge color="green">
                                            {{ $event->type->label() }}
                                        </x-badge>
                                        <x-text variant="muted" class="text-sm">
                                            {{ $event->starts_at->format('M d, Y') }}
                                        </x-text>
                                    </div>

                                    <x-heading level="3" class="group-hover:text-primary transition-colors mb-2">
                                        {{ $event->title }}
                                    </x-heading>

                                    @if ($event->description)
                                        <x-text variant="muted" class="text-sm line-clamp-2 mb-4">
                                            {{ $event->description }}
                                        </x-text>
                                    @endif

                                    <div class="flex items-center gap-4 text-sm">
                                        <span class="text-gold">
                                            {{ $event->speakers_count }}
                                            {{ Str::plural('speaker', $event->speakers_count) }}
                                        </span>
                                        <span class="text-teal">
                                            {{ $event->attendees_count }}
                                            {{ Str::plural('attendee', $event->attendees_count) }}
                                        </span>
                                    </div>
                                </x-card>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Past Events --}}
            @if ($pastEvents->count() > 0)
                <div>
                    <x-heading level="2" class="mb-6">Past Events</x-heading>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($pastEvents as $event)
                            <a href="{{ route('events.show', $event) }}" class="group block">
                                <x-card
                                    class="p-6 h-full transition-all duration-300 hover:border-primary/50 opacity-75 hover:opacity-100">
                                    <div class="flex items-start justify-between mb-4">
                                        <x-badge color="coral">
                                            {{ $event->type->label() }}
                                        </x-badge>
                                        <x-text variant="muted" class="text-sm">
                                            {{ $event->starts_at->format('M d, Y') }}
                                        </x-text>
                                    </div>

                                    <x-heading level="3" class="group-hover:text-primary transition-colors mb-2">
                                        {{ $event->title }}
                                    </x-heading>

                                    @if ($event->description)
                                        <x-text variant="muted" class="text-sm line-clamp-2 mb-4">
                                            {{ $event->description }}
                                        </x-text>
                                    @endif

                                    <div class="flex items-center gap-4 text-sm">
                                        <span class="text-gold">
                                            {{ $event->speakers_count }}
                                            {{ Str::plural('speaker', $event->speakers_count) }}
                                        </span>
                                        <span class="text-teal">
                                            {{ $event->attendees_count }}
                                            {{ Str::plural('attendee', $event->attendees_count) }}
                                        </span>
                                    </div>
                                </x-card>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($upcomingEvents->count() === 0 && $pastEvents->count() === 0)
                <x-card class="p-12 text-center">
                    <x-heading level="3">No Events Yet</x-heading>
                    <x-text variant="muted" class="mt-2">
                        Check back soon for upcoming meetups!
                    </x-text>
                </x-card>
            @endif
        </div>
    </main>
@endsection
