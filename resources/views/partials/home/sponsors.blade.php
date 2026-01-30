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
