@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'accept' => null,
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

    $inputClasses = [
        'block w-full text-sm text-muted',
        'file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0',
        'file:text-sm file:font-semibold file:bg-primary file:text-white',
        'hover:file:bg-primary-hover file:cursor-pointer',
        'disabled:cursor-not-allowed disabled:opacity-50',
    ];
@endphp

<div class="grid gap-2">
    @if ($label)
        <x-label :for="$id">{{ $label }}</x-label>
    @endif

    <input type="file" @if ($id) id="{{ $id }}" @endif
        @if ($name) name="{{ $name }}" @endif
        @if ($accept) accept="{{ $accept }}" @endif
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
