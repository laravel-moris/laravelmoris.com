@props(['class' => ''])
<h6 {{ $attributes->class(['text-base font-bold uppercase tracking-wider text-muted', $class]) }}>
    {{ $slot }}
</h6>
