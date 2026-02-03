<?php

declare(strict_types=1);

use App\Enums\EventLocation;
use App\Models\Event;
use App\Models\OnlineLocation;
use Illuminate\Support\Facades\Date;

test('it lists meetups ordered by start date', function () {
    $now = now('UTC')->toImmutable();
    Date::setTestNow($now);

    $location = OnlineLocation::factory()->create(['url' => 'https://example.test/meetup']);

    $first = Event::factory()->create([
        'title' => 'First',
        'description' => 'First description',
        'starts_at' => $now->addDays(1),
        'ends_at' => $now->addDays(1)->addHours(2),
        'type' => EventLocation::Online,
    ]);
    $first->location()->associate($location)->save();

    $second = Event::factory()->create([
        'title' => 'Second',
        'description' => 'Second description',
        'starts_at' => $now->addDays(2),
        'ends_at' => $now->addDays(2)->addHours(2),
        'type' => EventLocation::Online,
    ]);
    $second->location()->associate($location)->save();

    $response = $this->getJson('/api/meetups');

    $response
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.title', 'First')
        ->assertJsonPath('data.0.description', 'First description')
        ->assertJsonPath('data.0.starts_at', $first->starts_at->toJSON())
        ->assertJsonPath('data.0.ends_at', $first->ends_at->toJSON())
        ->assertJsonPath('data.1.title', 'Second')
        ->assertJsonPath('data.1.description', 'Second description')
        ->assertJsonPath('data.1.starts_at', $second->starts_at->toJSON())
        ->assertJsonPath('data.1.ends_at', $second->ends_at->toJSON());

    Date::setTestNow();
});

test('it shows a single meetup', function () {
    $now = now('UTC')->toImmutable();
    Date::setTestNow($now);

    $location = OnlineLocation::factory()->create(['url' => 'https://example.test/single']);

    $event = Event::factory()->create([
        'title' => 'Single',
        'description' => 'Single description',
        'starts_at' => $now->addDays(3),
        'ends_at' => $now->addDays(3)->addHours(2),
        'type' => EventLocation::Online,
    ]);
    $event->location()->associate($location)->save();

    $response = $this->getJson("/api/meetups/{$event->id}");

    $response
        ->assertOk()
        ->assertJsonPath('data.title', 'Single')
        ->assertJsonPath('data.description', 'Single description')
        ->assertJsonPath('data.starts_at', $event->starts_at->toJSON())
        ->assertJsonPath('data.ends_at', $event->ends_at->toJSON());

    Date::setTestNow();
});
