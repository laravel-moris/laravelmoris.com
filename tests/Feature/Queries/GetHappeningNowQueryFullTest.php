<?php

declare(strict_types=1);

use App\Enums\EventLocation;
use App\Models\Event;
use App\Models\OnlineLocation;
use App\Models\PhysicalLocation;
use App\Queries\GetHappeningNowQuery;
use Illuminate\Support\Facades\Cache;

test('it returns null when no event is happening', function () {
    Cache::flush();

    $result = app(GetHappeningNowQuery::class)->execute();

    expect($result)->toBeNull();
});

test('it caches null result when no event found', function () {
    Cache::flush();

    app(GetHappeningNowQuery::class)->execute();

    expect(Cache::get('homepage:happening-now'))->toBe('__none__');
});

test('it returns cached HappeningNowData', function () {
    Cache::flush();

    $event = Event::factory()->create([
        'starts_at' => now()->subHour(),
        'ends_at' => now()->addHour(),
    ]);

    $firstResult = app(GetHappeningNowQuery::class)->execute();
    expect($firstResult->id)->toBe($event->id);

    // Second call should use cache
    $secondResult = app(GetHappeningNowQuery::class)->execute();
    expect($secondResult->id)->toBe($event->id);
});

test('it generates online location url', function () {
    Cache::flush();

    $location = OnlineLocation::factory()->create([
        'platform' => 'zoom',
        'url' => 'https://zoom.us/j/123',
    ]);

    $event = Event::factory()->create([
        'starts_at' => now()->subHour(),
        'ends_at' => now()->addHour(),
        'type' => EventLocation::Online,
    ]);
    $event->location()->associate($location)->save();

    $result = app(GetHappeningNowQuery::class)->execute();

    expect($result->ctaUrl)->toBe('https://zoom.us/j/123');
});

test('it generates physical location url', function () {
    Cache::flush();

    $location = PhysicalLocation::factory()->create([
        'directions_url' => 'https://maps.google.com/?q=location',
    ]);

    $event = Event::factory()->create([
        'starts_at' => now()->subHour(),
        'ends_at' => now()->addHour(),
        'type' => EventLocation::Physical,
    ]);
    $event->location()->associate($location)->save();

    $result = app(GetHappeningNowQuery::class)->execute();

    expect($result->ctaUrl)->toBe('https://maps.google.com/?q=location');
});

test('it returns null for physical event without directions url', function () {
    Cache::flush();

    $location = PhysicalLocation::factory()->create([
        'directions_url' => null,
    ]);

    $event = Event::factory()->create([
        'starts_at' => now()->subHour(),
        'ends_at' => now()->addHour(),
        'type' => EventLocation::Physical,
    ]);
    $event->location()->associate($location)->save();

    $result = app(GetHappeningNowQuery::class)->execute();

    expect($result->ctaUrl)->toBeNull();
});
