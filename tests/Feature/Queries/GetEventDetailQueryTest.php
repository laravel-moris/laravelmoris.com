<?php

declare(strict_types=1);

use App\Models\Event;
use App\Models\Sponsor;
use App\Models\User;
use App\Queries\GetEventDetailQuery;

test('it loads event with location', function () {
    $event = Event::factory()->create();

    $result = app(GetEventDetailQuery::class)->execute($event);

    expect($result->relationLoaded('location'))->toBeTrue();
});

test('it loads speakers ordered by name', function () {
    $event = Event::factory()->create();
    $speaker1 = User::factory()->create(['name' => 'Zoe Speaker']);
    $speaker2 = User::factory()->create(['name' => 'Alice Speaker']);

    $event->speakers()->attach([$speaker1->id, $speaker2->id], [
        'title' => 'Talk',
        'description' => 'Description',
        'status' => 'approved',
    ]);

    $result = app(GetEventDetailQuery::class)->execute($event);

    expect($result->relationLoaded('speakers'))->toBeTrue()
        ->and($result->speakers->first()->name)->toBe('Alice Speaker')
        ->and($result->speakers->last()->name)->toBe('Zoe Speaker');
});

test('it does not eager load attendees', function () {
    $event = Event::factory()->create();
    $attendee1 = User::factory()->create(['name' => 'Zoe Attendee']);
    $attendee2 = User::factory()->create(['name' => 'Alice Attendee']);

    $event->attendees()->attach([$attendee1->id, $attendee2->id], ['status' => 'confirmed']);

    $result = app(GetEventDetailQuery::class)->execute($event);

    expect($result->relationLoaded('attendees'))->toBeFalse();
});

test('it loads sponsors ordered by name', function () {
    $event = Event::factory()->create();
    $sponsor1 = Sponsor::factory()->create(['name' => 'Zeta Sponsor']);
    $sponsor2 = Sponsor::factory()->create(['name' => 'Alpha Sponsor']);

    $event->sponsors()->attach([$sponsor1->id, $sponsor2->id]);

    $result = app(GetEventDetailQuery::class)->execute($event);

    expect($result->relationLoaded('sponsors'))->toBeTrue()
        ->and($result->sponsors->first()->name)->toBe('Alpha Sponsor')
        ->and($result->sponsors->last()->name)->toBe('Zeta Sponsor');
});

test('it returns the same event instance with relations loaded', function () {
    $event = Event::factory()->create();

    $result = app(GetEventDetailQuery::class)->execute($event);

    expect($result->is($event))->toBeTrue();
});
