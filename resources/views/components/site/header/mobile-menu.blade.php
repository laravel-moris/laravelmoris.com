@props([
    'links' => [],
])

@php
    $defaultLinks = [
        ['label' => 'Meetups', 'href' => '#meetups'],
        ['label' => 'Speakers', 'href' => '#speakers'],
        ['label' => 'Sponsors', 'href' => '#sponsors'],
        ['label' => 'Join Community', 'href' => '#community'],
    ];
    $links = count($links) ? $links : $defaultLinks;
@endphp

<div id="mobile-menu" class="group md:hidden hidden" data-mobile-menu data-open="false">
    <div class="fixed inset-0 z-[110]" aria-modal="true" role="dialog">
        <button
            type="button"
            class="absolute inset-0 bg-background/30 backdrop-blur-sm opacity-0 transition-opacity duration-300 group-data-[open=true]:opacity-100"
            data-mobile-menu-close
            aria-label="Close menu"
        ></button>

        <div
            class="absolute right-0 top-0 h-full w-[min(360px,92vw)] bg-surface border-l border-border/70 shadow-2xl translate-x-full transition-transform duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] will-change-transform group-data-[open=true]:translate-x-0"
            data-mobile-menu-panel
        >
            <div class="flex items-center justify-between px-6 py-5 border-b border-border/70">
                <span class="text-[13px] font-semibold uppercase tracking-[0.12em] text-muted">Menu</span>
                <x-ui.icon-button
                    class="size-10"
                    data-mobile-menu-close
                    aria-label="Close menu"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </x-ui.icon-button>
            </div>

            <div class="px-6 py-6">
                <div class="flex flex-col gap-2">
                    @foreach ($links as $link)
                        <a
                            href="{{ $link['href'] }}"
                            class="rounded-xl px-4 py-3 text-[14px] font-semibold tracking-[-0.01em] text-foreground border border-transparent hover:border-border/70 hover:bg-surface-2 transition"
                            data-mobile-menu-close
                        >
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    <x-ui.button href="#" variant="primary" size="md" class="w-full rounded-xl">Log In</x-ui.button>
                </div>
            </div>
        </div>
    </div>
</div>
