@extends('layouts.guest')

@section('title', 'Terms of Service | Laravel Moris')

@section('body')
    <x-site.header :links="[]" />

    <main class="px-6 py-12">
        <div class="max-w-3xl mx-auto">
            <x-ui.card class="p-8">
                <x-ui.text.h1>Terms of Service</x-ui.text.h1>
                <x-ui.text.muted class="mt-4">
                    Last updated: {{ now()->format('F d, Y') }}
                </x-ui.text.muted>

                <div class="mt-8 space-y-6">
                    <section>
                        <x-ui.text.h3>1. Introduction</x-ui.text.h3>
                        <x-ui.text.body class="mt-2">
                            Welcome to Laravel Moris. By accessing or using our services, you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use our services.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h3>2. Use of Our Services</x-ui.text.h3>
                        <x-ui.text.body class="mt-2">
                            You agree to use our services only for lawful purposes and in accordance with these Terms. You are responsible for all activity that occurs under your account.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h3>3. User Accounts</x-ui.text.h3>
                        <x-ui.text.body class="mt-2">
                            When you create an account with us, you must provide accurate and complete information. You are responsible for safeguarding your account credentials and for any activities or actions under your account.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h3>4. Community Guidelines</x-ui.text.h3>
                        <x-ui.text.body class="mt-2">
                            As a community-focused platform, we expect all users to treat each other with respect. Harassment, discrimination, spam, or any form of abusive behavior will not be tolerated and may result in account termination.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h3>5. Intellectual Property</x-ui.text.h3>
                        <x-ui.text.body class="mt-2">
                            The content, features, and functionality of our services are owned by Laravel Moris and are protected by international copyright, trademark, and other intellectual property laws.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h3>6. Termination</x-ui.text.h3>
                        <x-ui.text.body class="mt-2">
                            We may terminate or suspend your account immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the Terms.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h3>7. Changes to Terms</x-ui.text.h3>
                        <x-ui.text.body class="mt-2">
                            We reserve the right to modify or replace these Terms at any time. It is your responsibility to review these Terms periodically for changes.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h3>8. Contact Us</x-ui.text.h3>
                        <x-ui.text.body class="mt-2">
                            If you have any questions about these Terms, please contact us through our community channels or at the Laravel Moris meetup events.
                        </x-ui.text.body>
                    </section>
                </div>

                <div class="mt-10 pt-6 border-t border-border/70">
                    <a href="{{ route('login') }}" class="text-primary hover:text-primary-hover underline underline-offset-4">
                        ← Back to Login
                    </a>
                </div>
            </x-ui.card>
        </div>
    </main>
@endsection
