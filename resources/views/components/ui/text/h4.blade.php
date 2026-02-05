@props(['class' => ''])
<h4 {{ $attributes->class(['text-2xl font-bold tracking-snug text-foreground', $class]) }}>
    {{ $slot }}
</h4>
