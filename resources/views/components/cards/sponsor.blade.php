@props([
    'sponsor',
])

<div class="group lm-card lm-reveal lm-card-accent-top rounded-[20px] px-8 py-10 text-center group-hover:-translate-y-1.5 group-hover:border-orange before:bg-orange" data-reveal>
    <div class="mx-auto grid size-[88px] place-items-center rounded-2xl bg-surface-2 border border-border/70 overflow-hidden">
        <img src="{{ $sponsor->logoUrl }}" alt="{{ $sponsor->name }}" class="h-full w-full object-cover" loading="lazy">
    </div>
    <div class="mt-5 text-[14px] font-bold tracking-[-0.01em]">{{ $sponsor->name }}</div>
</div>
