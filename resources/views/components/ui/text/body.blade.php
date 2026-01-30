@props(['class' => ''])
<p {{ $attributes->class(['text-[16px] leading-relaxed text-foreground', $class]) }}>
    {{ $slot }}
</p>
