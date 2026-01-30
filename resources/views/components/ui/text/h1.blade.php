@props(['class' => ''])
<h1 {{ $attributes->class(['text-[clamp(32px,5vw,54px)] font-bold tracking-[-0.03em] text-foreground', $class]) }}>
    {{ $slot }}
</h1>
