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
                    <a
                        href="{{ route('auth.provider', 'github') }}"
                        class="flex items-center justify-center gap-3 w-full h-12 px-4 bg-[#24292e] hover:bg-[#2f363d] text-white font-medium rounded-lg transition-colors duration-200"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.17 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.463-1.11-1.463-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.268 2.75 1.026A9.578 9.578 0 0112 6.836c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.026 2.747-1.026.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.167 22 16.418 22 12c0-5.523-4.477-10-10-10z" clip-rule="evenodd" />
                        </svg>
                        Continue with GitHub
                    </a>

                    <a
                        href="{{ route('auth.provider', 'google') }}"
                        class="flex items-center justify-center gap-3 w-full h-12 px-4 bg-white hover:bg-gray-50 text-gray-900 font-medium rounded-lg border border-gray-200 transition-colors duration-200"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z" fill="#4285F4"/>
                        </svg>
                        Continue with Google
                    </a>
                </div>

                <x-ui.text.muted class="mt-8 text-center">
                    By continuing, you agree to our
                    <a href="{{ route('terms') }}" class="text-primary hover:text-primary-hover underline underline-offset-4">Terms of Service</a>
                    and
                    <a href="{{ route('privacy') }}" class="text-primary hover:text-primary-hover underline underline-offset-4">Privacy Policy</a>
                </x-ui.text.muted>
            </x-ui.card>
        </div>
    </main>
@endsection
