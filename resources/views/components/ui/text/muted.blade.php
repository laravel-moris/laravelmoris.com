@props(['class' => ''])
<p {{ $attributes->class(['text-sm text-muted', $class]) }}>
    {{ $slot }}
</p>
