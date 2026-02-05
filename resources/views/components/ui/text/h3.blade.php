@props(['class' => ''])
<h3 {{ $attributes->class(['text-3xl font-bold tracking-tight text-foreground', $class]) }}>
    {{ $slot }}
</h3>
