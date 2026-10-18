<?php

declare(strict_types=1);

use App\Mail\EventReminder;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('it sends reminders to confirmed attendees of upcoming events', function (): void {
    Mail::fake();

    $event = Event::factory()->create(['starts_at' => now()->addHour()]);

    $goingAttendee = User::factory()->create();
    $maybeAttendee = User::factory()->create();
    $notGoingAttendee = User::factory()->create();

    $event->attendees()->attach($goingAttendee, ['status' => 'going']);
    $event->attendees()->attach($maybeAttendee, ['status' => 'maybe']);
    $event->attendees()->attach($notGoingAttendee, ['status' => 'not_going']);

    $this->artisan('events:send-reminders')->assertSuccessful();

    Mail::assertQueued(EventReminder::class, 1);

    Mail::assertQueued(EventReminder::class, function (EventReminder $mail) use ($goingAttendee, $event): bool {
        return $mail->hasTo($goingAttendee->email)
            && $mail->event->is($event)
            && $mail->user->is($goingAttendee);
    });
});

test('it does not send reminders for events outside the 24-hour window', function (): void {
    Mail::fake();

    $pastEvent = Event::factory()->create(['starts_at' => now()->subDay()]);
    $farFutureEvent = Event::factory()->create(['starts_at' => now()->addWeek()]);

    $attendee = User::factory()->create();
    $pastEvent->attendees()->attach($attendee, ['status' => 'going']);
    $farFutureEvent->attendees()->attach($attendee, ['status' => 'going']);

    $this->artisan('events:send-reminders')->assertSuccessful();

    Mail::assertNothingQueued();
});

test('it sends reminders for multiple events within the window', function (): void {
    Mail::fake();

    $eventOne = Event::factory()->create(['starts_at' => now()->addHour()]);
    $eventTwo = Event::factory()->create(['starts_at' => now()->addHours(2)]);

    $attendee = User::factory()->create();
    $eventOne->attendees()->attach($attendee, ['status' => 'going']);
    $eventTwo->attendees()->attach($attendee, ['status' => 'going']);

    $this->artisan('events:send-reminders')->assertSuccessful();

    Mail::assertQueued(EventReminder::class, 2);
});
