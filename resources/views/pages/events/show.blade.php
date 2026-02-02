@extends('layouts.guest')
@use('App\Enums\RsvpStatus')

@section('title', $event->title . ' | Laravel Moris Events')

@section('body')
    <x-site.header />

    <main class="px-6 py-12">
        <div class="max-w-6xl mx-auto">
            @if (Session::has('error'))
                <div class="mb-6 p-4 bg-coral/10 border border-coral/30 rounded-lg text-coral">
                    {{ Session::get('error') }}
                </div>
            @endif

            @if (Session::has('success'))
                <div class="mb-6 p-4 bg-green/10 border border-green/30 rounded-lg text-green">
                    {{ Session::get('success') }}
                </div>
            @endif

            <div class="mb-6">
                <x-ui.button href="{{ route('events.index') }}" variant="secondary" size="sm">
                    ← All Events
                </x-ui.button>
            </div>

            {{-- Row 1: Event Info --}}
            <x-ui.card class="p-6 mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <x-ui.chip color="{{ $event->starts_at->isPast() ? 'coral' : 'green' }}">
                        {{ $event->starts_at->isPast() ? 'Past' : $event->type->label() }}
                    </x-ui.chip>
                </div>

                <x-ui.text.h1 class="text-2xl mb-3">{{ $event->title }}</x-ui.text.h1>

                @if ($event->description)
                    <x-ui.text.body class="mb-4">{{ $event->description }}</x-ui.text.body>
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
                            @php($currentStatus = $event->attendees->where('id', auth()->id())->first()?->pivot->status)
                            @php($isGoing = $currentStatus === RsvpStatus::Going->value)
                            @php($isMaybe = $currentStatus === RsvpStatus::Maybe->value)
                            <form action="{{ route('events.rsvp', $event) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="{{ RsvpStatus::Going->value }}">
                                <x-ui.button type="submit" variant="{{ $isGoing ? 'primary' : 'secondary' }}">
                                    {{ RsvpStatus::Going->label() }}
                                </x-ui.button>
                            </form>
                            <form action="{{ route('events.rsvp', $event) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="{{ RsvpStatus::Maybe->value }}">
                                <x-ui.button type="submit" variant="{{ $isMaybe ? 'primary' : 'secondary' }}">
                                    {{ RsvpStatus::Maybe->label() }}
                                </x-ui.button>
                            </form>
                            <form action="{{ route('events.rsvp', $event) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="{{ RsvpStatus::NotGoing->value }}">
                                <x-ui.button type="submit" variant="secondary">
                                    {{ RsvpStatus::NotGoing->label() }}
                                </x-ui.button>
                            </form>
                        @else
                            <x-ui.button href="{{ route('login') }}" variant="secondary">
                                Log in to RSVP
                            </x-ui.button>
                        @endauth

                        @auth
                            @if (!$event->starts_at->isPast())
                                @php($hasSubmittedPaper = auth()->user()->papers()->where('event_id', $event->id)->exists())
                                @if ($hasSubmittedPaper)
                                    <x-ui.button variant="secondary" disabled class="opacity-50">
                                        Talk Submitted
                                    </x-ui.button>
                                @else
                                    <x-ui.button href="{{ route('papers.create', $event) }}" variant="secondary"
                                        class="!text-gold !border-gold/50">
                                        Submit a Talk
                                    </x-ui.button>
                                @endif
                            @endif
                        @endauth
                    </div>
                @endif
            </x-ui.card>

            {{-- Row 2: Sponsors (Logos Only Centered) --}}
            @if ($event->sponsors->count() > 0)
                <x-ui.card class="px-6 pb-6 mb-6">
                    <x-ui.text.h3 class="mb-4">Sponsors</x-ui.text.h3>
                    <div class="flex items-center justify-center gap-6 flex-wrap">
                        @foreach ($event->sponsors as $sponsor)
                            <a href="{{ route('sponsors.show', $sponsor) }}"
                                class="block transition-opacity hover:opacity-80 p-3 bg-surface-2 rounded-lg">
                                @if ($sponsor->logo)
                                    <img src="{{ Storage::disk('public')->url($sponsor->logo) }}"
                                        alt="{{ $sponsor->name }}" class="h-20 w-auto max-w-40 object-contain">
                                @endif
                            </a>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif

            {{-- Row 3: Speakers --}}
            <div class="grid grid-cols-1 gap-6">
                {{-- Speakers Section --}}
                <x-ui.card class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <x-ui.text.h3>Speakers</x-ui.text.h3>
                        <x-ui.chip color="gold">{{ $event->speakers->count() }}</x-ui.chip>
                    </div>

                    @if ($event->speakers->count() > 0)
                        <div class="space-y-3">
                            @foreach ($event->speakers as $speaker)
                                <a href="{{ route('members.show', $speaker) }}" class="block group">
                                    <div
                                        class="p-4 bg-surface-2 rounded-lg transition-all duration-300 hover:border-primary/50 border border-transparent">
                                        {{-- Talk Title - Main Focus --}}
                                        <x-ui.text.body
                                            class="font-semibold text-lg mb-3 group-hover:text-primary transition-colors">
                                            {{ $speaker->pivot->title ?? 'Untitled Talk' }}
                                        </x-ui.text.body>

                                        {{-- Speaker Info - Secondary --}}
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $speaker->avatar }}" alt="{{ $speaker->name }}"
                                                class="w-10 h-10 rounded-full object-cover">
                                            <div class="min-w-0">
                                                <div class="font-medium text-sm truncate">{{ $speaker->name }}</div>
                                                @if ($speaker->title)
                                                    <x-ui.text.muted
                                                        class="text-xs truncate">{{ $speaker->title }}</x-ui.text.muted>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <x-ui.text.muted>No speakers yet.</x-ui.text.muted>
                    @endif
                </x-ui.card>

            </div>
        </div>
    </main>
@endsection
