@extends('layouts.guest')

@section('title', 'Privacy Policy | Laravel Moris')

@section('body')
    <x-site.header :links="[]" />

    <main class="px-6 py-12">
        <div class="max-w-3xl mx-auto">
            <x-ui.card class="p-8">
                <x-ui.text.h1>Privacy Policy</x-ui.text.h1>
                <x-ui.text.muted class="mt-4">
                    Last updated: {{ now()->format('F d, Y') }}
                </x-ui.text.muted>

                <div class="mt-8 space-y-6">
                    <section>
                        <x-ui.text.h3>1. Information We Collect</x-ui.text.h3>
                        <x-ui.text.body class="mt-2">
                            We collect information you provide directly to us when you create an account, including your name, email address, and GitHub profile information when you authenticate via GitHub OAuth.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h3>2. How We Use Your Information</x-ui.text.h3>
                        <x-ui.text.body class="mt-2">
                            We use the information we collect to provide, maintain, and improve our services, to communicate with you about events and updates, and to personalize your experience within the Laravel Moris community.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h3>3. Information Sharing</x-ui.text.h3>
                        <x-ui.text.body class="mt-2">
                            We do not sell, trade, or otherwise transfer your personal information to third parties. We may share information with trusted partners who assist us in operating our website and conducting our meetups.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h3>4. Data Security</x-ui.text.h3>
                        <x-ui.text.body class="mt-2">
                            We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h3>5. Your Rights</x-ui.text.h3>
                        <x-ui.text.body class="mt-2">
                            You have the right to access, update, or delete your personal information. You can manage your account settings or contact us to exercise these rights.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h3>6. Cookies and Tracking</x-ui.text.h3>
                        <x-ui.text.body class="mt-2">
                            We use cookies and similar technologies to enhance your experience, analyze usage patterns, and improve our services. You can control cookie settings through your browser preferences.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h3>7. Third-Party Services</x-ui.text.h3>
                        <x-ui.text.body class="mt-2">
                            Our services may contain links to third-party websites or services. We are not responsible for the privacy practices of these third parties.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h3>8. Changes to This Policy</x-ui.text.h3>
                        <x-ui.text.body class="mt-2">
                            We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the "Last updated" date.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h3>9. Contact Us</x-ui.text.h3>
                        <x-ui.text.body class="mt-2">
                            If you have any questions about this Privacy Policy, please contact us through our community channels or at Laravel Moris meetup events.
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
