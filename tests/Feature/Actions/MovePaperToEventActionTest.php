<?php

declare(strict_types=1);

use App\Actions\Paper\MovePaperToEventAction;
use App\Models\Event;
use App\Models\Paper;

test('it moves paper to a different event', function () {
    $originalEvent = Event::factory()->create();
    $newEvent = Event::factory()->create();
    $paper = Paper::factory()->create([
        'event_id' => $originalEvent->id,
    ]);

    app(MovePaperToEventAction::class)->execute($paper, $newEvent);

    expect($paper->refresh()->event_id)->toBe($newEvent->id);
});

test('it correctly updates the event_id', function () {
    $event1 = Event::factory()->create();
    $event2 = Event::factory()->create();
    $paper = Paper::factory()->create([
        'event_id' => $event1->id,
    ]);

    expect($paper->event_id)->toBe($event1->id);

    app(MovePaperToEventAction::class)->execute($paper, $event2);

    $paper->refresh();

    expect($paper->event_id)->toBe($event2->id)
        ->and($paper->event_id)->not->toBe($event1->id);
});
