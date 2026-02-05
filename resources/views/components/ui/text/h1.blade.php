@props(['class' => ''])
<h1 {{ $attributes->class(['text-display-lg font-bold tracking-tighter text-foreground', $class]) }}>
    {{ $slot }}
</h1>
