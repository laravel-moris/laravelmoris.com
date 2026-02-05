@extends('layouts.guest')

@section('title', 'Register | Laravel Moris')

@section('body')
    <x-site.header :links="[]" />

    <main class="px-6 py-12">
        <div class="max-w-2xl mx-auto">
            <x-ui.card class="p-8">
                <x-ui.text.h2>Create Account</x-ui.text.h2>
                <x-ui.text.muted class="mt-2">Join the Laravel Moris community</x-ui.text.muted>

                <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data"
                    class="mt-6 space-y-6">
                    @csrf

                    <x-ui.input name="name" label="Name" value="{{ old('name') }}" required />

                    <x-ui.input name="email" type="email" label="Email" value="{{ old('email') }}" required />

                    <x-ui.input name="password" type="password" label="Password" required />
                    <x-ui.text.muted class="-mt-4">Must have 8+ characters, uppercase, lowercase, number, and
                        symbol</x-ui.text.muted>

                    <x-ui.input name="password_confirmation" type="password" label="Confirm Password" required />

                    <x-ui.input name="title" label="Title" value="{{ old('title') }}" />

                    <div>
                        <label for="bio" class="text-label uppercase text-muted">Bio</label>
                        <textarea id="bio" name="bio" rows="4"
                            class="w-full rounded-2xl bg-surface-2 border border-border/70 px-4 py-3 text-base text-foreground placeholder:text-muted/80 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/60 focus-visible:border-primary/60">{{ old('bio') }}</textarea>
                        @error('bio')
                            <x-ui.text.muted class="text-red-500">{{ $message }}</x-ui.text.muted>
                        @enderror
                    </div>

                    <div>
                        <label for="avatar" class="text-label uppercase text-muted">Avatar</label>
                        <input type="file" id="avatar" name="avatar" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-hover">
                        <x-ui.text.muted class="mt-1">Upload a photo from your device (max 2MB)</x-ui.text.muted>
                        @error('avatar')
                            <x-ui.text.muted class="text-red-500">{{ $message }}</x-ui.text.muted>
                        @enderror
                    </div>

                    <div class="flex gap-4">
                        <x-ui.button type="submit" variant="primary">Create Account</x-ui.button>
                        <x-ui.button href="{{ route('login') }}" variant="secondary">Already have an account?</x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>
    </main>
@endsection
