<?php

declare(strict_types=1);

use App\Data\CommunityLinkData;

test('it determines whether a community link is available', function (string $url, bool $available) {
    $data = new CommunityLinkData(
        iconKey: 'discord',
        name: 'Discord',
        description: 'Join us',
        url: $url,
    );

    expect($data->isAvailable())->toBe($available);
})->with([
    ['#', false],
    ['', false],
    ['https://example.test', true],
]);
