@extends('layouts.guest')

@section('title', 'About Us | Laravel Moris')

@section('body')
    <x-site.header />

    {{-- Hero Section --}}
    <section class="relative overflow-hidden py-16 md:py-24">
        {{-- Decorative Background --}}
        <div class="absolute inset-0 bg-gradient-to-b from-surface-2/50 to-transparent"></div>

        {{-- Decorative Gradient Orbs --}}
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
        </div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-coral/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2">
        </div>

        <div class="relative z-10 px-6 md:px-12 max-w-4xl mx-auto text-center">
            <x-ui.text.h1 class="mb-6 opacity-0 animate-[lm-hero-title_1.2s_cubic-bezier(0.16,1,0.3,1)_forwards]">
                About <span class="text-primary">Laravel Moris</span>
            </x-ui.text.h1>
            <x-ui.text.body
                class="mt-6 max-w-2xl mx-auto text-lg text-muted opacity-0 animate-[lm-hero-subtitle_1s_cubic-bezier(0.16,1,0.3,1)_forwards] [animation-delay:200ms]">
                Empowering developers across Mauritius and Africa through community, education, and innovation.
            </x-ui.text.body>
        </div>
    </section>

    <main class="px-6 md:px-12 pb-16">
        <div class="max-w-5xl mx-auto flex flex-col gap-10">
            {{-- Meet the Team Section --}}
            <x-ui.card class="p-8 relative overflow-hidden group">
                {{-- Decorative Gradient Background --}}
                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-transparent to-coral/5 opacity-50"></div>

                <div class="relative">
                    <div class="flex flex-col items-center text-center mb-12">
                        <x-ui.text.h2 class="mb-4">Meet the Team</x-ui.text.h2>
                        <x-ui.text.body class="text-lg max-w-2xl text-muted leading-relaxed">
                            Following and supporting us means you'll collaborate with skilled professionals, be inspired to
                            master Laravel, and explore new and innovative approaches.
                        </x-ui.text.body>
                    </div>

                    {{-- Team Members --}}
                    @if ($teamMembers->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($teamMembers as $member)
                                <x-ui.card
                                    class="p-6 group/member hover:-translate-y-1 transition-all duration-300 border-border/40 hover:border-primary/30">
                                    <a href="{{ route('members.show', $member) }}"
                                        class="flex flex-col items-center text-center">
                                        <div class="relative inline-block mb-4">
                                            <img src="{{ $member->avatar }}" alt="{{ $member->name }}"
                                                class="size-12 md:w-28 md:h-28 rounded-full mx-auto object-cover aspect-square shrink-0 ring-4 ring-surface-2 group-hover/member:ring-primary/50 transition-all duration-300">
                                            <div
                                                class="absolute inset-0 rounded-full bg-gradient-to-tr from-primary/20 to-coral/20 opacity-0 group-hover/member:opacity-100 transition-opacity duration-300">
                                            </div>
                                        </div>
                                        <x-ui.text.body class="font-bold group-hover/member:text-primary transition-colors">
                                            {{ $member->name }}
                                        </x-ui.text.body>
                                        @if ($member->title)
                                            <x-ui.text.muted
                                                class="text-xs uppercase tracking-widest mt-1">{{ $member->title }}</x-ui.text.muted>
                                        @endif
                                    </a>
                                </x-ui.card>
                            @endforeach
                        </div>
                    @else
                        <div
                            class="text-center py-12 px-4 rounded-2xl bg-surface-2/30 border border-dashed border-border/60">
                            <x-ui.text.muted class="text-lg">Team members coming soon...</x-ui.text.muted>
                        </div>
                    @endif
                </div>
            </x-ui.card>

            {{-- Overview Section --}}
            <x-ui.card class="p-8 relative overflow-hidden group">
                <div class="relative">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1.5 h-8 bg-primary rounded-full"></div>
                        <x-ui.text.h2 class="text-3xl">Overview</x-ui.text.h2>
                    </div>

                    <x-ui.text.body class="mb-8 text-lg leading-relaxed text-muted">
                        Since its inception, Laravel Moris has been dedicated to advancing the use of Laravel across the
                        Mauritius and Africa. With a focus on delivering top-notch web solutions and sharing expert
                        knowledge, Laravel Moris aims to become synonymous with innovation, community-driven development,
                        and empowering developers to create exceptional applications.
                    </x-ui.text.body>

                    {{-- Chips with Icons --}}
                    <div class="flex flex-wrap gap-4">
                        <x-ui.chip color="primary" variant="soft" size="md">
                            Monthly Events
                        </x-ui.chip>
                        <x-ui.chip color="green" variant="soft" size="md">
                            Vibrant Community
                        </x-ui.chip>
                        <x-ui.chip color="gold" variant="soft" size="md">
                            Open Source
                        </x-ui.chip>
                    </div>
                </div>
            </x-ui.card>

            {{-- Background Section --}}
            <x-ui.card class="p-8 relative overflow-hidden group">
                <div class="relative">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1.5 h-8 bg-purple rounded-full"></div>
                        <x-ui.text.h2 class="text-3xl">Our Background</x-ui.text.h2>
                    </div>

                    <x-ui.text.body class="text-lg leading-relaxed text-muted">
                        Laravel Moris was founded with a clear mission: to boost the adoption of Laravel across Mauritius
                        and Africa. We noticed that many local developers viewed Laravel as merely a tool for side projects,
                        and there was a noticeable shortage of skilled Laravel developers in the region. Laravel Moris was
                        built to unite developers passionate about Laravel and foster a community of learning and growth.
                    </x-ui.text.body>
                </div>
            </x-ui.card>

            {{-- The Challenge Section --}}
            <x-ui.card class="p-8 relative overflow-hidden group">
                <div class="relative">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1.5 h-8 bg-orange rounded-full"></div>
                        <x-ui.text.h2 class="text-3xl">The Challenge</x-ui.text.h2>
                    </div>

                    <x-ui.text.body class="text-lg leading-relaxed text-muted">
                        Laravel Moris faces the challenge of establishing a consistent and engaging presence in the
                        developer community by organizing monthly meetups, publishing regular blog posts, and actively
                        animating a growing community of Laravel developers. The tight schedule, combined with the need to
                        continually engage and educate developers across Mauritius and Africa, means that collaboration and
                        dedication from the entire community are essential.
                    </x-ui.text.body>
                </div>
            </x-ui.card>
        </div>
    </main>
@endsection
