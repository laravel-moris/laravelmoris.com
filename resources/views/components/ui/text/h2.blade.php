@props(['class' => ''])
<h2 {{ $attributes->class(['text-[clamp(28px,4vw,44px)] font-bold tracking-[-0.02em] text-foreground', $class]) }}>
    {{ $slot }}
</h2>
