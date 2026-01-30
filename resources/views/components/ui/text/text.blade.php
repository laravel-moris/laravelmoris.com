@props(['class' => ''])
<p {{ $attributes->class(['text-foreground', $class]) }}>
    {{ $slot }}
</p>
