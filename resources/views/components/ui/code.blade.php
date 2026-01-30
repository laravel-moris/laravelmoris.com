@props(['class' => ''])

<code {{ $attributes->class(['font-mono text-[13px]', $class]) }}>
    {{ $slot }}
</code>
