@props(['class' => ''])
<h6 {{ $attributes->class(['text-[15px] font-bold uppercase tracking-[0.12em] text-muted', $class]) }}>
    {{ $slot }}
</h6>
