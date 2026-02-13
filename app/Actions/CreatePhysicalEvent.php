<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\CreatePhysicalEventData;
use App\Enums\EventLocation;
use App\Models\Event;
use App\Models\PhysicalLocation;

final class CreatePhysicalEvent
{
    public function execute(CreatePhysicalEventData $data): Event
    {
        $event = Event::query()->create([
            'title' => $data->title,
            'description' => $data->description,
            'type' => EventLocation::Physical,
            'starts_at' => $data->starts_at,
            'ends_at' => $data->ends_at,
            'location_type' => PhysicalLocation::class,
            'location_id' => $data->location_id,
        ]);

        return $event->fresh('location');
    }
}
