@props(['class' => ''])
<p {{ $attributes->class(['text-md font-medium uppercase tracking-caps text-muted', $class]) }}>
    {{ $slot }}
</p>
