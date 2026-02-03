<?php

declare(strict_types=1);

use App\Data\EventData;
use App\Data\EventLocationData;
use App\Data\SpeakerAvatarData;
use App\Enums\EventLocation;
use App\Models\OnlineLocation;
use Carbon\CarbonImmutable;

test('it builds event data with immutable datetimes', function () {
    $location = new EventLocationData(
        class: OnlineLocation::class,
        attributes: [
            'platform' => 'zoom',
            'url' => 'https://example.test/zoom',
        ],
    );

    $event = EventData::make(
        title: 'Meetup',
        date: '2026-02-03',
        startsAt: '18:30',
        endsAt: '20:30',
        timezone: 'Indian/Mauritius',
        description: 'A meetup',
        type: EventLocation::Online,
        location: $location,
    );

    expect($event->startsAt)->toBeInstanceOf(CarbonImmutable::class)
        ->and($event->endsAt)->toBeInstanceOf(CarbonImmutable::class)
        ->and($event->startsAt->format('Y-m-d H:i'))->toBe('2026-02-03 18:30')
        ->and($event->endsAt->format('Y-m-d H:i'))->toBe('2026-02-03 20:30')
        ->and($event->startsAt->getTimezone()->getName())->toBe('Indian/Mauritius')
        ->and($event->type)->toBe(EventLocation::Online)
        ->and($event->location->class)->toBe(OnlineLocation::class)
        ->and($event->location->attributes['platform'])->toBe('zoom');
});

test('it stores speaker avatar data', function () {
    $speaker = new SpeakerAvatarData(
        name: 'Jane Doe',
        avatarUrl: 'https://example.test/avatar.png',
    );

    expect($speaker->name)->toBe('Jane Doe')
        ->and($speaker->avatarUrl)->toBe('https://example.test/avatar.png');
});
