@props(['class' => ''])
<h5 {{ $attributes->class(['text-lg font-bold text-foreground', $class]) }}>
    {{ $slot }}
</h5>
