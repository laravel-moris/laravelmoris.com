@extends('layouts.guest')

@section('title', 'Cookie Policy | Laravel Moris')

@section('body')
    <x-site.header :links="[]" />

    <main class="px-6 py-12">
        <div class="max-w-3xl mx-auto">
            <x-ui.card class="p-8">
                <x-ui.text.h1>Cookie Policy</x-ui.text.h1>
                <x-ui.text.muted class="mt-4">
                    Last updated: {{ now()->format('F d, Y') }}
                </x-ui.text.muted>

                <div class="mt-8 space-y-8">
                    <section>
                        <x-ui.text.h2>What Are Cookies</x-ui.text.h2>
                        <x-ui.text.body class="mt-2">
                            Cookies are small text files that are stored on your device (computer, tablet, or mobile phone)
                            when you visit a website. They are widely used to make websites work more efficiently and
                            provide information to the owners of the site. Cookies help us recognize your device, remember
                            your preferences, and improve your experience on our website.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h2>How We Use Cookies</x-ui.text.h2>
                        <x-ui.text.body class="mt-2">
                            Laravel Moris uses cookies for several purposes to enhance your experience on our website:
                        </x-ui.text.body>
                        <ul class="list-disc ml-6 space-y-2 mt-2">
                            <li>
                                <strong>Session Management:</strong> We use cookies to keep you logged in as you navigate
                                through different pages of our website during your session. This allows you to maintain
                                your authentication state without needing to log in on every page.
                            </li>
                            <li>
                                <strong>Authentication:</strong> Cookies help us verify your identity when you log in using
                                Google OAuth. This ensures that only authenticated users can access protected features
                                like RSVPing for meetups and managing their profiles.
                            </li>
                            <li>
                                <strong>Analytics:</strong> We use cookies to collect anonymous information about how
                                visitors use our website. This helps us understand which pages are most popular, identify
                                technical issues, and improve the overall user experience for the Laravel Moris community.
                            </li>
                        </ul>
                    </section>

                    <section>
                        <x-ui.text.h2>Types of Cookies We Use</x-ui.text.h2>

                        <div class="space-y-6 mt-2">
                            <div>
                                <x-ui.text.body class="font-semibold">
                                    1. Essential Cookies
                                </x-ui.text.body>
                                <x-ui.text.body class="mt-1">
                                    These cookies are necessary for the website to function properly. They enable core
                                    functionality such as security, network management, and accessibility. You cannot
                                    switch these off as they are essential for the website to work correctly.
                                </x-ui.text.body>
                            </div>

                            <div>
                                <x-ui.text.body class="font-semibold">
                                    2. Functional Cookies
                                </x-ui.text.body>
                                <x-ui.text.body class="mt-1">
                                    These cookies allow us to remember choices you make (such as your language
                                    preference or region) and provide enhanced, more personal features. They may also
                                    be used to remember your preferences for meetup RSVPs and profile settings.
                                </x-ui.text.body>
                            </div>

                            <div>
                                <x-ui.text.body class="font-semibold">
                                    3. Analytics Cookies
                                </x-ui.text.body>
                                <x-ui.text.body class="mt-1">
                                    We use Google Analytics to understand how visitors engage with our website. These
                                    cookies collect information such as which pages you visit, how long you stay on
                                    each page, and how you found our website. This data is anonymous and helps us
                                    improve our website for the community.
                                </x-ui.text.body>
                            </div>
                        </div>
                    </section>

                    <section>
                        <x-ui.text.h2>Managing and Deleting Cookies</x-ui.text.h2>
                        <x-ui.text.body class="mt-2">
                            You have the right to accept, refuse, or delete cookies at any time. Here are the options
                            available to you:
                        </x-ui.text.body>
                        <ul class="list-disc ml-6 space-y-2 mt-2">
                            <li>
                                <strong>Browser Settings:</strong> Most web browsers allow you to control cookies through
                                their settings. You can typically choose to block all cookies, accept all cookies, or
                                receive a notification when a cookie is set. Instructions for managing cookies can be
                                found in your browser's help documentation.
                            </li>
                            <li>
                                <strong>Opt-out of Google Analytics:</strong> You can install the
                                <a href="https://tools.google.com/dlpage/gaoptout"
                                   target="_blank"
                                   class="text-primary hover:text-primary-hover underline underline-offset-4">
                                    Google Analytics Opt-out Browser Add-on
                                </a>
                                to prevent your data from being collected by Google Analytics.
                            </li>
                            <li>
                                <strong>Delete Existing Cookies:</strong> You can delete all cookies currently stored
                                on your device through your browser's settings. This will log you out of any websites
                                you were using and reset your preferences.
                            </li>
                        </ul>
                        <x-ui.text.body class="mt-4">
                            Please note that blocking or deleting cookies may affect your experience on our website and
                            may prevent you from using certain features, such as staying logged in or RSVPing for meetups.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h2>Cookies We Use</x-ui.text.h2>
                        <x-ui.text.body class="mt-2">
                            Below is a summary of the main cookies used by Laravel Moris:
                        </x-ui.text.body>
                        <div class="mt-4 overflow-x-auto">
                            <table class="w-full text-sm border-collapse">
                                <thead>
                                    <tr class="border-b border-border/70">
                                        <th class="text-left py-2 px-3 font-semibold">Cookie Name</th>
                                        <th class="text-left py-2 px-3 font-semibold">Purpose</th>
                                        <th class="text-left py-2 px-3 font-semibold">Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-border/50">
                                        <td class="py-2 px-3">laravel_session</td>
                                        <td class="py-2 px-3">Session authentication</td>
                                        <td class="py-2 px-3">2 hours</td>
                                    </tr>
                                    <tr class="border-b border-border/50">
                                        <td class="py-2 px-3">XSRF-TOKEN</td>
                                        <td class="py-2 px-3">Security (Cross-Site Request Forgery protection)</td>
                                        <td class="py-2 px-3">2 hours</td>
                                    </tr>
                                    <tr class="border-b border-border/50">
                                        <td class="py-2 px-3">_ga</td>
                                        <td class="py-2 px-3">Google Analytics (Distinguishes unique users)</td>
                                        <td class="py-2 px-3">2 years</td>
                                    </tr>
                                    <tr class="border-b border-border/50">
                                        <td class="py-2 px-3">_gid</td>
                                        <td class="py-2 px-3">Google Analytics (Distinguishes users within 24 hours)</td>
                                        <td class="py-2 px-3">24 hours</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <section>
                        <x-ui.text.h2>Contact Us</x-ui.text.h2>
                        <x-ui.text.body class="mt-2">
                            If you have any questions about our Cookie Policy or how we use cookies, please contact us at
                            <a href="mailto:contact@laravelmoris.com"
                               class="text-primary hover:text-primary-hover underline underline-offset-4">
                                contact@laravelmoris.com
                            </a>.
                        </x-ui.text.body>
                    </section>
                </div>

                <div class="mt-10 pt-6 border-t border-border/70">
                    <a href="{{ route('login') }}"
                        class="text-primary hover:text-primary-hover underline underline-offset-4">
                        ← Back to Login
                    </a>
                </div>
            </x-ui.card>
        </div>
    </main>
@endsection
