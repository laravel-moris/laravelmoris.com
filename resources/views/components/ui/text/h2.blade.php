@props(['class' => ''])
<h2 {{ $attributes->class(['text-display font-bold tracking-tight text-foreground', $class]) }}>
    {{ $slot }}
</h2>
