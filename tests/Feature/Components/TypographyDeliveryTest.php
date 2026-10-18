<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

it('loads only font weights used by the interface', function () {
    $fonts = File::get(resource_path('css/fonts.css'));

    expect($fonts)
        ->toContain('JetBrainsMono-normal-400-1.woff2')
        ->toContain('Oswald-normal-700-1.woff2')
        ->not->toContain('JetBrainsMono-normal-500-1.woff2')
        ->not->toContain('JetBrainsMono-normal-600-1.woff2')
        ->not->toContain('Oswald-normal-500-1.woff2');
});
