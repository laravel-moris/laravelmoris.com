@extends('layouts.guest')

@section('title', 'Login | Laravel Moris')

@section('body')
    <x-site.header :links="[]" />

    <main class="flex min-h-[calc(100dvh-200px)] items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">
            <div class="text-center mb-10">
                <x-ui.text.h2 class="text-[clamp(28px,4vw,36px)]">Welcome Back</x-ui.text.h2>
                <x-ui.text.muted class="mt-3 text-[15px]">
                    Sign in to join the Laravel Moris community
                </x-ui.text.muted>
            </div>

            <x-ui.card class="p-8">
                <div class="space-y-4">
                    @provider('github')
                        <x-ui.button href="{{ route('auth.provider', 'github') }}" variant="primary" size="md"
                            class="w-full h-12 !bg-[#24292e] hover:!bg-[#2f363d] !text-white !border-0 !rounded-lg">
                            <img src="{{ Vite::asset('resources/images/logos/github-light.svg') }}" alt="GitHub" class="size-5">
                            Continue with GitHub
                        </x-ui.button>
                    @else
                        <x-ui.button variant="primary" size="md" disabled title="GitHub OAuth is not configured"
                            class="w-full h-12 !bg-[#24292e]/50 !text-white/70 !border-0 !rounded-lg opacity-50 cursor-not-allowed">
                            <img src="{{ Vite::asset('resources/images/logos/github-light.svg') }}" alt="GitHub" class="size-5">
                            GitHub Disabled
                        </x-ui.button>
                    @endprovider

                    @provider('google')
                        <x-ui.button href="{{ route('auth.provider', 'google') }}" variant="primary" size="md"
                            class="w-full h-12 !bg-white hover:!bg-gray-50 !text-gray-900 !border !border-gray-200 !rounded-lg">
                            <img src="{{ Vite::asset('resources/images/logos/google.svg') }}" alt="Google" class="size-5">
                            Continue with Google
                        </x-ui.button>
                    @else
                        <x-ui.button variant="primary" size="md" disabled title="Google OAuth is not configured"
                            class="w-full h-12 !bg-gray-100 !text-gray-500 !border !border-gray-200 !rounded-lg opacity-50 cursor-not-allowed">
                            <img src="{{ Vite::asset('resources/images/logos/google.svg') }}" alt="Google" class="size-5">
                            Google Disabled
                        </x-ui.button>
                    @endprovider
                </div>

                <x-ui.text.muted class="mt-8 text-center">
                    By continuing, you agree to our
                    <a href="{{ route('terms') }}"
                        class="text-primary hover:text-primary-hover underline underline-offset-4">Terms of Service</a>
                    and
                    <a href="{{ route('privacy') }}"
                        class="text-primary hover:text-primary-hover underline underline-offset-4">Privacy Policy</a>
                </x-ui.text.muted>
            </x-ui.card>
        </div>
    </main>
@endsection
