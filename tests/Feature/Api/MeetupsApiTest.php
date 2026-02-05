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

test('it includes rsvp link for upcoming events', function () {
    $now = now('UTC')->toImmutable();
    Date::setTestNow($now);

    $event = Event::factory()->create([
        'title' => 'Upcoming Meetup',
        'starts_at' => $now->addDays(1),
        'ends_at' => $now->addDays(1)->addHours(2),
        'type' => EventLocation::Online,
    ]);

    $response = $this->getJson("/api/meetups/{$event->id}");

    $response
        ->assertOk()
        ->assertJsonPath('data.rsvp_link', route('events.rsvp', $event));

    Date::setTestNow();
});

test('it returns null rsvp link for past events', function () {
    $now = now('UTC')->toImmutable();
    Date::setTestNow($now);

    $event = Event::factory()->create([
        'title' => 'Past Meetup',
        'starts_at' => $now->subDays(2),
        'ends_at' => $now->subDays(2)->addHours(2),
        'type' => EventLocation::Online,
    ]);

    $response = $this->getJson("/api/meetups/{$event->id}");

    $response
        ->assertOk()
        ->assertJsonPath('data.rsvp_link', null);

    Date::setTestNow();
});

test('it includes rsvp links in the meetups list', function () {
    $now = now('UTC')->toImmutable();
    Date::setTestNow($now);

    // Query orders by oldest start date first, so past event comes before upcoming
    $past = Event::factory()->create([
        'title' => 'Past',
        'starts_at' => $now->subDays(2),
        'ends_at' => $now->subDays(2)->addHours(2),
        'type' => EventLocation::Online,
    ]);

    $upcoming = Event::factory()->create([
        'title' => 'Upcoming',
        'starts_at' => $now->addDays(1),
        'ends_at' => $now->addDays(1)->addHours(2),
        'type' => EventLocation::Online,
    ]);

    $response = $this->getJson('/api/meetups');

    $response
        ->assertOk()
        ->assertJsonCount(2, 'data')
        // Past event comes first (oldest starts_at)
        ->assertJsonPath('data.0.rsvp_link', null)
        // Upcoming event comes second
        ->assertJsonPath('data.1.rsvp_link', route('events.rsvp', $upcoming));

    Date::setTestNow();
});
