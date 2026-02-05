@extends('layouts.guest')
@use('App\Enums\RsvpStatus')

@section('title', $event->title . ' | Laravel Moris Events')

@section('body')
    <x-site.header />

    <main class="px-6 py-12">
        <div class="max-w-6xl mx-auto">
            @if (Session::has('error'))
                <x-alert variant="danger" class="mb-6">{{ Session::get('error') }}</x-alert>
            @endif

            @if (Session::has('success'))
                <x-alert variant="success" class="mb-6">{{ Session::get('success') }}</x-alert>
            @endif

            <div class="mb-6">
                <x-button href="{{ route('events.index') }}" variant="secondary" size="sm">
                    ← All Events
                </x-button>
            </div>

            {{-- Row 1: Event Info --}}
            <x-card class="p-6 mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <x-badge color="{{ $event->starts_at->isPast() ? 'coral' : 'green' }}">
                        {{ $event->starts_at->isPast() ? 'Past' : $event->type->label() }}
                    </x-badge>
                </div>

                <x-heading level="1" class="text-2xl mb-3">{{ $event->title }}</x-heading>

                @if ($event->description)
                    <x-text size="md" class="mb-4">{{ $event->description }}</x-text>
                @endif

                <div class="flex flex-wrap items-center gap-4 text-sm mb-6">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="w-5 h-5 text-muted shrink-0">
                            <path fill-rule="evenodd"
                                d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ $event->starts_at->format('F d, Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="w-5 h-5 text-muted shrink-0">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ $event->starts_at->format('g:i A') }} - {{ $event->ends_at->format('g:i A') }}</span>
                    </div>

                    @if ($event->location)
                        <div class="flex items-center gap-2">
                            @if ($event->type->value === 'physical')
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-5 h-5 text-muted shrink-0">
                                    <path fill-rule="evenodd"
                                        d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                        clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-5 h-5 text-muted shrink-0">
                                    <path fill-rule="evenodd"
                                        d="M19.902 4.098a3.75 3.75 0 0 0-5.304 0l-4.5 4.5a3.75 3.75 0 0 0 1.035 6.037.75.75 0 0 1-.646 1.353 5.25 5.25 0 0 1-1.449-8.45l4.5-4.5a5.25 5.25 0 1 1 7.424 7.424l-1.757 1.757a.75.75 0 1 1-1.06-1.06l1.757-1.757a3.75 3.75 0 0 0 0-5.304Zm-7.389 4.267a.75.75 0 0 1 1-.353 5.25 5.25 0 0 1 1.449 8.45l-4.5 4.5a5.25 5.25 0 1 1-7.424-7.424l1.757-1.757a.75.75 0 1 1 1.06 1.06l-1.757 1.757a3.75 3.75 0 1 0 5.304 5.304l4.5-4.5a3.75 3.75 0 0 0-1.035-6.037.75.75 0 0 1-.354-1Z"
                                        clip-rule="evenodd" />
                                </svg>
                            @endif
                            <a href="{{ $event->location->directions_url ?? ($event->location->url ?? '#') }}"
                                target="_blank" rel="noopener noreferrer"
                                class="border-b border-dotted border-current hover:text-primary transition-colors cursor-pointer">
                                @if ($event->type->value === 'physical')
                                    <span class="font-medium">{{ $event->location->venue_name }}</span>
                                    <span class="text-muted"> - {{ $event->location->address }}</span>
                                @else
                                    <span class="font-medium">Online - {{ $event->location->platform }}</span>
                                @endif
                            </a>
                        </div>
                    @endif
                </div>

                {{-- RSVP Buttons --}}
                @if (!$event->starts_at->isPast())
                    <div class="flex items-center gap-3">
                        @auth
                            @php
                                $currentStatus = $event
                                    ->attendees()
                                    ->whereKey(auth()->id())
                                    ->first()?->rsvp->status;
                                $isGoing = $currentStatus === RsvpStatus::Going->value;
                                $isMaybe = $currentStatus === RsvpStatus::Maybe->value;
                            @endphp
                            <form action="{{ route('events.rsvp', $event) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="{{ RsvpStatus::Going->value }}">
                                <x-button type="submit" variant="{{ $isGoing ? 'primary' : 'secondary' }}">
                                    {{ RsvpStatus::Going->label() }}
                                </x-button>
                            </form>
                            <form action="{{ route('events.rsvp', $event) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="{{ RsvpStatus::Maybe->value }}">
                                <x-button type="submit" variant="{{ $isMaybe ? 'primary' : 'secondary' }}">
                                    {{ RsvpStatus::Maybe->label() }}
                                </x-button>
                            </form>
                            <form action="{{ route('events.rsvp', $event) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="{{ RsvpStatus::NotGoing->value }}">
                                <x-button type="submit" variant="secondary">
                                    {{ RsvpStatus::NotGoing->label() }}
                                </x-button>
                            </form>
                        @else
                            <x-button href="{{ route('login') }}" variant="secondary">
                                Log in to RSVP
                            </x-button>
                        @endauth

                        @auth
                            @if (!$event->starts_at->isPast())
                                @php
                                    $hasSubmittedPaper = auth()
                                        ->user()
                                        ->papers()
                                        ->where('event_id', $event->id)
                                        ->exists();
                                @endphp
                                @if ($hasSubmittedPaper)
                                    <x-button variant="secondary" disabled class="opacity-50">
                                        Talk Submitted
                                    </x-button>
                                @else
                                    <x-button href="{{ route('papers.create', $event) }}" variant="gold">
                                        Submit a Talk
                                    </x-button>
                                @endif
                            @endif
                        @endauth
                    </div>
                @endif
            </x-card>

            {{-- Row 2: Sponsors (Logos Only Centered) --}}
            @if ($event->sponsors->count() > 0)
                <x-card class="px-6 pb-6 mb-6">
                    <x-heading level="3" class="mb-4">Sponsors</x-heading>
                    <div class="flex items-center justify-center gap-6 flex-wrap">
                        @foreach ($event->sponsors as $sponsor)
                            <a href="{{ route('sponsors.show', $sponsor) }}"
                                class="block transition-opacity hover:opacity-80 p-3 bg-surface-2 rounded-lg">
                                <img src="{{ $sponsor->logo }}" alt="{{ $sponsor->name }}"
                                    class="h-20 w-auto max-w-40 object-contain">
                            </a>
                        @endforeach
                    </div>
                </x-card>
            @endif

            {{-- Row 3: Speakers --}}
            <x-card class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <x-heading level="3">Speakers</x-heading>
                    <x-badge color="gold">{{ $event->speakers->count() }}</x-badge>
                </div>

                @if ($event->speakers->count() > 0)
                    <div class="space-y-3">
                        @foreach ($event->speakers as $speaker)
                            <a href="{{ route('members.show', $speaker) }}" class="block group">
                                <div
                                    class="p-4 bg-surface-2 rounded-lg transition-all duration-300 hover:border-primary/50 border border-transparent">
                                    {{-- Talk Title - Main Focus --}}
                                    <x-text size="md"
                                        class="font-semibold text-lg mb-3 group-hover:text-primary transition-colors">
                                        {{ $speaker->paper->title ?? 'Untitled Talk' }}
                                    </x-text>

                                    {{-- Speaker Info - Secondary --}}
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $speaker->avatar }}" alt="{{ $speaker->name }}"
                                            class="w-10 h-10 rounded-full object-cover">
                                        <div class="min-w-0">
                                            <div class="font-medium text-sm truncate">{{ $speaker->name }}</div>
                                            @if ($speaker->title)
                                                <x-text variant="muted"
                                                    class="text-xs truncate">{{ $speaker->title }}</x-text>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <x-text variant="muted">No speakers yet.</x-text>
                @endif
            </x-card>
        </div>
    </main>
@endsection
