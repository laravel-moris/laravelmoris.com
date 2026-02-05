@extends('layouts.guest')
@use('App\Enums\RsvpStatus')

@section('title', 'My Profile | Laravel Moris')

@section('body')
    <x-site.header :links="[]" />

    <main class="px-6 py-12">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Column: Profile Info --}}
                <div class="lg:col-span-1">
                    <x-card class="p-6">
                        <div class="flex flex-col items-center text-center">
                            <x-avatar :src="auth()->user()->avatar" :alt="auth()->user()->name" size="2xl" class="mb-4" />
                            <x-heading level="2">{{ auth()->user()->name }}</x-heading>

                            @if (auth()->user()->title)
                                <x-text variant="muted" class="mt-1">{{ auth()->user()->title }}</x-text>
                            @endif

                            @if (auth()->user()->bio)
                                <x-text size="md" class="mt-4 text-center">{{ auth()->user()->bio }}</x-text>
                            @endif

                            <x-button href="{{ route('profile.edit') }}" variant="primary" class="mt-6 w-full">
                                Edit Profile
                            </x-button>
                        </div>
                    </x-card>
                </div>

                {{-- Right Column: Activity --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- My RSVPs Section --}}
                    <x-card class="p-6">
                        <x-heading level="3">My RSVPs</x-heading>

                        @if (auth()->user()->rsvps->count() > 0)
                            <div class="mt-4 space-y-3">
                                @foreach (auth()->user()->rsvps as $event)
                                    <a href="{{ route('events.show', $event) }}" class="block group">
                                        <div
                                            class="flex items-center justify-between p-3 bg-surface-2 rounded-lg group-hover:border-primary/50 border border-transparent transition-all">
                                            <div>
                                                <x-text size="md"
                                                    class="font-medium group-hover:text-primary transition-colors">{{ $event->title }}</x-text>
                                                <x-text variant="muted" class="text-sm">
                                                    {{ $event->starts_at->format('M d, Y') }}
                                                </x-text>
                                            </div>
                                            @php
                                                $status = $event->rsvp->status
                                                    ? RsvpStatus::from($event->rsvp->status)
                                                    : RsvpStatus::Maybe;
                                            @endphp
                                            <x-badge color="{{ $status->color() }}">
                                                {{ $status->label() }}
                                            </x-badge>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <x-text variant="muted" class="mt-4">
                                You haven't RSVP'd to any events yet.
                            </x-text>
                        @endif
                    </x-card>

                    {{-- My Talks Section --}}
                    <x-card class="p-6">
                        <x-heading level="3">My Talks</x-heading>

                        @if (auth()->user()->papers->count() > 0)
                            <div class="mt-4 space-y-3">
                                @foreach (auth()->user()->papers as $paper)
                                    <a href="{{ $paper->event ? route('events.show', $paper->event) : '#' }}"
                                        class="block group">
                                        <div
                                            class="p-3 bg-surface-2 rounded-lg group-hover:border-primary/50 border border-transparent transition-all">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="flex-1 min-w-0">
                                                    <x-text size="md"
                                                        class="font-medium group-hover:text-primary transition-colors truncate">{{ $paper->title }}</x-text>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <x-text variant="muted" class="text-sm truncate">
                                                            {{ $paper->event->title ?? 'Unknown Event' }}
                                                        </x-text>
                                                    </div>
                                                </div>
                                                @php
                                                    $paperColor = match ($paper->status->value) {
                                                        'approved' => 'green',
                                                        'rejected' => 'coral',
                                                        default => 'gold',
                                                    };
                                                @endphp
                                                <x-badge color="{{ $paperColor }}" class="shrink-0 self-center">
                                                    {{ $paper->status->value }}
                                                </x-badge>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <x-text variant="muted" class="mt-4">
                                You haven't submitted any talks yet.
                            </x-text>
                        @endif
                    </x-card>
                </div>
            </div>
        </div>
    </main>
@endsection
