<?php

declare(strict_types=1);

use App\Models\Event;
use App\Models\User;
use App\Queries\GetMemberProfileQuery;

test('it loads speaking events ordered by date descending', function () {
    $member = User::factory()->create();
    $event1 = Event::factory()->create([
        'starts_at' => now()->subMonths(2),
        'ends_at' => now()->subMonths(2)->addHours(4),
    ]);
    $event2 = Event::factory()->create([
        'starts_at' => now()->subWeek(),
        'ends_at' => now()->subWeek()->addHours(4),
    ]);

    $member->speakingEvents()->attach([$event1->id, $event2->id], [
        'title' => 'Talk',
        'description' => 'Description',
        'status' => 'approved',
    ]);

    $result = app(GetMemberProfileQuery::class)->execute($member);

    expect($result->relationLoaded('speakingEvents'))->toBeTrue()
        ->and($result->speakingEvents->first()->id)->toBe($event2->id)
        ->and($result->speakingEvents->last()->id)->toBe($event1->id);
});

test('it loads rsvps ordered by date descending', function () {
    $member = User::factory()->create();
    $event1 = Event::factory()->create([
        'starts_at' => now()->subMonths(3),
        'ends_at' => now()->subMonths(3)->addHours(4),
    ]);
    $event2 = Event::factory()->create([
        'starts_at' => now()->subDays(10),
        'ends_at' => now()->subDays(10)->addHours(4),
    ]);

    $member->rsvps()->attach([$event1->id, $event2->id], ['status' => 'confirmed']);

    $result = app(GetMemberProfileQuery::class)->execute($member);

    expect($result->relationLoaded('rsvps'))->toBeTrue()
        ->and($result->rsvps->first()->id)->toBe($event2->id)
        ->and($result->rsvps->last()->id)->toBe($event1->id);
});

test('it returns the same user instance with relations loaded', function () {
    $member = User::factory()->create();

    $result = app(GetMemberProfileQuery::class)->execute($member);

    expect($result->is($member))->toBeTrue();
});
