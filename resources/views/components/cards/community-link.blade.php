@props(['link'])

<a href="{{ $link->url }}"
    class="group relative overflow-hidden rounded-3xl bg-surface border border-border/70 transition-all duration-400 lm-reveal lm-card-accent-top px-8 py-12 text-center no-underline text-foreground group-hover:-translate-y-2 group-hover:border-green before:bg-green"
    data-reveal>
    <div
        class="mx-auto mb-6 grid size-avatar-md place-items-center rounded-full bg-surface-2 border border-border/70 transition duration-300 group-hover:border-green group-hover:scale-105 group-hover:rotate-[-3deg]">
        <img src="{{ Vite::asset('resources/images/logos/' . $link->iconKey . '.svg') }}" alt="{{ $link->name }}"
            class="size-12">
    </div>

    <x-ui.text.h3 class="text-xl font-bold tracking-snug">{{ $link->name }}</x-ui.text.h3>
    <x-ui.text.muted class="mt-3 leading-relaxed">{{ $link->description }}</x-ui.text.muted>
</a>
