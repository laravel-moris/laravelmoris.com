@props(['class' => ''])
<p {{ $attributes->class(['text-[16px] font-medium uppercase tracking-[0.2em] text-muted', $class]) }}>
    {{ $slot }}
</p>
