<?php

declare(strict_types=1);

use App\Enums\EventLocation;
use App\Models\Event;
use App\Models\OnlineLocation;
use App\Queries\GetUpcomingMeetupsQuery;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;

test('it returns 6 meetups: upcoming first, then past fill, excluding happening now', function () {
    Cache::flush();

    $now = now('UTC')->toImmutable();
    Date::setTestNow($now);

    $liveLocation = OnlineLocation::factory()->create(['url' => 'https://example.test/live']);
    $live = Event::factory()->create([
        'title' => 'Happening Now',
        'starts_at' => $now->subMinutes(30),
        'ends_at' => $now->addMinutes(30),
        'type' => EventLocation::Online,
    ]);
    $live->location()->associate($liveLocation)->save();

    $futureLocation = OnlineLocation::factory()->create(['url' => 'https://example.test/future']);
    $pastLocation = OnlineLocation::factory()->create(['url' => 'https://example.test/past']);

    collect(range(1, 5))->map(function (int $i) use ($now, $futureLocation) {
        $event = Event::factory()->create([
            'title' => "Upcoming {$i}",
            'starts_at' => $now->addDays($i),
            'ends_at' => $now->addDays($i)->addHours(2),
            'type' => EventLocation::Online,
        ]);
        $event->location()->associate($futureLocation)->save();

        return $event;
    });

    collect(range(1, 3))->each(function (int $i) use ($now, $pastLocation) {
        $event = Event::factory()->create([
            'title' => "Past {$i}",
            'starts_at' => $now->subDays($i),
            'ends_at' => $now->subDays($i)->addHours(2),
            'type' => EventLocation::Online,
        ]);
        $event->location()->associate($pastLocation)->save();
    });

    $query = app(GetUpcomingMeetupsQuery::class);
    $meetups = $query->execute();

    $meetupsCollection = $meetups->toCollection();

    expect($meetups)
        ->toHaveCount(6)
        ->and($meetupsCollection->first()->featured)
        ->toBeTrue()
        ->and($meetupsCollection->filter(fn ($m) => $m->featured))
        ->toHaveCount(1)
        ->and($meetupsCollection->pluck('id')->all())->not->toContain($live->id);


    $nowUtc = now('Indian/Mauritius')->utc()->toImmutable();
    $isUpcoming = fn ($m) => $m->startsAt->greaterThanOrEqualTo($nowUtc);

    expect($meetupsCollection->filter($isUpcoming))
        ->toHaveCount(5)
        ->and($meetupsCollection->reject($isUpcoming))
        ->toHaveCount(1);

    // Upcoming are ordered soonest first
    $upcomingStarts = $meetupsCollection->filter($isUpcoming)->map(fn ($m) => $m->startsAt->getTimestamp())->values()->all();
    $sorted = $upcomingStarts;
    sort($sorted);
    expect($upcomingStarts)->toBe($sorted);

    Date::setTestNow();
});
