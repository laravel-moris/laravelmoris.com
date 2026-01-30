<?php

declare(strict_types=1);

use App\Actions\Paper\SubmitPaperAction;
use App\Data\Paper\SubmitPaperData;
use App\Enums\PaperStatus;
use App\Models\Event;
use App\Models\User;

test('it creates a paper with submitted status', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create();
    $data = new SubmitPaperData(
        title: 'My Talk Title',
        description: 'Talk description',
        phone: null,
        secondaryEmail: null,
    );

    app(SubmitPaperAction::class)->execute($user, $event, $data);

    expect($user->papers()->where('event_id', $event->id)->exists())->toBeTrue()
        ->and($user->papers()->where('event_id', $event->id)->first()->status)->toBe(PaperStatus::Submitted);
});

test('it updates user phone when provided', function () {
    $user = User::factory()->create(['phone' => null]);
    $event = Event::factory()->create();
    $data = new SubmitPaperData(
        title: 'My Talk',
        description: null,
        phone: '57654321',
        secondaryEmail: null,
    );

    app(SubmitPaperAction::class)->execute($user, $event, $data);

    expect($user->refresh()->phone)->toBe('57654321');
});

test('it updates user secondary email when provided', function () {
    $user = User::factory()->create(['secondary_email' => null]);
    $event = Event::factory()->create();
    $data = new SubmitPaperData(
        title: 'My Talk',
        description: null,
        phone: null,
        secondaryEmail: 'backup@example.com',
    );

    app(SubmitPaperAction::class)->execute($user, $event, $data);

    expect($user->refresh()->secondary_email)->toBe('backup@example.com');
});

test('it does not update user when no optional fields provided', function () {
    $user = User::factory()->create(['phone' => '51234567', 'secondary_email' => 'original@example.com']);
    $event = Event::factory()->create();
    $data = new SubmitPaperData(
        title: 'My Talk',
        description: null,
        phone: null,
        secondaryEmail: null,
    );

    app(SubmitPaperAction::class)->execute($user, $event, $data);

    expect($user->refresh()->phone)->toBe('51234567')
        ->and($user->secondary_email)->toBe('original@example.com');
});

test('it stores paper with title and description', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create();
    $data = new SubmitPaperData(
        title: 'Advanced Laravel Patterns',
        description: 'Deep dive into Laravel architecture',
        phone: null,
        secondaryEmail: null,
    );

    app(SubmitPaperAction::class)->execute($user, $event, $data);

    $paper = $user->papers()->where('event_id', $event->id)->first();
    expect($paper->title)->toBe('Advanced Laravel Patterns')
        ->and($paper->description)->toBe('Deep dive into Laravel architecture');
});
