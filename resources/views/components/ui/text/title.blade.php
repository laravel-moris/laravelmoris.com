@props(['class' => ''])
<h1 {{ $attributes->class(['text-display-lg font-bold tracking-tighter text-foreground animate-fade-in-up', $class]) }}>
    {{ $slot }}
</h1>
