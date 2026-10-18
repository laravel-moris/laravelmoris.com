<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\UpdatePhysicalEventData;
use App\Enums\EventLocation;
use App\Models\Event;
use App\Models\PhysicalLocation;

final readonly class UpdatePhysicalEvent
{
    public function execute(UpdatePhysicalEventData $data): Event
    {
        $event = Event::query()->findOrFail($data->eventId);

        $event->update([
            'title' => $data->title,
            'description' => $data->description,
            'type' => EventLocation::Physical,
            'starts_at' => $data->startsAt,
            'ends_at' => $data->endsAt,
            'location_type' => PhysicalLocation::class,
            'location_id' => $data->locationId,
        ]);

        return $event->fresh('location');
    }
}
