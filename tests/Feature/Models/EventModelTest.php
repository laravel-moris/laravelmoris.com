<?php

declare(strict_types=1);

use App\Models\Event;
use App\Models\OnlineLocation;
use App\Models\Paper;
use App\Models\PhysicalLocation;
use Illuminate\Database\ClassMorphViolationException;

test('it has a papers relationship', function () {
    $event = Event::factory()->create();

    $paper = Paper::factory()->create([
        'event_id' => $event->id,
    ]);

    $papers = $event->papers()->get();

    expect($papers)->toHaveCount(1)
        ->and($papers->first()?->id)->toBe($paper->id);
});

test('it resolves legacy and aliased event location morph types', function (string $class, string $alias): void {
    expect(Event::getActualClassNameForMorph($class))->toBe($class)
        ->and(Event::getActualClassNameForMorph($alias))->toBe($class)
        ->and((new $class)->getMorphClass())->toBe($alias);
})->with([
    [OnlineLocation::class, 'online-location'],
    [PhysicalLocation::class, 'physical-location'],
]);

test('it rejects unregistered event location morph types', function (): void {
    Event::getActualClassNameForMorph('unregistered-location');
})->throws(ClassMorphViolationException::class);
