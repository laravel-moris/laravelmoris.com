<?php

declare(strict_types=1);

use App\Models\Event;
use App\Queries\GetEventsQuery;

test('it returns upcoming events sorted by start date', function () {
    $upcomingEvent1 = Event::factory()->create([
        'title' => 'First Meetup',
        'starts_at' => now()->addDays(10),
        'ends_at' => now()->addDays(10)->addHours(4),
    ]);
    $upcomingEvent2 = Event::factory()->create([
        'title' => 'Second Meetup',
        'starts_at' => now()->addDays(5),
        'ends_at' => now()->addDays(5)->addHours(4),
    ]);

    $result = app(GetEventsQuery::class)->execute();

    expect($result['upcoming'])->toHaveCount(2)
        ->and($result['upcoming']->first()->title)->toBe('Second Meetup')
        ->and($result['upcoming']->last()->title)->toBe('First Meetup');
});

test('it returns past events sorted by start date descending', function () {
    $pastEvent1 = Event::factory()->create([
        'title' => 'Oldest Meetup',
        'starts_at' => now()->subMonths(2),
        'ends_at' => now()->subMonths(2)->addHours(4),
    ]);
    $pastEvent2 = Event::factory()->create([
        'title' => 'Recent Meetup',
        'starts_at' => now()->subDays(5),
        'ends_at' => now()->subDays(5)->addHours(4),
    ]);

    $result = app(GetEventsQuery::class)->execute();

    expect($result['past'])->toHaveCount(2)
        ->and($result['past']->first()->title)->toBe('Recent Meetup')
        ->and($result['past']->last()->title)->toBe('Oldest Meetup');
});

test('it includes speaker and attendee counts', function () {
    $event = Event::factory()->create([
        'title' => 'Count Test Event',
        'starts_at' => now()->addWeek(),
        'ends_at' => now()->addWeek()->addHours(4),
    ]);

    $result = app(GetEventsQuery::class)->execute();

    $returnedEvent = $result['upcoming']->where('title', 'Count Test Event')->first();
    expect($returnedEvent->speakers_count)->toBe(0)
        ->and($returnedEvent->attendees_count)->toBe(0);
});

test('it separates upcoming from past events', function () {
    Event::factory()->create([
        'starts_at' => now()->addDay(),
        'ends_at' => now()->addDay()->addHours(4),
    ]);
    Event::factory()->create([
        'starts_at' => now()->subDay(),
        'ends_at' => now()->subDay()->addHours(4),
    ]);

    $result = app(GetEventsQuery::class)->execute();

    expect($result['upcoming'])->toHaveCount(1)
        ->and($result['past'])->toHaveCount(1);
});

test('it returns empty collections when no events exist', function () {
    Event::query()->delete();

    $result = app(GetEventsQuery::class)->execute();

    expect($result['upcoming'])->toBeEmpty()
        ->and($result['past'])->toBeEmpty();
});
