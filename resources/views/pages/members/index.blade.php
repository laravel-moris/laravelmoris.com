@extends('layouts.guest')

@section('title', 'Members | Laravel Moris')

@section('body')
    <x-site.header />

    <main class="px-6 py-12">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <x-heading level="1">Our Community Members</x-heading>
                <x-text variant="muted" class="mt-2 max-w-2xl mx-auto">
                    Meet the passionate developers who make up the Laravel Moris community.
                </x-text>
            </div>

            @if ($members->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach ($members as $member)
                        <a href="{{ route('members.show', $member) }}" class="group block">
                            <x-card class="p-5 transition-all duration-300 hover:border-primary/50 h-full">
                                <div class="flex flex-col items-center text-center">
                                    <img src="{{ $member->avatar }}" alt="{{ $member->name }}"
                                        class="w-20 h-20 rounded-full object-cover mb-3">
                                    <x-heading level="3" class="text-lg group-hover:text-primary transition-colors">
                                        {{ $member->name }}
                                    </x-heading>

                                    @if ($member->title)
                                        <x-text variant="muted"
                                            class="mt-1 font-semibold uppercase tracking-widest text-teal text-xs">
                                            {{ $member->title }}
                                        </x-text>
                                    @endif

                                    @if ($member->bio)
                                        <x-text variant="muted" class="mt-2 text-sm leading-relaxed line-clamp-2">
                                            {{ $member->bio }}
                                        </x-text>
                                    @endif

                                    <div class="flex items-center gap-4 mt-3 text-sm">
                                        @if ($member->papers_count > 0)
                                            <span class="flex items-center gap-1 text-gold">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" class="w-4 h-4">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                {{ $member->papers_count }} {{ Str::plural('talk', $member->papers_count) }}
                                            </span>
                                        @endif

                                        @if ($member->rsvps_count > 0)
                                            <span class="flex items-center gap-1 text-green">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" class="w-4 h-4">
                                                    <path fill-rule="evenodd"
                                                        d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                {{ $member->rsvps_count }} {{ Str::plural('event', $member->rsvps_count) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </x-card>
                        </a>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $members->links() }}
                </div>
            @else
                <x-card class="p-12 text-center">
                    <x-heading level="3">No Members Yet</x-heading>
                    <x-text variant="muted" class="mt-2">
                        Be the first to join our community!
                    </x-text>
                    <x-button href="{{ route('login') }}" variant="primary" class="mt-6">
                        Join Community
                    </x-button>
                </x-card>
            @endif
        </div>
    </main>
@endsection
