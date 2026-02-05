@props(['class' => ''])

<p {{ $attributes->class(['text-label uppercase text-muted', $class]) }}>
    {{ $slot }}
</p>
