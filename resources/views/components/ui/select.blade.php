@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'help' => null,
    'error' => null,
    'options' => null,
    'selected' => null,
    'placeholder' => null,
])

@php
    $id = $id ?? $name;
    $error = $error ?? ($name ? $errors->first($name) : null);

    $helpId = $id ? $id.'-help' : null;
    $errorId = $id ? $id.'-error' : null;

    $describedBy = collect([$help ? $helpId : null, $error ? $errorId : null])
        ->filter()
        ->implode(' ');

    $current = $name ? old($name, $selected) : $selected;

    $selectClasses = array_filter([
        'w-full rounded-2xl bg-surface-2 border border-border/70 px-4 py-3 pr-10 text-[15px] text-foreground',
        'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/60 focus-visible:border-primary/60',
        'disabled:cursor-not-allowed disabled:opacity-50',
        $error ? 'border-coral/50 focus-visible:ring-coral/50 focus-visible:border-coral/60' : null,
    ]);
@endphp

<div class="grid gap-2">
    @if ($label)
        <label @if ($id) for="{{ $id }}" @endif class="text-[12px] font-bold uppercase tracking-[0.14em] text-muted">
            {{ $label }}
        </label>
    @endif

    <select
        @if ($id) id="{{ $id }}" @endif
        @if ($name) name="{{ $name }}" @endif
        @if ($error) aria-invalid="true" @endif
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        {{ $attributes->class($selectClasses) }}
    >
        @if ($placeholder)
            <option value="" @selected($current === null || $current === '')>{{ $placeholder }}</option>
        @endif

        @if (is_array($options))
            @foreach ($options as $value => $optionLabel)
                <option value="{{ $value }}" @selected((string) $current === (string) $value)>{{ $optionLabel }}</option>
            @endforeach
        @elseif (trim($slot) !== '')
            {{ $slot }}
        @endif
    </select>

    @if ($help)
        <p @if ($helpId) id="{{ $helpId }}" @endif class="text-[13px] text-muted">
            {{ $help }}
        </p>
    @endif

    @if ($error)
        <p @if ($errorId) id="{{ $errorId }}" @endif class="text-[13px] text-coral font-medium">
            {{ $error }}
        </p>
    @endif
</div>
