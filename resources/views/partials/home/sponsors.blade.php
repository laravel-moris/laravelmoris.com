<x-section id="sponsors">
    <x-section-header title="Our Past" accent="Sponsors"
        subtitle="Venue hosts and partners that made our meetups possible" />

    <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach ($sponsors as $sponsor)
            <x-sponsor-card :sponsor="$sponsor" />
        @endforeach
    </div>
</x-section>
