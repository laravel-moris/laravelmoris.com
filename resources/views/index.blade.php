<x-layout.guest title="Laravel Moris">
    <x-site.ticker />
    <x-site.header />
    <x-site.hero />

    @if ($happeningNow)
        <x-ui.section class="pt-0 pb-10 md:pb-12">
            <x-site.happening-now
                :event="$happeningNow"
            />
        </x-ui.section>
    @endif

    <x-ui.section id="meetups" class="pt-0">
        <x-ui.section-header title="Upcoming" accent="Meetups" />

        <div class="grid gap-7 grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($meetups as $meetup)
                <x-cards.meetup :card="$meetup" />
            @endforeach
        </div>
    </x-ui.section>

    <x-ui.section id="speakers">
        <x-ui.section-header title="Featured" accent="Speakers" />
        <div class="grid gap-7 grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($speakers as $speaker)
                <x-cards.speaker :speaker="$speaker" />
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
            @foreach ($communityLinks as $link)
                <x-cards.community-link :link="$link" />
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
                <x-cards.sponsor :sponsor="$sponsor" />
            @endforeach
        </div>
    </x-ui.section>

    <x-site.footer />
</x-layout.guest>
