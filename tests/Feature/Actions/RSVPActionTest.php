<?php

declare(strict_types=1);

use App\Actions\Event\RSVPAction;
use App\Enums\RsvpStatus;
use App\Exceptions\EventFullException;
use App\Mail\RsvpCancellation;
use App\Mail\RsvpConfirmation;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

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

describe('cache updates', function (): void {
    test('it increments rsvps_count_cache when RSVPing as going', function (): void {
        $user = User::factory()->create(['rsvps_count_cache' => 0]);
        $event = Event::factory()->create();

        app(RSVPAction::class)->execute($user, $event, RsvpStatus::Going);

        expect($user->fresh()->rsvps_count_cache)->toBe(1);
    });

    test('it does not increment rsvps_count_cache when RSVPing as maybe', function (): void {
        $user = User::factory()->create(['rsvps_count_cache' => 0]);
        $event = Event::factory()->create();

        app(RSVPAction::class)->execute($user, $event, RsvpStatus::Maybe);

        expect($user->fresh()->rsvps_count_cache)->toBe(0);
    });

    test('it decrements rsvps_count_cache when changing from going to not going', function (): void {
        $user = User::factory()->create(['rsvps_count_cache' => 1]);
        $event = Event::factory()->create();
        $user->rsvps()->attach($event, ['status' => 'going']);

        app(RSVPAction::class)->execute($user, $event, RsvpStatus::NotGoing);

        expect($user->fresh()->rsvps_count_cache)->toBe(0);
    });

    test('it does not change rsvps_count_cache when already not going', function (): void {
        $user = User::factory()->create(['rsvps_count_cache' => 0]);
        $event = Event::factory()->create();

        app(RSVPAction::class)->execute($user, $event, RsvpStatus::NotGoing);

        expect($user->fresh()->rsvps_count_cache)->toBe(0);
    });

    test('it decrements when changing from going to maybe', function (): void {
        $user = User::factory()->create(['rsvps_count_cache' => 1]);
        $event = Event::factory()->create();
        $user->rsvps()->attach($event, ['status' => 'going']);

        app(RSVPAction::class)->execute($user, $event, RsvpStatus::Maybe);

        expect($user->fresh()->rsvps_count_cache)->toBe(0);
    });

    test('it increments when changing from maybe to going', function (): void {
        $user = User::factory()->create(['rsvps_count_cache' => 0]);
        $event = Event::factory()->create();
        $user->rsvps()->attach($event, ['status' => 'maybe']);

        app(RSVPAction::class)->execute($user, $event, RsvpStatus::Going);

        expect($user->fresh()->rsvps_count_cache)->toBe(1);
    });
});

describe('email notifications', function (): void {
    test('it sends confirmation email when RSVPing as going', function (): void {
        Mail::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create();

        app(RSVPAction::class)->execute($user, $event, RsvpStatus::Going);

        Mail::assertQueued(RsvpConfirmation::class, function (RsvpConfirmation $mail) use ($event, $user): bool {
            return $mail->hasTo($user->email)
                && $mail->event->is($event)
                && $mail->user->is($user)
                && str_contains($mail->envelope()->subject, $event->title);
        });
    });

    test('it does not send confirmation email when RSVPing as maybe', function (): void {
        Mail::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create();

        app(RSVPAction::class)->execute($user, $event, RsvpStatus::Maybe);

        Mail::assertNothingQueued();
    });

    test('it does not send confirmation email when RSVPing as not going', function (): void {
        Mail::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create();

        app(RSVPAction::class)->execute($user, $event, RsvpStatus::NotGoing);

        Mail::assertNothingQueued();
    });

    test('it sends confirmation email when defaulting to going', function (): void {
        Mail::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create();

        app(RSVPAction::class)->execute($user, $event);

        Mail::assertQueued(RsvpConfirmation::class);
    });

    test('it sends confirmation email when changing from maybe to going', function (): void {
        Mail::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create();
        $user->rsvps()->attach($event, ['status' => 'maybe']);

        app(RSVPAction::class)->execute($user, $event, RsvpStatus::Going);

        Mail::assertQueued(RsvpConfirmation::class);
    });

    test('it sends cancellation email when changing from going to not going', function (): void {
        Mail::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create();
        $user->rsvps()->attach($event, ['status' => 'going']);

        app(RSVPAction::class)->execute($user, $event, RsvpStatus::NotGoing);

        Mail::assertQueued(RsvpCancellation::class, function (RsvpCancellation $mail) use ($event, $user): bool {
            return $mail->hasTo($user->email)
                && $mail->event->is($event)
                && $mail->user->is($user)
                && str_contains($mail->envelope()->subject, $event->title);
        });
    });

    test('it does not send cancellation email when changing from maybe to not going', function (): void {
        Mail::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create();
        $user->rsvps()->attach($event, ['status' => 'maybe']);

        app(RSVPAction::class)->execute($user, $event, RsvpStatus::NotGoing);

        Mail::assertNothingQueued();
    });
});

describe('capacity limits', function (): void {
    test('it allows RSVP when event has no capacity set', function (): void {
        $user = User::factory()->create();
        $event = Event::factory()->create(['capacity' => null]);

        app(RSVPAction::class)->execute($user, $event, RsvpStatus::Going);

        expect($user->rsvps()->where('event_id', $event->id)->exists())->toBeTrue();
    });

    test('it allows RSVP when capacity is not reached', function (): void {
        $user = User::factory()->create();
        $event = Event::factory()->create(['capacity' => 10]);

        app(RSVPAction::class)->execute($user, $event, RsvpStatus::Going);

        expect($user->rsvps()->where('event_id', $event->id)->exists())->toBeTrue();
    });

    test('it throws EventFullException when capacity is reached', function (): void {
        $user = User::factory()->create();
        $event = Event::factory()->create(['capacity' => 1]);
        $otherUser = User::factory()->create();
        $event->attendees()->attach($otherUser, ['status' => 'going']);

        app(RSVPAction::class)->execute($user, $event, RsvpStatus::Going);
    })->throws(EventFullException::class);

    test('it allows already confirmed attendee even when capacity is reached', function (): void {
        $user = User::factory()->create();
        $event = Event::factory()->create(['capacity' => 1]);
        $event->attendees()->attach($user, ['status' => 'going']);

        app(RSVPAction::class)->execute($user, $event, RsvpStatus::Going);

        expect($user->rsvps()->where('event_id', $event->id)->exists())->toBeTrue();
    });

    test('it allows maybe RSVP even when capacity is reached', function (): void {
        $user = User::factory()->create();
        $event = Event::factory()->create(['capacity' => 1]);
        $otherUser = User::factory()->create();
        $event->attendees()->attach($otherUser, ['status' => 'going']);

        app(RSVPAction::class)->execute($user, $event, RsvpStatus::Maybe);

        expect($user->rsvps()->where('event_id', $event->id)->exists())->toBeTrue();
    });
});
