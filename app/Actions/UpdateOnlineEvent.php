<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\UpdateOnlineEventData;
use App\Enums\EventLocation;
use App\Models\Event;
use App\Models\OnlineLocation;
use Illuminate\Support\Facades\DB;

final readonly class UpdateOnlineEvent
{
    public function execute(UpdateOnlineEventData $data): Event
    {
        return DB::transaction(function () use ($data): Event {
            $event = Event::query()->findOrFail($data->eventId);

            // Update or create the online location
            $onlineLocation = null;

            if ($event->location_type === OnlineLocation::class && $event->location_id) {
                // Update existing location
                $onlineLocation = OnlineLocation::query()->findOrFail($event->location_id);
                $onlineLocation->update([
                    'platform' => $data->platform,
                    'url' => $data->meetingUrl,
                ]);
            } else {
                // Create new location
                $onlineLocation = OnlineLocation::query()->create([
                    'platform' => $data->platform,
                    'url' => $data->meetingUrl,
                ]);
            }

            // Update the event
            $event->update([
                'title' => $data->title,
                'description' => $data->description,
                'type' => EventLocation::Online,
                'starts_at' => $data->startsAt,
                'ends_at' => $data->endsAt,
                'location_type' => OnlineLocation::class,
                'location_id' => $onlineLocation->id,
            ]);

            return $event->fresh('location');
        });
    }
}
