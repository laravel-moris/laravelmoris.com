<x-section id="speakers">
    <x-section-header title="Featured" accent="Speakers" />

    <div class="grid gap-7 grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($speakers as $speaker)
            <x-speaker-card :speaker="$speaker" />
        @endforeach
    </div>
</x-section>
