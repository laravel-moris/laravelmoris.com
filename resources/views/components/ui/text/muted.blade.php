@props(['class' => ''])
<p {{ $attributes->class(['text-[13px] text-muted', $class]) }}>
    {{ $slot }}
</p>
