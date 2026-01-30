@props(['class' => ''])
<h4 {{ $attributes->class(['text-[22px] font-bold tracking-[-0.01em] text-foreground', $class]) }}>
    {{ $slot }}
</h4>
