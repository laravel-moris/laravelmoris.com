<?php

declare(strict_types=1);

use App\Models\Event;
use App\Models\Paper;

test('it has a papers relationship', function () {
    $event = Event::factory()->create();

    $paper = Paper::factory()->create([
        'event_id' => $event->id,
    ]);

    $papers = $event->papers()->get();

    expect($papers)->toHaveCount(1)
        ->and($papers->first()?->id)->toBe($paper->id);
});
