@extends('layouts.guest')

@section('title', 'Privacy Policy | Laravel Moris')

@section('body')
    <x-site.header :links="[]" />

    <main class="px-6 py-12">
        <div class="max-w-3xl mx-auto">
            <x-card class="p-8">
                <x-heading level="1">Privacy Policy</x-heading>
                <x-text variant="muted" class="mt-4">
                    Last updated: {{ now()->format('F d, Y') }}
                </x-text>

                <div class="mt-8 space-y-8">
                    <section>
                        <x-text size="md">
                            Welcome to Laravel Moris, a community for developers in Mauritius who connect through regular
                            physical meetups. We value your privacy and are committed to protecting your personal
                            information. This Privacy Policy explains what information we collect, how we use it, and who we
                            share it with.
                        </x-text>
                    </section>

                    <section>
                        <x-heading level="2">Information We Collect</x-heading>
                        <ul class="list-disc ml-6 space-y-2 mt-2">
                            <li>
                                <strong>Personal Information:</strong> During RSVP and login, we collect your email address,
                                Google ID (to prevent spam), meal preference, and transport preference. If you choose to
                                provide it, we also collect your phone number.
                            </li>
                            <li>
                                <strong>Website Usage Data:</strong> We may collect anonymous data about the website usage,
                                such as pages visited and time spent on each page via Google Analytics. This data helps us
                                improve the website experience for everyone.
                            </li>
                        </ul>
                    </section>

                    <section>
                        <x-heading level="2">How We Use Your Information</x-heading>
                        <ul class="list-disc ml-6 space-y-2 mt-2">
                            <li>
                                <strong>Meetup Organization:</strong> We use your email address and phone number to send you
                                RSVP confirmation and updates about the meetup. We use your preferences to ensure a smooth
                                meetup experience, such as arranging catering and transportation.
                            </li>
                        </ul>
                    </section>

                    <section>
                        <x-heading level="2">Information We Share</x-heading>
                        <x-text size="md" class="mt-2">
                            We never share your personal information with sponsors, recruiters, or anyone outside the
                            Laravel Moris organizing team.
                        </x-text>
                        <x-text size="md" class="mt-2">
                            We may share anonymous website usage data with third-party analytics providers to help us
                            understand how the website is used. This does not include any personal information.
                        </x-text>
                    </section>

                    <section>
                        <x-heading level="2">Your Choices</x-heading>
                        <ul class="list-disc ml-6 space-y-2 mt-2">
                            <li>You can control your email preferences for meetup updates at any time by logging into your
                                account or contacting us.</li>
                            <li>You can delete your account at any time by contacting us.</li>
                        </ul>
                    </section>

                    <section>
                        <x-heading level="2">Data Security</x-heading>
                        <x-text size="md" class="mt-2">
                            We take reasonable security measures to protect your personal information from unauthorized
                            access, disclosure, alteration, or destruction. However, please be aware that no website is 100%
                            secure.
                        </x-text>
                    </section>

                    <section>
                        <x-heading level="2">Changes to this Policy</x-heading>
                        <x-text size="md" class="mt-2">
                            We may update this Privacy Policy from time to time. We will post any changes on this page, so
                            please review it periodically.
                        </x-text>
                    </section>

                    <section>
                        <x-heading level="2">Contact Us</x-heading>
                        <x-text size="md" class="mt-2">
                            If you have any questions about this Privacy Policy, please contact us at <a
                                href="mailto:contact@laravelmoris.com"
                                class="text-primary hover:text-primary-hover underline underline-offset-4">contact@laravelmoris.com</a>.
                        </x-text>
                    </section>
                </div>

                <div class="mt-10 pt-6 border-t border-border/70">
                    <a href="{{ route('login') }}"
                        class="text-primary hover:text-primary-hover underline underline-offset-4">
                        ← Back to Login
                    </a>
                </div>
            </x-card>
        </div>
    </main>
@endsection
