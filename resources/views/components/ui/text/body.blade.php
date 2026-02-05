@props(['class' => ''])
<p {{ $attributes->class(['text-md leading-relaxed text-foreground', $class]) }}>
    {{ $slot }}
</p>
