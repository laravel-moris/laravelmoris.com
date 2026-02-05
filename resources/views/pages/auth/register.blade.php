@extends('layouts.guest')

@section('title', 'Register | Laravel Moris')

@section('body')
    <x-site.header :links="[]" />

    <main class="px-6 py-12">
        <div class="max-w-2xl mx-auto">
            <x-card class="p-8">
                <x-heading level="2">Create Account</x-heading>
                <x-text variant="muted" class="mt-2">Join the Laravel Moris community</x-text>

                <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data"
                    class="mt-6 space-y-6">
                    @csrf

                    <x-input name="name" label="Name" value="{{ old('name') }}" required />

                    <x-input name="email" type="email" label="Email" value="{{ old('email') }}" required />

                    <x-input name="password" type="password" label="Password" required />
                    <x-text variant="muted" class="-mt-4">Must have 8+ characters, uppercase, lowercase, number, and
                        symbol</x-text>

                    <x-input name="password_confirmation" type="password" label="Confirm Password" required />

                    <x-input name="title" label="Title" value="{{ old('title') }}" />

                    <x-textarea name="bio" label="Bio" rows="4" />

                    <x-avatar-upload name="avatar" label="Avatar" help="Upload a photo from your device (max 2MB)" />

                    <div class="flex gap-4">
                        <x-button type="submit" variant="primary">Create Account</x-button>
                        <x-button href="{{ route('login') }}" variant="secondary">Already have an account?</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </main>
@endsection
