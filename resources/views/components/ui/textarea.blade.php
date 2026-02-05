@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'rows' => 4,
    'value' => null,
    'help' => null,
    'error' => null,
])

@php
    $id = $id ?? $name;
    $error = $error ?? ($name ? $errors->first($name) : null);

    $currentValue = $name && $value === null ? old($name, $value) : $value;

    $helpId = $id ? $id . '-help' : null;
    $errorId = $id ? $id . '-error' : null;

    $describedBy = collect([$help ? $helpId : null, $error ? $errorId : null])
        ->filter()
        ->implode(' ');

    $textareaClasses = array_filter([
        'w-full rounded-2xl bg-surface-2 border border-border/70 px-4 py-3 text-base text-foreground placeholder:text-muted/80 resize-none',
        'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/60 focus-visible:border-primary/60',
        'disabled:cursor-not-allowed disabled:opacity-50',
        $error ? 'border-coral/50 focus-visible:ring-coral/50 focus-visible:border-coral/60' : null,
    ]);
@endphp

<div class="grid gap-2">
    @if ($label)
        <label @if ($id) for="{{ $id }}" @endif
            class="text-label uppercase text-muted">
            {{ $label }}
        </label>
    @endif

    <textarea @if ($id) id="{{ $id }}" @endif
        @if ($name) name="{{ $name }}" @endif rows="{{ $rows }}"
        @if ($currentValue) >{{ $currentValue }}</textarea>
        @else {{ $attributes->class($textareaClasses) }}></textarea> @endif
        @if ($help) <p ___inline_directive__________________________________________2___ class="text-sm text-muted">
            {{ $help }}
        </p> @endif
        @if ($error) <p ___inline_directive__________________________________________3___ class="text-sm text-coral font-medium">
            {{ $error }}
        </p> @endif
        </div>
