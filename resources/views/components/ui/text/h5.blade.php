@props(['class' => ''])
<h5 {{ $attributes->class(['text-[18px] font-bold text-foreground', $class]) }}>
    {{ $slot }}
</h5>
