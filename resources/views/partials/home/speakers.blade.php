<x-ui.section id="speakers">
    <x-ui.section-header title="Featured" accent="Speakers" />

    <div class="grid gap-7 grid-cols-1 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($speakers as $speaker)
            <x-cards.speaker :speaker="$speaker" />
        @endforeach
    </div>
</x-ui.section>
