<?php

declare(strict_types=1);

use App\Enums\EventLocation;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\OnlineLocation;
use App\Models\PhysicalLocation;

test('it returns null when location is not loaded', function () {
    $location = OnlineLocation::factory()->create();
    $event = Event::factory()->create(['type' => EventLocation::Online]);
    $event->location()->associate($location)->save();

    $eventWithoutLoadedLocation = Event::query()->findOrFail($event->id);

    $resource = new class($eventWithoutLoadedLocation) extends EventResource
    {
        public function locationArray(): ?array
        {
            return $this->transformLocation();
        }
    };

    expect($resource->locationArray())->toBeNull();
});

test('it returns null when type is not an EventLocation enum', function () {
    $event = new class
    {
        public string $type = 'not-an-enum';

        public mixed $location = null;

        public function relationLoaded(string $relation): bool
        {
            return $relation === 'location';
        }
    };

    $resource = new class($event) extends EventResource
    {
        public function locationArray(): ?array
        {
            return $this->transformLocation();
        }
    };

    expect($resource->locationArray())->toBeNull();
});

test('it transforms a physical location', function () {
    $location = PhysicalLocation::factory()->create([
        'venue_name' => 'Venue',
        'address' => '1 Street',
        'city' => 'Port Louis',
        'directions_url' => 'https://example.test/directions',
    ]);

    $event = Event::factory()->create(['type' => EventLocation::Physical]);
    $event->location()->associate($location)->save();

    $eventWithLocation = Event::with('location')->findOrFail($event->id);

    $resource = new class($eventWithLocation) extends EventResource
    {
        public function locationArray(): ?array
        {
            return $this->transformLocation();
        }
    };

    expect($resource->locationArray())->toBe([
        'type' => 'physical',
        'name' => 'Venue',
        'address' => '1 Street',
        'city' => 'Port Louis',
        'directions_url' => 'https://example.test/directions',
    ]);
});

test('it transforms an online location', function () {
    $location = OnlineLocation::factory()->create([
        'platform' => 'zoom',
        'url' => 'https://example.test/zoom',
    ]);

    $event = Event::factory()->create(['type' => EventLocation::Online]);
    $event->location()->associate($location)->save();

    $eventWithLocation = Event::with('location')->findOrFail($event->id);

    $resource = new class($eventWithLocation) extends EventResource
    {
        public function locationArray(): ?array
        {
            return $this->transformLocation();
        }
    };

    expect($resource->locationArray())->toBe([
        'type' => 'online',
        'platform' => 'zoom',
        'url' => 'https://example.test/zoom',
    ]);
});
