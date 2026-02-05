@props(['class' => ''])

<code {{ $attributes->class(['font-mono text-sm', $class]) }}>
    {{ $slot }}
</code>
