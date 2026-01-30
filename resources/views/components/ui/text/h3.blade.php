@props(['class' => ''])
<h3 {{ $attributes->class(['text-[28px] font-bold tracking-[-0.02em] text-foreground', $class]) }}>
    {{ $slot }}
</h3>
