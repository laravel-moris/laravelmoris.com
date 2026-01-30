<x-ui.section id="meetups" class="pt-0">
    <x-ui.section-header title="Upcoming" accent="Meetups" />

    <div class="grid gap-7 grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($meetups as $meetup)
            <x-cards.meetup :card="$meetup" />
        @endforeach
    </div>
</x-ui.section>
