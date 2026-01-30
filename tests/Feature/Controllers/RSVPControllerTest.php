<?php

declare(strict_types=1);

use App\Models\Event;
use App\Models\User;
use Tests\TestCase;

test('it redirects guest to login', function () {
    /** @var TestCase $this */
    $event = Event::factory()->create([
        'starts_at' => now()->addWeek(),
        'ends_at' => now()->addWeek()->addHours(4),
    ]);

    $response = $this->post(route('events.rsvp', $event), ['status' => 'going']);

    $response->assertRedirect(route('login'));
});

test('it redirects with error for past events', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $event = Event::factory()->create([
        'starts_at' => now()->subWeek(),
        'ends_at' => now()->subWeek()->addHours(4),
    ]);

    $response = $this->actingAs($user)->post(route('events.rsvp', $event), ['status' => 'going']);

    $response->assertRedirect(route('events.show', $event))
        ->assertSessionHas('error', 'You cannot change your RSVP for past events.');
});

test('it RSVPs user as going', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $event = Event::factory()->create([
        'starts_at' => now()->addWeek(),
        'ends_at' => now()->addWeek()->addHours(4),
    ]);

    $response = $this->actingAs($user)->post(route('events.rsvp', $event), ['status' => 'going']);

    $response->assertRedirect(route('events.show', $event));
    expect($user->rsvps()->where('event_id', $event->id)->exists())->toBeTrue()
        ->and($user->rsvps()->where('event_id', $event->id)->first()->pivot->status)->toBe('going');
});

test('it RSVPs user as maybe', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $event = Event::factory()->create([
        'starts_at' => now()->addWeek(),
        'ends_at' => now()->addWeek()->addHours(4),
    ]);

    $response = $this->actingAs($user)->post(route('events.rsvp', $event), ['status' => 'maybe']);

    $response->assertRedirect(route('events.show', $event));
    expect($user->rsvps()->where('event_id', $event->id)->first()->pivot->status)->toBe('maybe');
});

test('it removes RSVP when not going', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $event = Event::factory()->create([
        'starts_at' => now()->addWeek(),
        'ends_at' => now()->addWeek()->addHours(4),
    ]);

    $user->rsvps()->attach($event, ['status' => 'going']);

    $response = $this->actingAs($user)->post(route('events.rsvp', $event), ['status' => 'not_going']);

    $response->assertRedirect(route('events.show', $event));
    expect($user->rsvps()->where('event_id', $event->id)->exists())->toBeFalse();
});

test('it defaults to going when no status provided', function () {
    /** @var TestCase $this */
    $user = User::factory()->create();
    $event = Event::factory()->create([
        'starts_at' => now()->addWeek(),
        'ends_at' => now()->addWeek()->addHours(4),
    ]);

    $response = $this->actingAs($user)->post(route('events.rsvp', $event));

    $response->assertRedirect(route('events.show', $event));
    expect($user->rsvps()->where('event_id', $event->id)->first()->pivot->status)->toBe('going');
});
