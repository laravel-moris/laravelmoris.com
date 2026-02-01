@props(['link'])

<a href="{{ $link->url }}"
    class="group lm-card lm-reveal lm-card-accent-top rounded-3xl px-8 py-12 text-center no-underline text-foreground group-hover:-translate-y-2 group-hover:border-green before:bg-green"
    data-reveal>
    <div
        class="mx-auto mb-6 grid size-[90px] place-items-center rounded-full bg-surface-2 border border-border/70 transition duration-300 group-hover:border-green group-hover:scale-[1.05] group-hover:rotate-[-3deg]">
        <img src="{{ Vite::asset('resources/images/logos/' . $link->iconKey . '.svg') }}" alt="{{ $link->name }}" class="size-12">
    </div>

    <x-ui.text.h3 class="text-[20px] font-bold tracking-[-0.01em]">{{ $link->name }}</x-ui.text.h3>
    <x-ui.text.muted class="mt-3 leading-relaxed">{{ $link->description }}</x-ui.text.muted>
</a>
