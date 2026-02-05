@extends('layouts.guest')

@section('title', 'Login | Laravel Moris')

@section('body')
    <x-site.header :links="[]" />

    <main class="flex min-h-[calc(100dvh-200px)] items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">
            <div class="text-center mb-10">
                <x-heading level="2" class="text-display-sm">Welcome Back</x-heading>
                <x-text variant="muted" class="mt-3 text-base">
                    Sign in to join the Laravel Moris community
                </x-text>
            </div>

            <x-card class="p-8">
                <div class="space-y-4">
                    @provider('github')
                        <x-button href="{{ route('auth.provider', 'github') }}" variant="github" size="md"
                            class="w-full h-12">
                            <img src="{{ Vite::asset('resources/images/logos/github-light.svg') }}" alt="GitHub"
                                class="size-5">
                            Continue with GitHub
                        </x-button>
                    @else
                        <x-button variant="github" size="md" disabled title="GitHub OAuth is not configured"
                            class="w-full h-12">
                            <img src="{{ Vite::asset('resources/images/logos/github-light.svg') }}" alt="GitHub"
                                class="size-5">
                            GitHub Unavailable
                        </x-button>
                    @endprovider

                    @provider('google')
                        <x-button href="{{ route('auth.provider', 'google') }}" variant="google" size="md"
                            class="w-full h-12">
                            <img src="{{ Vite::asset('resources/images/logos/google.svg') }}" alt="Google" class="size-5">
                            Continue with Google
                        </x-button>
                    @else
                        <x-button variant="google" size="md" disabled title="Google OAuth is not configured"
                            class="w-full h-12">
                            <img src="{{ Vite::asset('resources/images/logos/google.svg') }}" alt="Google" class="size-5">
                            Google Unavailable
                        </x-button>
                    @endprovider
                </div>

                <div class="mt-8 pt-8 border-t border-border/50">
                    <x-text variant="muted" class="text-center mb-6">
                        Or sign in with email
                    </x-text>

                    <form action="{{ route('login.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <x-input name="email" type="email" label="Email" value="{{ old('email') }}" required />

                        <x-input name="password" type="password" label="Password" required />

                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remember"
                                    class="rounded border-border/70 bg-surface-2 text-primary focus:ring-primary/60">
                                <span class="text-sm text-muted">Remember me</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                    class="text-sm text-primary hover:text-primary-hover">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <x-button type="submit" variant="primary" size="md" class="w-full h-12">
                            Sign In
                        </x-button>
                    </form>

                    <div class="mt-6 text-center">
                        <x-text variant="muted" class="text-sm">
                            Don't have an account?
                            <a href="{{ route('register.create') }}"
                                class="text-primary hover:text-primary-hover font-medium">Register</a>
                        </x-text>
                    </div>

                    <x-text variant="muted" class="mt-8 text-center">
                        By continuing, you agree to our
                        <a href="{{ route('terms') }}"
                            class="text-primary hover:text-primary-hover underline underline-offset-4">Terms of Service</a>
                        and
                        <a href="{{ route('privacy') }}"
                            class="text-primary hover:text-primary-hover underline underline-offset-4">Privacy Policy</a>
                    </x-text>
                </div>
            </x-card>
        </div>
    </main>
@endsection
