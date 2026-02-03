<?php

declare(strict_types=1);

use App\Enums\PaperStatus;
use App\Models\Event;
use App\Models\Paper;

test('it can exit from the main menu', function () {
    $this->artisan('papers:manage')
        ->expectsChoice('Paper Management', 'exit', [
            'view' => 'View Papers',
            'exit' => 'Exit',
        ])
        ->assertExitCode(0);
});

test('it can view papers when none exist and return to the menu', function () {
    $this->artisan('papers:manage')
        ->expectsChoice('Paper Management', 'view', [
            'view' => 'View Papers',
            'exit' => 'Exit',
        ])
        ->expectsChoice('What next?', 'back', [
            'back' => 'Back',
        ])
        ->expectsChoice('Paper Management', 'exit', [
            'view' => 'View Papers',
            'exit' => 'Exit',
        ])
        ->assertExitCode(0);
});

test('it can select a paper and go back', function () {
    $paper = Paper::factory()->create();

    $paperOptions = [
        (string) $paper->id => "[{$paper->id}] {$paper->title} - {$paper->speaker->name}",
    ];

    $this->artisan('papers:manage')
        ->expectsChoice('Paper Management', 'view', [
            'view' => 'View Papers',
            'exit' => 'Exit',
        ])
        ->expectsChoice('Select a paper', (string) $paper->id, $paperOptions)
        ->expectsChoice('Actions', 'back', [
            'status' => 'Change Status',
            'move' => 'Move to Event',
            'back' => 'Back',
        ])
        ->expectsChoice('Paper Management', 'exit', [
            'view' => 'View Papers',
            'exit' => 'Exit',
        ])
        ->assertExitCode(0);
});

test('it can open change status and keep the status unchanged', function () {
    $paper = Paper::factory()->create(['status' => PaperStatus::Draft]);

    $paperOptions = [
        (string) $paper->id => "[{$paper->id}] {$paper->title} - {$paper->speaker->name}",
    ];

    $this->artisan('papers:manage')
        ->expectsChoice('Paper Management', 'view', [
            'view' => 'View Papers',
            'exit' => 'Exit',
        ])
        ->expectsChoice('Select a paper', (string) $paper->id, $paperOptions)
        ->expectsChoice('Actions', 'status', [
            'status' => 'Change Status',
            'move' => 'Move to Event',
            'back' => 'Back',
        ])
        ->expectsChoice('Select new status', PaperStatus::Draft->value, [
            PaperStatus::Draft->value => PaperStatus::Draft->value.' (current)',
            PaperStatus::Submitted->value => PaperStatus::Submitted->value,
            PaperStatus::Approved->value => PaperStatus::Approved->value,
            PaperStatus::Rejected->value => PaperStatus::Rejected->value,
        ])
        ->expectsChoice('Actions', 'back', [
            'status' => 'Change Status',
            'move' => 'Move to Event',
            'back' => 'Back',
        ])
        ->expectsChoice('Paper Management', 'exit', [
            'view' => 'View Papers',
            'exit' => 'Exit',
        ])
        ->assertExitCode(0);

    expect($paper->refresh()->status)->toBe(PaperStatus::Draft);
});

test('it can cancel a status change', function () {
    $paper = Paper::factory()->create(['status' => PaperStatus::Draft]);

    $paperOptions = [
        (string) $paper->id => "[{$paper->id}] {$paper->title} - {$paper->speaker->name}",
    ];

    $this->artisan('papers:manage')
        ->expectsChoice('Paper Management', 'view', [
            'view' => 'View Papers',
            'exit' => 'Exit',
        ])
        ->expectsChoice('Select a paper', (string) $paper->id, $paperOptions)
        ->expectsChoice('Actions', 'status', [
            'status' => 'Change Status',
            'move' => 'Move to Event',
            'back' => 'Back',
        ])
        ->expectsChoice('Select new status', PaperStatus::Submitted->value, [
            PaperStatus::Draft->value => PaperStatus::Draft->value.' (current)',
            PaperStatus::Submitted->value => PaperStatus::Submitted->value,
            PaperStatus::Approved->value => PaperStatus::Approved->value,
            PaperStatus::Rejected->value => PaperStatus::Rejected->value,
        ])
        ->expectsConfirmation("Change status to '".PaperStatus::Submitted->value."'?", 'no')
        ->expectsChoice('Actions', 'back', [
            'status' => 'Change Status',
            'move' => 'Move to Event',
            'back' => 'Back',
        ])
        ->expectsChoice('Paper Management', 'exit', [
            'view' => 'View Papers',
            'exit' => 'Exit',
        ])
        ->assertExitCode(0);

    expect($paper->refresh()->status)->toBe(PaperStatus::Draft);
});

test('it can change a paper status', function () {
    $paper = Paper::factory()->create(['status' => PaperStatus::Draft]);

    $paperOptions = [
        (string) $paper->id => "[{$paper->id}] {$paper->title} - {$paper->speaker->name}",
    ];

    $this->artisan('papers:manage')
        ->expectsChoice('Paper Management', 'view', [
            'view' => 'View Papers',
            'exit' => 'Exit',
        ])
        ->expectsChoice('Select a paper', (string) $paper->id, $paperOptions)
        ->expectsChoice('Actions', 'status', [
            'status' => 'Change Status',
            'move' => 'Move to Event',
            'back' => 'Back',
        ])
        ->expectsChoice('Select new status', PaperStatus::Submitted->value, [
            PaperStatus::Draft->value => PaperStatus::Draft->value.' (current)',
            PaperStatus::Submitted->value => PaperStatus::Submitted->value,
            PaperStatus::Approved->value => PaperStatus::Approved->value,
            PaperStatus::Rejected->value => PaperStatus::Rejected->value,
        ])
        ->expectsConfirmation("Change status to '".PaperStatus::Submitted->value."'?", 'yes')
        ->expectsChoice('Actions', 'back', [
            'status' => 'Change Status',
            'move' => 'Move to Event',
            'back' => 'Back',
        ])
        ->expectsChoice('Paper Management', 'exit', [
            'view' => 'View Papers',
            'exit' => 'Exit',
        ])
        ->assertExitCode(0);

    expect($paper->refresh()->status)->toBe(PaperStatus::Submitted);
});

test('it warns when there are no other events to move to', function () {
    $paper = Paper::factory()->create();

    $paperOptions = [
        (string) $paper->id => "[{$paper->id}] {$paper->title} - {$paper->speaker->name}",
    ];

    $this->artisan('papers:manage')
        ->expectsChoice('Paper Management', 'view', [
            'view' => 'View Papers',
            'exit' => 'Exit',
        ])
        ->expectsChoice('Select a paper', (string) $paper->id, $paperOptions)
        ->expectsChoice('Actions', 'move', [
            'status' => 'Change Status',
            'move' => 'Move to Event',
            'back' => 'Back',
        ])
        ->expectsChoice('Actions', 'back', [
            'status' => 'Change Status',
            'move' => 'Move to Event',
            'back' => 'Back',
        ])
        ->expectsChoice('Paper Management', 'exit', [
            'view' => 'View Papers',
            'exit' => 'Exit',
        ])
        ->assertExitCode(0);

    expect($paper->refresh()->event_id)->not->toBeNull();
});

test('it can cancel moving a paper to another event', function () {
    $paper = Paper::factory()->create();
    $originalEventId = $paper->event_id;

    $newEvent = Event::factory()->create();

    $paperOptions = [
        (string) $paper->id => "[{$paper->id}] {$paper->title} - {$paper->speaker->name}",
    ];

    $eventOptions = [
        (string) $newEvent->id => sprintf('%s (%s)', $newEvent->title, $newEvent->starts_at->format('Y-m-d')),
    ];

    $this->artisan('papers:manage')
        ->expectsChoice('Paper Management', 'view', [
            'view' => 'View Papers',
            'exit' => 'Exit',
        ])
        ->expectsChoice('Select a paper', (string) $paper->id, $paperOptions)
        ->expectsChoice('Actions', 'move', [
            'status' => 'Change Status',
            'move' => 'Move to Event',
            'back' => 'Back',
        ])
        ->expectsChoice('Move to which event?', (string) $newEvent->id, $eventOptions)
        ->expectsConfirmation("Move to '{$newEvent->title}'?", 'no')
        ->expectsChoice('Actions', 'back', [
            'status' => 'Change Status',
            'move' => 'Move to Event',
            'back' => 'Back',
        ])
        ->expectsChoice('Paper Management', 'exit', [
            'view' => 'View Papers',
            'exit' => 'Exit',
        ])
        ->assertExitCode(0);

    expect($paper->refresh()->event_id)->toBe($originalEventId);
});

test('it can move a paper to another event', function () {
    $paper = Paper::factory()->create();
    $originalEventId = $paper->event_id;

    $newEvent = Event::factory()->create();

    $paperOptions = [
        (string) $paper->id => "[{$paper->id}] {$paper->title} - {$paper->speaker->name}",
    ];

    $eventOptions = [
        (string) $newEvent->id => sprintf('%s (%s)', $newEvent->title, $newEvent->starts_at->format('Y-m-d')),
    ];

    $this->artisan('papers:manage')
        ->expectsChoice('Paper Management', 'view', [
            'view' => 'View Papers',
            'exit' => 'Exit',
        ])
        ->expectsChoice('Select a paper', (string) $paper->id, $paperOptions)
        ->expectsChoice('Actions', 'move', [
            'status' => 'Change Status',
            'move' => 'Move to Event',
            'back' => 'Back',
        ])
        ->expectsChoice('Move to which event?', (string) $newEvent->id, $eventOptions)
        ->expectsConfirmation("Move to '{$newEvent->title}'?", 'yes')
        ->expectsChoice('Actions', 'back', [
            'status' => 'Change Status',
            'move' => 'Move to Event',
            'back' => 'Back',
        ])
        ->expectsChoice('Paper Management', 'exit', [
            'view' => 'View Papers',
            'exit' => 'Exit',
        ])
        ->assertExitCode(0);

    expect($paper->refresh()->event_id)
        ->not->toBe($originalEventId)
        ->toBe($newEvent->id);
});
