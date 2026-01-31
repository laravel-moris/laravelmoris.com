@extends('layouts.guest')

@section('title', 'Submit Talk | Laravel Moris')

@section('body')
    <x-site.header />

    <main class="px-6 py-12">
        <div class="max-w-2xl mx-auto">
            <div class="mb-6">
                <x-ui.button href="{{ route('events.show', $event) }}" variant="secondary" size="sm">
                    ← Back to Event
                </x-ui.button>
            </div>

            <x-ui.card class="p-6">
                <x-ui.text.h1 class="text-2xl mb-2">Submit a Talk</x-ui.text.h1>
                <x-ui.text.muted class="mb-6">
                    Share your knowledge with the Laravel Moris community at {{ $event->title }}
                </x-ui.text.muted>

                @if (Session::has('error'))
                    <div class="mb-6 p-4 bg-coral/10 border border-coral/30 rounded-lg text-coral">
                        {{ Session::get('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-coral/10 border border-coral/30 rounded-lg">
                        <ul class="list-disc list-inside text-coral">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('papers.store', $event) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <x-ui.input label="Talk Title" name="title" placeholder="Enter your talk title" required />
                    </div>

                    <div class="mb-6">
                        <x-ui.textarea label="Description" name="description"
                            placeholder="Describe what you'll talk about..." rows="5" />
                    </div>

                    <div class="mb-4">
                        <x-ui.input label="Phone Number" name="phone" type="tel" placeholder="Mobile/WhatsApp"
                            help="We'll use this to number to contact you" />
                    </div>

                    <div class="mb-4">
                        <x-ui.input label="Contact Email" name="secondaryEmail" type="email"
                            placeholder="contact@example.com" help="Optional email so we can reach you" />
                    </div>

                    <div class="flex items-center gap-3">
                        <x-ui.button type="submit" variant="primary">
                            Submit Talk
                        </x-ui.button>
                        <x-ui.button href="{{ route('events.show', $event) }}" variant="secondary">
                            Cancel
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>
    </main>

    <x-site.footer />
@endsection
