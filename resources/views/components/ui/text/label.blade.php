@props(['class' => ''])

<p {{ $attributes->class(['text-[12px] font-bold uppercase tracking-[0.14em] text-muted', $class]) }}>
    {{ $slot }}
</p>
