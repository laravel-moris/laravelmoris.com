<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\UpdatePhysicalEventData;
use App\Enums\EventLocation;
use App\Models\Event;
use App\Models\PhysicalLocation;

final class UpdatePhysicalEvent
{
    public function execute(UpdatePhysicalEventData $data): Event
    {
        $event = Event::query()->findOrFail($data->eventId);

        $event->update([
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
