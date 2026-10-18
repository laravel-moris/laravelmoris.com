<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;

function contrastRatio(string $foreground, string $background): float
{
    $luminances = collect([$foreground, $background])
        ->map(function (string $color): float {
            $channels = collect(str_split(mb_substr($color, 1), 2))
                ->map(fn (string $channel): float => hexdec($channel) / 255)
                ->map(fn (float $channel): float => $channel <= 0.04045
                    ? $channel / 12.92
                    : (($channel + 0.055) / 1.055) ** 2.4)
                ->values();

            return 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
        });

    return ($luminances->max() + 0.05) / ($luminances->min() + 0.05);
}

test('semantic colors expose accessible foreground tokens for both themes', function () {
    $css = File::get(resource_path('css/app.css'));

    expect($css)
        ->toContain('--primary-foreground: #0a0908;')
        ->toContain('--accent: #c52219;')
        ->toContain('--accent-hover: #a91d16;')
        ->toContain('--sunset-coral: #b4232b;')
        ->toContain('--primary-foreground: #ffffff;')
        ->toContain('--color-primary-foreground: var(--primary-foreground);')
        ->toContain('--color-bright-foreground: #0a0908;');
});

test('semantic text and solid control colors meet WCAG AA', function (string $foreground, string $background) {
    expect(contrastRatio($foreground, $background))->toBeGreaterThanOrEqual(4.5);
})->with([
    'dark primary text' => ['#ff2d20', '#0a0908'],
    'dark primary control' => ['#0a0908', '#ff2d20'],
    'dark coral control' => ['#0a0908', '#ff6b6b'],
    'light primary text' => ['#c52219', '#faf9f7'],
    'light primary control' => ['#ffffff', '#c52219'],
    'light coral control' => ['#ffffff', '#b4232b'],
    'dark muted control' => ['#0a0908', '#a39e96'],
    'light muted control' => ['#ffffff', '#6b6560'],
    'green control' => ['#0a0908', '#55efc4'],
    'gold control' => ['#0a0908', '#fdcb6e'],
    'teal control' => ['#0a0908', '#48dbfb'],
    'cyan control' => ['#0a0908', '#00d2d3'],
]);

test('semantic color controls use their accessible foreground token', function () {
    $button = Blade::render('<x-button variant="primary">Join</x-button>');
    $dangerButton = Blade::render('<x-button variant="danger">Delete</x-button>');
    $badge = Blade::render('<x-badge color="primary" variant="solid">New</x-badge>');

    expect($button)
        ->toContain('text-primary-foreground')
        ->and($dangerButton)->toContain('text-primary-foreground')
        ->and($badge)->toContain('text-primary-foreground')
        ->and(File::get(resource_path('views/components/badge.blade.php')))
        ->not->toContain('text-white')
        ->and(File::get(resource_path('views/components/meetup-card.blade.php')))
        ->not->toContain('group-hover:text-white')
        ->not->toContain("\$featured ? 'text-white");
});

test('compact dismiss and removal controls retain accessible touch targets', function () {
    $alert = Blade::render('<x-alert dismissible>Message</x-alert>');
    $avatarUpload = Blade::render('<x-avatar-upload error="" />');

    expect($alert)
        ->toContain('aria-label="Dismiss alert"')
        ->toContain('size-11')
        ->and($avatarUpload)
        ->toContain('aria-label="Remove selected image"')
        ->toContain('size-11');
});
