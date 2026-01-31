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

                <div class="mt-8 space-y-8">
                    <section>
                        <x-ui.text.body>
                            Welcome to Laravel Moris, a community for developers in Mauritius who connect through regular
                            physical meetups. Please carefully review these Terms of Service ("Terms") before using our
                            website or attending any meetups.
                        </x-ui.text.body>
                        <x-ui.text.body class="mt-2">
                            By accessing or using our website or attending any meetups, you agree to be bound by these
                            Terms. If you disagree with any part of these Terms, you may not access or use our website or
                            attend any meetups.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h2>1. Eligibility</x-ui.text.h2>
                        <x-ui.text.body class="mt-2">
                            Any person who is interested in tech and software development is free to attend our meetups
                            after going through the RSVP process.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h2>2. User Accounts</x-ui.text.h2>
                        <ul class="list-disc ml-6 space-y-2 mt-2">
                            <li>Creating an account is required to RSVP for meetups.</li>
                            <li>By creating an account, you agree to provide accurate and complete information and to keep
                                your account information up-to-date.</li>
                            <li>You are responsible for maintaining the confidentiality of your password and account and for
                                any activity that occurs under your account.</li>
                        </ul>
                    </section>

                    <section>
                        <x-ui.text.h2>3. Content and Conduct</x-ui.text.h2>
                        <ul class="list-disc ml-6 space-y-2 mt-2">
                            <li>You may not use our website or attend meetups for any illegal or unauthorized purpose.</li>
                            <li>You may not post or share any content that is hateful, discriminatory, obscene, defamatory,
                                or infringes on any third-party rights.</li>
                            <li>You may not spam or solicit other users.</li>
                            <li>You may not attempt to harm or interfere with our website or systems.</li>
                        </ul>
                    </section>

                    <section>
                        <x-ui.text.h2>4. RSVP and Meetups</x-ui.text.h2>
                        <ul class="list-disc ml-6 space-y-2 mt-2">
                            <li>You must RSVP for each meetup you want to attend.</li>
                            <li>We reserve the right to deny or revoke RSVPs at our discretion.</li>
                            <li>Attendance at meetups is subject to space availability.</li>
                            <li>You are responsible for your own transportation and other expenses related to attending
                                meetups.</li>
                        </ul>
                    </section>

                    <section>
                        <x-ui.text.h2>5. Intellectual Property</x-ui.text.h2>
                        <ul class="list-disc ml-6 space-y-2 mt-2">
                            <li>The source code of this website is open source and we encourage you to contribute and
                                improve it.</li>
                            <li>You may not use the personal information of people featured on our website for commercial
                                purposes.</li>
                        </ul>
                    </section>

                    <section>
                        <x-ui.text.h2>6. Disclaimer</x-ui.text.h2>
                        <x-ui.text.body class="mt-2">
                            Our website and meetups are provided "as is" and without warranty of any kind. We disclaim any
                            liability for any damages arising out of your use of our website or attendance at meetups.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h2>7. Indemnity</x-ui.text.h2>
                        <x-ui.text.body class="mt-2">
                            You agree to indemnify and hold us harmless from any claims, losses, or damages arising out of
                            your use of our website or attendance at meetups.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h2>8. Governing Law</x-ui.text.h2>
                        <x-ui.text.body class="mt-2">
                            These Terms shall be governed by and construed in accordance with the laws of Mauritius.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h2>9. Entire Agreement</x-ui.text.h2>
                        <x-ui.text.body class="mt-2">
                            These Terms constitute the entire agreement between you and us regarding your use of our website
                            and attendance at meetups.
                        </x-ui.text.body>
                    </section>

                    <section>
                        <x-ui.text.h2>10. Amendment</x-ui.text.h2>
                        <x-ui.text.body class="mt-2">
                            We may amend these Terms at any time by posting the amended Terms on our website. Your continued
                            use of our website or attendance at meetups following the posting of amended Terms constitutes
                            your acceptance of the amended Terms.
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
