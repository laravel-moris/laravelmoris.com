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
                    <x-ui.card class="p-6">
                        <div class="flex flex-col items-center text-center">
                            <img
                                src="{{ auth()->user()->avatar }}"
                                alt="{{ auth()->user()->name }}"
                                class="w-32 h-32 rounded-full object-cover mb-4"
                            >
                            <x-ui.text.h2>{{ auth()->user()->name }}</x-ui.text.h2>

                            @if(auth()->user()->title)
                                <x-ui.text.muted class="mt-1">{{ auth()->user()->title }}</x-ui.text.muted>
                            @endif

                            @if(auth()->user()->bio)
                                <x-ui.text.body class="mt-4 text-left">{{ auth()->user()->bio }}</x-ui.text.body>
                            @endif

                            <x-ui.button
                                href="{{ route('profile.edit') }}"
                                variant="primary"
                                class="mt-6 w-full"
                            >
                                Edit Profile
                            </x-ui.button>
                        </div>
                    </x-ui.card>
                </div>

                {{-- Right Column: Activity --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- My RSVPs Section --}}
                    <x-ui.card class="p-6">
                        <x-ui.text.h3>My RSVPs</x-ui.text.h3>

                        @if(auth()->user()->rsvps->count() > 0)
                            <div class="mt-4 space-y-3">
                                @foreach(auth()->user()->rsvps as $event)
                                    <a href="{{ route('events.show', $event) }}" class="block group">
                                        <div class="flex items-center justify-between p-3 bg-surface-2 rounded-lg group-hover:border-primary/50 border border-transparent transition-all">
                                            <div>
                                                <x-ui.text.body class="font-medium group-hover:text-primary transition-colors">{{ $event->title }}</x-ui.text.body>
                                                <x-ui.text.muted class="text-sm">
                                                    {{ $event->starts_at->format('M d, Y') }}
                                                </x-ui.text.muted>
                                            </div>
                                            @php
                                                $status = $event->pivot->status ? RsvpStatus::from($event->pivot->status) : RsvpStatus::Maybe
                                            @endphp
                                            <x-ui.chip color="{{ $status->color() }}">
                                                {{ $status->label() }}
                                            </x-ui.chip>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <x-ui.text.muted class="mt-4">
                                You haven't RSVP'd to any events yet.
                            </x-ui.text.muted>
                        @endif
                    </x-ui.card>

                    {{-- My Talks Section --}}
                    <x-ui.card class="p-6">
                        <x-ui.text.h3>My Talks</x-ui.text.h3>

                        @if(auth()->user()->papers->count() > 0)
                            <div class="mt-4 space-y-3">
                                @foreach(auth()->user()->papers as $paper)
                                    <a href="{{ $paper->event ? route('events.show', $paper->event) : '#' }}" class="block group">
                                        <div class="p-3 bg-surface-2 rounded-lg group-hover:border-primary/50 border border-transparent transition-all">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="flex-1 min-w-0">
                                                    <x-ui.text.body class="font-medium group-hover:text-primary transition-colors truncate">{{ $paper->title }}</x-ui.text.body>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <x-ui.text.muted class="text-sm truncate">
                                                            {{ $paper->event->title ?? 'Unknown Event' }}
                                                        </x-ui.text.muted>
                                                    </div>
                                                </div>
                                                @php
                                                    $paperColor = match($paper->status->value) {
                                                        'approved' => 'green',
                                                        'rejected' => 'coral',
                                                        default => 'gold',
                                                    };
                                                @endphp
                                                <x-ui.chip color="{{ $paperColor }}" class="shrink-0 self-center">
                                                    {{ $paper->status->value }}
                                                </x-ui.chip>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <x-ui.text.muted class="mt-4">
                                You haven't submitted any talks yet.
                            </x-ui.text.muted>
                        @endif
                    </x-ui.card>
                </div>
            </div>
        </div>
    </main>
@endsection
