<?php

declare(strict_types=1);

namespace App\Actions;

use App\Data\CreateOnlineEventData;
use App\Enums\EventLocation;
use App\Models\Event;
use App\Models\OnlineLocation;
use Illuminate\Support\Facades\DB;

final class CreateOnlineEvent
{
    public function execute(CreateOnlineEventData $data): Event
    {
        return DB::transaction(function () use ($data): Event {
            // Create the online location first
            $onlineLocation = OnlineLocation::query()->create([
                'platform' => $data->platform,
                'url' => $data->meeting_url,
            ]);

            // Create the event with polymorphic relationship
            $event = Event::query()->create([
                'title' => $data->title,
                'description' => $data->description,
                'type' => EventLocation::Online,
                'starts_at' => $data->starts_at,
                'ends_at' => $data->ends_at,
                'location_type' => OnlineLocation::class,
                'location_id' => $onlineLocation->id,
            ]);

            return $event->fresh('location');
        });
    }
}
