<?php

declare(strict_types=1);

use App\Enums\PaperStatus;
use App\Models\Event;
use App\Models\Paper;
use App\Models\User;

test('it displays main menu and can exit', function () {
    $this->artisan('papers:manage')
        ->expectsQuestion('Paper Management', 'exit')
        ->assertSuccessful();
});

test('it displays papers in table format', function () {
    $speaker = User::factory()->create(['name' => 'John Doe']);
    $event = Event::factory()->create(['title' => 'Laravel Meetup']);
    $paper = Paper::factory()->create([
        'user_id' => $speaker->id,
        'event_id' => $event->id,
        'title' => 'Advanced Laravel Patterns',
        'status' => PaperStatus::Approved,
    ]);

    $this->artisan('papers:manage')
        ->expectsQuestion('Paper Management', 'view')
        ->expectsQuestion('Select a paper', (string) $paper->id)
        ->expectsQuestion('Actions', 'back')
        ->expectsQuestion('Paper Management', 'exit')
        ->assertSuccessful();
});

test('it can view paper details and go back', function () {
    $speaker = User::factory()->create(['name' => 'John Doe']);
    $event = Event::factory()->create(['title' => 'Laravel Meetup']);
    $paper = Paper::factory()->create([
        'user_id' => $speaker->id,
        'event_id' => $event->id,
        'title' => 'Advanced Laravel Patterns',
        'status' => PaperStatus::Approved,
    ]);

    $this->artisan('papers:manage')
        ->expectsQuestion('Paper Management', 'view')
        ->expectsQuestion('Select a paper', (string) $paper->id)
        ->expectsQuestion('Actions', 'back')
        ->expectsQuestion('Paper Management', 'exit')
        ->assertSuccessful();
});
