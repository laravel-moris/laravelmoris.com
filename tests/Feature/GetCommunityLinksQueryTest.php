<?php

declare(strict_types=1);

use App\Queries\GetCommunityLinksQuery;

test('it returns community links from sushi model', function () {
    $links = app(GetCommunityLinksQuery::class)->execute();

    $iconKeys = $links->toCollection()->pluck('iconKey')->all();

    expect($links)
        ->toHaveCount(4)
        ->and($iconKeys)
        ->toContain('discord')
        ->toContain('whatsapp')
        ->toContain('linkedin')
        ->toContain('github-light');
});
