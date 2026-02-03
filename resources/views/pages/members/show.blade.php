@extends('layouts.guest')

@section('title', $member->name . ' | Laravel Moris Members')

@section('body')
    <x-site.header />

    <main class="px-6 py-12">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Left Column: Profile Info --}}
                <div class="lg:col-span-1">
                    <x-ui.card class="p-6">
                        <div class="flex flex-col items-center text-center">
                            <img src="{{ $member->avatar }}" alt="{{ $member->name }}"
                                class="w-32 h-32 rounded-full object-cover mb-4">
                            <x-ui.text.h2>{{ $member->name }}</x-ui.text.h2>

                            @if ($member->title)
                                <x-ui.text.muted class="mt-1">{{ $member->title }}</x-ui.text.muted>
                            @endif

                             @if ($member->bio)
                                <x-ui.text.body class="mt-4 text-center">{{ $member->bio }}</x-ui.text.body>
                            @endif

                            <div class="flex items-center gap-4 mt-6 text-sm">
                                @if ($member->papers->count() > 0)
                                    <span class="flex items-center gap-1 text-gold">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                            class="w-4 h-4">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $member->papers->count() }} {{ Str::plural('talk', $member->papers->count()) }}
                                    </span>
                                @endif

                                @if ($member->rsvps->count() > 0)
                                    <span class="flex items-center gap-1 text-green">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                            class="w-4 h-4">
                                            <path fill-rule="evenodd"
                                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $member->rsvps->count() }} {{ Str::plural('event', $member->rsvps->count()) }}
                                    </span>
                                @endif
                            </div>

                            <x-ui.button href="{{ route('members.index') }}" variant="secondary" class="mt-6 w-full">
                                ← Back to Members
                            </x-ui.button>
                        </div>
                    </x-ui.card>
                </div>

                {{-- Right Column: Activity --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Speaking History Section --}}
                    <x-ui.card class="p-6">
                        <x-ui.text.h3>Speaking History</x-ui.text.h3>

                        @if ($member->speakingEvents->count() > 0)
                            <div class="mt-4 space-y-3">
                                @foreach ($member->speakingEvents as $event)
                                    <a href="{{ route('events.show', $event) }}" class="block group">
                                        <div
                                            class="p-3 bg-surface-2 rounded-lg transition-all duration-300 hover:border-primary/50 border border-transparent">
                                            <x-ui.text.body
                                                class="font-medium group-hover:text-primary transition-colors">{{ $event->paper->title ?? 'Untitled Talk' }}</x-ui.text.body>
                                            <x-ui.text.muted class="text-sm mt-1">
                                                {{ $event->title }} • {{ $event->starts_at->format('M d, Y') }}
                                            </x-ui.text.muted>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <x-ui.text.muted class="mt-4">
                                {{ $member->name }} hasn't spoken at any events yet.
                            </x-ui.text.muted>
                        @endif
                    </x-ui.card>

                    {{-- Attendance History Section --}}
                    <x-ui.card class="p-6">
                        <x-ui.text.h3>Attendance History</x-ui.text.h3>

                        @if ($member->rsvps->count() > 0)
                            <div class="mt-4 space-y-3">
                                @foreach ($member->rsvps as $event)
                                    <a href="{{ route('events.show', $event) }}" class="block group">
                                        <div
                                            class="flex items-center justify-between p-3 bg-surface-2 rounded-lg transition-all duration-300 hover:border-primary/50 border border-transparent">
                                            <div>
                                                <x-ui.text.body
                                                    class="font-medium group-hover:text-primary transition-colors">{{ $event->title }}</x-ui.text.body>
                                                <x-ui.text.muted class="text-sm">
                                                    {{ $event->starts_at->format('M d, Y') }} •
                                                    {{ $event->type->label() }}
                                                </x-ui.text.muted>
                                            </div>
                                            <x-ui.chip color="{{ $event->starts_at->isPast() ? 'coral' : 'green' }}">
                                                {{ $event->starts_at->isPast() ? 'Attended' : $event->rsvp->status }}
                                            </x-ui.chip>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <x-ui.text.muted class="mt-4">
                                {{ $member->name }} hasn't attended any events yet.
                            </x-ui.text.muted>
                        @endif
                    </x-ui.card>
                </div>
            </div>
        </div>
    </main>
@endsection
