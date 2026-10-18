@extends('layouts.guest')

@section('title', 'Verify Email | Laravel Moris')

@section('body')
    <x-site.header :links="[]" />

    <main class="px-6 py-12">
        <div class="max-w-lg mx-auto">
            <x-card class="p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-amber-500/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-amber-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>

                <x-heading level="2">Verify Your Email</x-heading>
                <x-text variant="muted" class="mt-3">
                    Thanks for signing up! Before getting started, could you verify your email address by clicking the link
                    we just emailed to you? If you didn't receive the email, we'll gladly send you another.
                </x-text>

                @if(session('success'))
                    <x-alert variant="success" class="mt-4">{{ session('success') }}</x-alert>
                @endif

                <div class="mt-6">
                    <form action="{{ route('verification.send') }}" method="POST">
                        @csrf
                        <x-button type="submit" variant="primary" class="w-full">
                            Resend Verification Email
                        </x-button>
                    </form>
                </div>

                <div class="mt-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <x-button type="submit" variant="secondary" class="w-full">
                            Log Out
                        </x-button>
                    </form>
                </div>
            </x-card>
        </div>
    </main>
@endsection
