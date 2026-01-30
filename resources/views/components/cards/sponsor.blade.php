@props([
    'sponsor',
])

<a href="{{ route('sponsors.show', $sponsor->id) }}" class="group block">
    <x-ui.card class="p-5 transition-all duration-300 hover:border-primary/50 h-full">
        <div class="flex flex-col items-center text-center">
            <div class="w-full h-32 rounded-xl bg-white/5 border border-border/70 overflow-hidden mb-3 flex items-center justify-center p-4">
                <img
                    src="{{ $sponsor->logoUrl }}"
                    alt="{{ $sponsor->name }}"
                    class="max-w-full max-h-full object-contain"
                >
            </div>

            <x-ui.text.h3 class="text-lg group-hover:text-primary transition-colors">
                {{ $sponsor->name }}
            </x-ui.text.h3>

            @if($sponsor->website)
                <x-ui.text.muted class="mt-1 text-xs text-teal">
                    {{ parse_url($sponsor->website, PHP_URL_HOST) }}
                </x-ui.text.muted>
            @endif
        </div>
    </x-ui.card>
</a>
