<?php

declare(strict_types=1);

use App\Enums\EventLocation;
use App\Enums\PaperStatus;
use App\Models\Event;
use App\Models\OnlineLocation;
use App\Models\Paper;
use App\Models\User;
use App\Queries\GetHappeningNowQuery;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;

test('it returns happening now event data (cached)', function () {
    Cache::flush();

    $now = now('UTC')->toImmutable();
    Date::setTestNow($now);

    $location = OnlineLocation::factory()->create([
        'platform' => 'meetup',
        'url' => 'https://example.test/live',
    ]);

    $event = Event::factory()->create([
        'title' => 'Live Event',
        'starts_at' => $now->subMinutes(10),
        'ends_at' => $now->addMinutes(10),
        'type' => EventLocation::Online,
    ]);
    $event->location()->associate($location)->save();

    $users = User::factory()->count(2)->create();
    foreach ($users as $user) {
        Paper::factory()->create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => PaperStatus::Approved,
        ]);
    }

    $query = app(GetHappeningNowQuery::class);
    $first = $query->execute();
    $second = $query->execute();

    expect($first)
        ->not->toBeNull()
        ->and($first->id)->toBe($event->id)
        ->and($first->speakersCount)->toBe(2)
        ->and($second->id)->toBe($event->id);

    Date::setTestNow();
});
