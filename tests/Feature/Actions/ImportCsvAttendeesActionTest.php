<?php

declare(strict_types=1);

use App\Actions\Event\ImportCsvAttendeesAction;
use App\Data\Event\ImportCsvAttendeesData;
use App\Enums\RsvpStatus;
use App\Models\Event;
use App\Models\User;

test('it imports attendees from CSV data with header row', function () {
    $event = Event::factory()->create();

    $csvData = "name,email\nJohn Doe,john@example.com\nJane Smith,jane@example.com";

    $result = app(ImportCsvAttendeesAction::class)->execute(
        $event,
        ImportCsvAttendeesData::from(['csvData' => $csvData]),
    );

    expect($result['imported'])->toBe(2)
        ->and($result['skipped'])->toBe(0)
        ->and($result['errors'])->toBe([])
        ->and(User::query()->where('email', 'john@example.com')->exists())->toBeTrue()
        ->and(User::query()->where('email', 'jane@example.com')->exists())->toBeTrue()
        ->and($event->attendees()->count())->toBe(2);
});

test('it imports attendees from CSV data without header row', function () {
    $event = Event::factory()->create();

    $csvData = "John Doe,john@example.com\nJane Smith,jane@example.com";

    $result = app(ImportCsvAttendeesAction::class)->execute(
        $event,
        ImportCsvAttendeesData::from(['csvData' => $csvData]),
    );

    expect($result['imported'])->toBe(2)
        ->and($result['skipped'])->toBe(0)
        ->and($result['errors'])->toBe([])
        ->and($event->attendees()->count())->toBe(2);
});

test('it creates users without email using generated email', function () {
    $event = Event::factory()->create();

    $csvData = "John Doe\nJane Smith";

    $result = app(ImportCsvAttendeesAction::class)->execute(
        $event,
        ImportCsvAttendeesData::from(['csvData' => $csvData]),
    );

    expect($result['imported'])->toBe(2)
        ->and($result['errors'])->toBe([])
        ->and(User::query()->where('email', 'john-doe@attendee.laravelmoris.com')->exists())->toBeTrue()
        ->and(User::query()->where('email', 'jane-smith@attendee.laravelmoris.com')->exists())->toBeTrue()
        ->and($event->attendees()->count())->toBe(2);
});

test('it skips duplicate attendees', function () {
    $event = Event::factory()->create();
    $user = User::factory()->create(['email' => 'existing@example.com']);
    $event->attendees()->attach($user->id, ['status' => RsvpStatus::Going->value]);

    $csvData = "John Doe,john@example.com\nExisting User,existing@example.com";

    $result = app(ImportCsvAttendeesAction::class)->execute(
        $event,
        ImportCsvAttendeesData::from(['csvData' => $csvData]),
    );

    expect($result['imported'])->toBe(1)
        ->and($result['skipped'])->toBe(1)
        ->and($result['errors'])->toBe([])
        ->and($event->attendees()->count())->toBe(2);
});

test('it finds existing user by email and does not duplicate', function () {
    $event = Event::factory()->create();
    $user = User::factory()->create(['email' => 'john@example.com', 'name' => 'Original Name']);

    $csvData = 'John Doe,john@example.com';

    $result = app(ImportCsvAttendeesAction::class)->execute(
        $event,
        ImportCsvAttendeesData::from(['csvData' => $csvData]),
    );

    expect($result['imported'])->toBe(1)
        ->and($result['errors'])->toBe([])
        ->and(User::query()->where('email', 'john@example.com')->count())->toBe(1)
        ->and($user->fresh()->name)->toBe('Original Name');
});

test('it reports errors for lines with missing name', function () {
    $event = Event::factory()->create();

    $csvData = "name,email\n,jane@example.com\nJohn Doe,john@example.com";

    $result = app(ImportCsvAttendeesAction::class)->execute(
        $event,
        ImportCsvAttendeesData::from(['csvData' => $csvData]),
    );

    expect($result['imported'])->toBe(1)
        ->and($result['errors'])->toHaveCount(1)
        ->and($result['errors'][0])->toContain('missing name')
        ->and($event->attendees()->count())->toBe(1);
});

test('it handles empty lines gracefully', function () {
    $event = Event::factory()->create();

    $csvData = "John Doe,john@example.com\n\n\nJane Smith,jane@example.com";

    $result = app(ImportCsvAttendeesAction::class)->execute(
        $event,
        ImportCsvAttendeesData::from(['csvData' => $csvData]),
    );

    expect($result['imported'])->toBe(2)
        ->and($result['errors'])->toBe([])
        ->and($event->attendees()->count())->toBe(2);
});

test('it generates unique emails for attendees with same name', function () {
    $event = Event::factory()->create();

    $csvData = "John Doe\nJohn Doe";

    $result = app(ImportCsvAttendeesAction::class)->execute(
        $event,
        ImportCsvAttendeesData::from(['csvData' => $csvData]),
    );

    expect($result['imported'])->toBe(2)
        ->and($result['errors'])->toBe([])
        ->and($event->attendees()->count())->toBe(2);

    $emails = $event->attendees()->pluck('email')->sort()->values();
    expect($emails[0])->toBe('john-doe-1@attendee.laravelmoris.com')
        ->and($emails[1])->toBe('john-doe@attendee.laravelmoris.com');
});

test('it imports with both name and rsvp fields', function () {
    $event = Event::factory()->create();

    $csvData = 'John Doe,john@example.com';
    $result = app(ImportCsvAttendeesAction::class)->execute(
        $event,
        ImportCsvAttendeesData::from(['csvData' => $csvData]),
    );

    expect($result['imported'])->toBe(1);
    $rsvp = $event->attendees()->where('email', 'john@example.com')->first()->rsvp;
    expect($rsvp->status)->toBe(RsvpStatus::Going->value);
});
