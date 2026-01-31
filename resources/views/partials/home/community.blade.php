<x-ui.section id="community">
    <x-ui.section-header title="Join Our" accent="Community" subtitle="Connect with fellow developers and stay updated" />

    <div class="grid gap-7 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($communityLinks as $link)
            <x-cards.community-link :link="$link" />
        @endforeach
    </div>
</x-ui.section>
