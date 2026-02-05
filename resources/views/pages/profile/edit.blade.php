@extends('layouts.guest')

@section('title', 'Edit Profile | Laravel Moris')

@section('body')
    <x-site.header :links="[]" />

    <main class="px-6 py-12">
        <div class="max-w-2xl mx-auto">
            <x-card class="p-8">
                <x-heading level="2">Edit Profile</x-heading>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                    class="mt-6 space-y-6">
                    @csrf
                    @method('PATCH')

                    <x-input name="name" label="Name" value="{{ old('name', auth()->user()->name) }}" required />

                    <x-input name="title" label="Title" value="{{ old('title', auth()->user()->title) }}" />

                    <x-textarea name="bio" label="Bio" rows="4" :value="old('bio', auth()->user()->bio)" />

                    <x-avatar-upload name="avatar" label="Avatar" :currentAvatar="auth()->user()->avatar"
                        help="Upload a new photo from your device (max 2MB)" />

                    <div class="flex gap-4">
                        <x-button type="submit" variant="primary">Save Changes</x-button>
                        <x-button href="{{ route('profile.index') }}" variant="secondary">Cancel</x-button>
                    </div>
                </form>
            </x-card>
        </div>
    </main>
@endsection
