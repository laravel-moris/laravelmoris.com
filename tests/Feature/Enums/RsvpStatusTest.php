<?php

declare(strict_types=1);

use App\Enums\RsvpStatus;

test('it provides a label and color for each RSVP status', function (RsvpStatus $status, string $label, string $color) {
    expect($status->label())->toBe($label)
        ->and($status->color())->toBe($color);
})->with([
    [RsvpStatus::Going, 'Going', 'green'],
    [RsvpStatus::Maybe, 'Maybe', 'gold'],
    [RsvpStatus::NotGoing, 'Not Going', 'coral'],
]);
