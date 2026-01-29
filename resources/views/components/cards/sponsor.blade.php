@props([
    'logo' => null,
    'label' => null,
])

<div class="group lm-card lm-reveal lm-card-accent-top rounded-[20px] px-8 py-12 text-center group-hover:-translate-y-1.5 group-hover:border-orange before:bg-orange" data-reveal>
    <div class="text-[18px] font-extrabold uppercase tracking-[0.08em] text-orange">{{ $logo }}</div>
    <div class="mt-3 text-[13px] uppercase tracking-[0.08em] text-muted">{{ $label }}</div>
</div>
