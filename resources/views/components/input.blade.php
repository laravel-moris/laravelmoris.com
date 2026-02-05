@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'type' => 'text',
    'value' => null,
    'help' => null,
    'error' => null,
])

@php
    $id = $id ?? $name;
    $error = $error ?? ($name ? $errors->first($name) : null);

    $preventsAutoValue = in_array($type, ['password', 'file'], true);
    $shouldSetValue = !$preventsAutoValue || $value !== null;
    $currentValue = $shouldSetValue ? (!$preventsAutoValue && $name ? old($name, $value) : $value) : null;

    $helpId = $id ? "{$id}-help" : null;
    $errorId = $id ? "{$id}-error" : null;
    $describedBy = collect([$help ? $helpId : null, $error ? $errorId : null])
        ->filter()
        ->implode(' ');

    $inputClasses = [
        'w-full rounded-2xl bg-surface-2 border border-border/70 px-4 py-3 text-base text-foreground placeholder:text-muted/80',
        'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/60 focus-visible:border-primary/60',
        'disabled:cursor-not-allowed disabled:opacity-50',
        $error ? 'border-coral/50 focus-visible:ring-coral/50 focus-visible:border-coral/60' : '',
    ];
@endphp

<div class="grid gap-2">
    @if ($label)
        <x-label :for="$id">{{ $label }}</x-label>
    @endif

    <input @if ($id) id="{{ $id }}" @endif
        @if ($name) name="{{ $name }}" @endif type="{{ $type }}"
        @if ($shouldSetValue) value="{{ $currentValue }}" @endif
        @if ($error) aria-invalid="true" @endif
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        {{ $attributes->class($inputClasses) }}>

    @if ($help)
        <x-text variant="muted" id="{{ $helpId }}">{{ $help }}</x-text>
    @endif

    @if ($error)
        <p id="{{ $errorId }}" class="text-sm text-coral font-medium">{{ $error }}</p>
    @endif
</div>
