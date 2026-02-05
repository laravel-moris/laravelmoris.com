@props(['sponsor'])

<a href="{{ route('sponsors.show', $sponsor->id) }}" class="group block">
    <x-card class="p-5 transition-all duration-300 hover:border-primary/50 h-full">
        <div class="flex flex-col items-center text-center">
            <div
                class="w-full h-32 rounded-xl bg-white overflow-hidden mb-3 flex items-center justify-center p-4">
                <img src="{{ $sponsor->logoUrl }}" alt="{{ $sponsor->name }}" class="max-w-full max-h-full object-contain">
            </div>

            <x-heading level="3" class="text-lg group-hover:text-primary transition-colors">
                {{ $sponsor->name }}
            </x-heading>

            @if ($sponsor->website)
                <x-text variant="muted" class="mt-1 text-xs text-teal">
                    {{ parse_url($sponsor->website, PHP_URL_HOST) }}
                </x-text>
            @endif
        </div>
    </x-card>
</a>
