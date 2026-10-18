<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

it('renders a labelled accessible modal with focus management', function () {
    $html = Blade::render(<<<'BLADE'
        <div x-data="{ showModal: false }">
            <x-modal id="confirmation-modal">
                <x-slot:header>Confirm attendance</x-slot:header>
                Are you sure?
            </x-modal>
        </div>
    BLADE);

    expect($html)
        ->toContain('role="dialog"')
        ->toContain('aria-modal="true"')
        ->toContain('aria-labelledby="confirmation-modal-title"')
        ->toContain('x-data="accessibleDialog"')
        ->toContain('size-11')
        ->not->toContain('x-transition:leave');
});

it('gives a modal without a header a valid accessible name', function () {
    $html = Blade::render('<x-modal id="notice-modal" label="Important notice">Message</x-modal>');

    expect($html)
        ->toContain('aria-label="Important notice"')
        ->not->toContain('aria-labelledby="notice-modal-title"');
});

it('renders the mobile menu as a labelled managed dialog', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('aria-labelledby="mobile-menu-title"', false)
        ->assertSee('id="mobile-menu-title"', false)
        ->assertSee('data-mobile-menu-panel', false);

    expect(file_get_contents(resource_path('views/components/site/header.blade.php')))
        ->toContain('<x-icon-button class="size-11" data-mobile-menu-close');
});

it('preserves the original mobile menu opener when a closing animation is interrupted', function () {
    $script = file_get_contents(resource_path('js/app.js'));

    expect($script)->toMatch(
        '/if \(!inertedElements\.length\) \{\s+previouslyFocused = document\.activeElement;\s+inertedElements = makeBackgroundInert\(menu\);\s+\}/',
    );
});
