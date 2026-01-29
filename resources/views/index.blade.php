@php
    $meetups = [
        [
            'status' => 'upcoming',
            'featured' => true,
            'month' => 'November',
            'day' => '29',
            'title' => 'Laravel Moris November 2025 Meetup',
            'speakers' => ['BB', 'PM', 'SR'],
        ],
        [
            'status' => 'past',
            'featured' => false,
            'month' => 'August',
            'day' => '23',
            'title' => 'Laravel Moris August 2025 Meetup',
            'speakers' => ['RD', 'BB'],
        ],
        [
            'status' => 'past',
            'featured' => false,
            'month' => 'May',
            'day' => '31',
            'title' => 'Laravel Moris May 2025 Meetup',
            'speakers' => ['PM', 'RD'],
        ],
        [
            'status' => 'past',
            'featured' => false,
            'month' => 'March',
            'day' => '29',
            'title' => 'Laravel Moris March 2025 Meetup',
            'speakers' => ['BB', 'SM'],
        ],
        [
            'status' => 'past',
            'featured' => false,
            'month' => 'February',
            'day' => '15',
            'title' => 'Laravel Moris February 2025 Meetup',
            'speakers' => ['RD', 'BB'],
        ],
    ];

    $speakers = [
        [
            'initials' => 'PM',
            'name' => 'Percy Mamedy',
            'role' => 'Co-Founder',
            'bio' => 'Full-stack developer specializing in Laravel ecosystem and modern UI systems.',
            'links' => [
                ['label' => 'GH', 'href' => '#'],
                ['label' => 'TW', 'href' => '#'],
                ['label' => 'LI', 'href' => '#'],
            ],
        ],
        [
            'initials' => 'RD',
            'name' => 'Ravish Dussaruth',
            'role' => 'Co-Founder',
            'bio' => 'Laravel expert with focus on API development, testing, and best practices.',
            'links' => [
                ['label' => 'GH', 'href' => '#'],
                ['label' => 'TW', 'href' => '#'],
                ['label' => 'LI', 'href' => '#'],
            ],
        ],
        [
            'initials' => 'BB',
            'name' => 'Bruno Bernard',
            'role' => 'Full Stack Developer',
            'bio' => 'Passionate about building scalable applications and teaching Laravel.',
            'links' => [
                ['label' => 'GH', 'href' => '#'],
                ['label' => 'TW', 'href' => '#'],
                ['label' => 'LI', 'href' => '#'],
            ],
        ],
        [
            'initials' => 'SM',
            'name' => 'Sanjivee Muthoora',
            'role' => 'Backend Specialist',
            'bio' => 'Expert in database optimization, API design, and server architecture.',
            'links' => [
                ['label' => 'GH', 'href' => '#'],
                ['label' => 'TW', 'href' => '#'],
                ['label' => 'LI', 'href' => '#'],
            ],
        ],
        [
            'initials' => 'YL',
            'name' => 'Yann Labour',
            'role' => 'Tech Lead',
            'bio' => 'Leading teams and building enterprise applications with Laravel.',
            'links' => [
                ['label' => 'GH', 'href' => '#'],
                ['label' => 'TW', 'href' => '#'],
                ['label' => 'LI', 'href' => '#'],
            ],
        ],
        [
            'initials' => 'SR',
            'name' => 'Sandeep Ramgolam',
            'role' => 'GDE Web & Frontend Lead',
            'bio' => 'Google Developer Expert (Web) & Senior Frontend Engineer. Lead Organizer of frontend.coders.mu',
            'links' => [
                ['label' => 'GH', 'href' => '#'],
                ['label' => 'TW', 'href' => '#'],
                ['label' => 'LI', 'href' => '#'],
            ],
        ],
    ];

    $communities = [
        ['icon' => 'DC', 'name' => 'Discord', 'description' => 'Real-time chat & support', 'href' => '#'],
        ['icon' => 'GH', 'name' => 'GitHub', 'description' => 'Open source contributions', 'href' => '#'],
        ['icon' => 'WA', 'name' => 'WhatsApp', 'description' => 'Quick discussions', 'href' => '#'],
        ['icon' => 'TW', 'name' => 'Twitter', 'description' => 'Latest updates & news', 'href' => '#'],
        ['icon' => 'IG', 'name' => 'Instagram', 'description' => 'Behind the scenes', 'href' => '#'],
        ['icon' => 'LI', 'name' => 'LinkedIn', 'description' => 'Professional network', 'href' => '#'],
    ];

    $sponsors = [
        ['logo' => 'ESOKIA', 'label' => 'Venue Host - August 2025'],
        ['logo' => 'ALCHE', 'label' => 'Venue Host - May 2025'],
        ['logo' => 'WORKSHOP17', 'label' => 'Venue Host - March 2025'],
        ['logo' => 'RINGIER', 'label' => 'Venue Host - February 2025'],
        ['logo' => 'BLACK PIRATES', 'label' => 'Venue & Pizza - November 2025'],
        ['logo' => 'LARAVEL', 'label' => 'Official Partner'],
        ['logo' => 'CERTIFICATION', 'label' => 'Laravel Program Partner'],
    ];
@endphp

<x-layout.guest title="Laravel Moris">
    <x-site.ticker />
    <x-site.header />
    <x-site.hero />

    <x-ui.section id="meetups">
        <x-ui.section-header title="Upcoming" accent="Meetups" />
        <div class="grid gap-7 grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($meetups as $meetup)
                <x-cards.meetup
                    :status="$meetup['status']"
                    :featured="$meetup['featured']"
                    :month="$meetup['month']"
                    :day="$meetup['day']"
                    :title="$meetup['title']"
                    :speakers="$meetup['speakers']"
                />
            @endforeach
        </div>
    </x-ui.section>

    <x-ui.section id="speakers">
        <x-ui.section-header title="Featured" accent="Speakers" />
        <div class="grid gap-7 grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($speakers as $speaker)
                <x-cards.speaker
                    :initials="$speaker['initials']"
                    :name="$speaker['name']"
                    :role="$speaker['role']"
                    :bio="$speaker['bio']"
                    :links="$speaker['links']"
                />
            @endforeach
        </div>
    </x-ui.section>

    <x-ui.section id="community">
        <x-ui.section-header
            title="Join Our"
            accent="Community"
            subtitle="Connect with fellow developers and stay updated"
        />

        <div class="grid gap-7 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($communities as $community)
                <x-cards.community-link
                    :href="$community['href']"
                    :icon="$community['icon']"
                    :name="$community['name']"
                    :description="$community['description']"
                />
            @endforeach
        </div>
    </x-ui.section>

    <x-ui.section id="sponsors">
        <x-ui.section-header
            title="Our Past"
            accent="Sponsors"
            subtitle="Venue hosts and partners that made our meetups possible"
        />

        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($sponsors as $sponsor)
                <x-cards.sponsor :logo="$sponsor['logo']" :label="$sponsor['label']" />
            @endforeach
        </div>
    </x-ui.section>

    <x-site.footer />
</x-layout.guest>
