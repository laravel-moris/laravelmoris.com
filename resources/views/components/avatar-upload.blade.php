@props([
    'name' => 'avatar',
    'id' => null,
    'label' => null,
    'currentAvatar' => null,
    'maxFileSize' => '2MB',
    'help' => null,
    'error' => null,
])

@php
    $id = $id ?? $name;
    $error = $error ?? ($name ? $errors->first($name) : null);

    $helpId = $id ? "{$id}-help" : null;
    $errorId = $id ? "{$id}-error" : null;
    $describedBy = collect([$help ? $helpId : null, $error ? $errorId : null])
        ->filter()
        ->implode(' ');
@endphp

<div class="grid gap-2" x-data="avatarUpload({ maxFileSize: '{{ $maxFileSize }}' })">
    @if ($label)
        <x-label :for="$id">{{ $label }}</x-label>
    @endif

    <div class="flex items-start gap-4">
        {{-- Avatar Preview with Upload Overlay --}}
        <div class="relative group">
            {{-- Current/Preview Avatar --}}
            <div class="size-24 rounded-full overflow-hidden bg-surface-2 border-2 border-border/70">
                <img x-ref="preview"
                    src="{{ $currentAvatar ?? 'https://ui-avatars.com/api/?name=Avatar&background=1c1a17&color=a39e96&size=96' }}"
                    alt="Avatar preview" class="size-full object-cover">
            </div>

            {{-- Upload Overlay --}}
            <label for="{{ $id }}"
                class="absolute inset-0 flex items-center justify-center rounded-full bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                    class="size-8 text-white">
                    <path fill-rule="evenodd"
                        d="M11.47 2.47a.75.75 0 0 1 1.06 0l4.5 4.5a.75.75 0 0 1-1.06 1.06l-3.22-3.22V16.5a.75.75 0 0 1-1.5 0V4.81L8.03 8.03a.75.75 0 0 1-1.06-1.06l4.5-4.5ZM3 15.75a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z"
                        clip-rule="evenodd" />
                </svg>
            </label>

            {{-- Hidden File Input --}}
            <input type="file" x-ref="input" name="{{ $name }}" id="{{ $id }}" accept="image/*"
                class="sr-only" @change="handleFileSelect($event)"
                @if ($error) aria-invalid="true" @endif
                @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif>
        </div>

        {{-- Info & Actions --}}
        <div class="flex flex-col justify-center gap-2 py-2">
            <x-button type="button" variant="secondary" size="sm" @click="$refs.input.click()">
                Choose Image
            </x-button>

            <template x-if="fileName">
                <div class="flex items-center gap-2">
                    <x-text variant="muted" class="text-xs truncate max-w-32" x-text="fileName"></x-text>
                    <button type="button" @click="clearFile()"
                        class="text-coral hover:text-coral/80 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                            <path fill-rule="evenodd"
                                d="M4.47 4.47a.75.75 0 0 1 1.06 0L10 8.94l4.47-4.47a.75.75 0 1 1 1.06 1.06L11.06 10l4.47 4.47a.75.75 0 0 1-1.06 1.06L10 11.06l-4.47 4.47a.75.75 0 0 1-1.06-1.06L8.94 10 4.47 5.53a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>
    </div>

    @if ($help)
        <x-text variant="muted" id="{{ $helpId }}">{{ $help }}</x-text>
    @endif

    @if ($error)
        <p id="{{ $errorId }}" class="text-sm text-coral font-medium">{{ $error }}</p>
    @endif
</div>
