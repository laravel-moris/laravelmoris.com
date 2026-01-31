@extends('layouts.guest')

@section('title', 'Edit Profile | Laravel Moris')

@section('body')
    <x-site.header :links="[]" />

    <main class="px-6 py-12">
        <div class="max-w-2xl mx-auto">
            <x-ui.card class="p-8">
                <x-ui.text.h2>Edit Profile</x-ui.text.h2>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                    class="mt-6 space-y-6">
                    @csrf
                    @method('PATCH')

                    <x-ui.input name="name" label="Name" value="{{ old('name', auth()->user()->name) }}" required />

                    <x-ui.input name="title" label="Title" value="{{ old('title', auth()->user()->title) }}" />

                    <div>
                        <label for="bio"
                            class="text-[12px] font-bold uppercase tracking-[0.14em] text-muted">Bio</label>
                        <textarea id="bio" name="bio" rows="4"
                            class="w-full rounded-2xl bg-surface-2 border border-border/70 px-4 py-3 text-[15px] text-foreground placeholder:text-muted/80 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/60 focus-visible:border-primary/60">{{ old('bio', auth()->user()->bio) }}</textarea>
                        @error('bio')
                            <x-ui.text.muted class="text-red-500">{{ $message }}</x-ui.text.muted>
                        @enderror
                    </div>

                    <div>
                        <label for="avatar"
                            class="text-[12px] font-bold uppercase tracking-[0.14em] text-muted">Avatar</label>
                        <input type="file" id="avatar" name="avatar" accept="image/*"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-hover">
                        <x-ui.text.muted class="mt-1">Upload a photo from your device (max 2MB)</x-ui.text.muted>
                        @error('avatar')
                            <x-ui.text.muted class="text-red-500">{{ $message }}</x-ui.text.muted>
                        @enderror
                    </div>

                    <div class="flex gap-4">
                        <x-ui.button type="submit" variant="primary">Save Changes</x-ui.button>
                        <x-ui.button href="{{ route('profile.index') }}" variant="secondary">Cancel</x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>
    </main>
@endsection
