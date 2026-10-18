<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('keeps hero content visible when reduced motion is requested', function () {
    $html = Blade::render('<x-site.hero />');

    expect($html)
        ->toContain('motion-safe:animate-hero-title')
        ->toContain('motion-safe:animate-hero-subtitle')
        ->toContain('motion-safe:animate-hero-cta')
        ->toContain('motion-safe:opacity-0')
        ->not->toMatch('/(?<!:)opacity-0/');
});

it('stops the ticker when reduced motion is requested', function () {
    $html = Blade::render('<x-site.ticker />');

    expect($html)->toContain('motion-reduce:animate-none');
});
