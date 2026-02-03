<?php

declare(strict_types=1);

use App\Actions\Event\RSVPAction;
use App\Enums\RsvpStatus;
use App\Models\Event;
use App\Models\User;

test('it RSVPs a user as going', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create();

    app(RSVPAction::class)->execute($user, $event, RsvpStatus::Going);

    expect($user->rsvps()->where('event_id', $event->id)->exists())->toBeTrue()
        ->and($user->rsvps()->where('event_id', $event->id)->first()->rsvp->status)->toBe('going');
});

test('it RSVPs a user as maybe', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create();

    app(RSVPAction::class)->execute($user, $event, RsvpStatus::Maybe);

    expect($user->rsvps()->where('event_id', $event->id)->exists())->toBeTrue()
        ->and($user->rsvps()->where('event_id', $event->id)->first()->rsvp->status)->toBe('maybe');
});

test('it removes RSVP when not going', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create();

    $user->rsvps()->attach($event, ['status' => 'going']);

    app(RSVPAction::class)->execute($user, $event, RsvpStatus::NotGoing);

    expect($user->rsvps()->where('event_id', $event->id)->exists())->toBeFalse();
});

test('it defaults to going when no status provided', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create();

    app(RSVPAction::class)->execute($user, $event);

    expect($user->rsvps()->where('event_id', $event->id)->exists())->toBeTrue()
        ->and($user->rsvps()->where('event_id', $event->id)->first()->rsvp->status)->toBe('going');
});

test('it updates existing RSVP without detaching', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create();

    $user->rsvps()->attach($event, ['status' => 'going']);

    app(RSVPAction::class)->execute($user, $event, RsvpStatus::Maybe);

    expect($user->rsvps()->where('event_id', $event->id)->exists())->toBeTrue()
        ->and($user->rsvps()->count())->toBe(1)
        ->and($user->rsvps()->where('event_id', $event->id)->first()->rsvp->status)->toBe('maybe');
});
