<?php

declare(strict_types=1);

namespace App\Filament\Resources\Events\Pages;

use Illuminate\Support\Facades\Date;
use App\Actions\CreateOnlineEvent;
use App\Actions\CreatePhysicalEvent;
use App\Data\CreateOnlineEventData;
use App\Data\CreatePhysicalEventData;
use App\Enums\EventLocation;
use App\Filament\Resources\Events\EventResource;
use App\Models\Event;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function handleRecordCreation(array $data): Event
    {
        // For online events, use the CreateOnlineEvent action
        if ($data['type'] === EventLocation::Online->value) {
            $onlineEventData = CreateOnlineEventData::from([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'starts_at' => Date::parse($data['starts_at']),
                'ends_at' => Date::parse($data['ends_at']),
                'platform' => $data['online_platform'] ?? null,
                'meeting_url' => $data['meeting_url'],
            ]);

            return app(CreateOnlineEvent::class)->execute($onlineEventData);
        }

        // For physical events, use the CreatePhysicalEvent action
        if ($data['type'] === EventLocation::Physical->value) {
            $physicalEventData = CreatePhysicalEventData::from([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'starts_at' => Date::parse($data['starts_at']),
                'ends_at' => Date::parse($data['ends_at']),
                'location_id' => $data['location_id'],
            ]);

            return app(CreatePhysicalEvent::class)->execute($physicalEventData);
        }

        // If we reach here, the event type is not supported
        Notification::make()
            ->title('Invalid Event Type')
            ->body('The selected event type is not supported.')
            ->danger()
            ->send();

        // This will halt execution and prevent form submission
        $this->halt();

        // This line should never be reached due to halt(), but needed for type safety
        throw new \LogicException('Invalid event type provided.');
    }
}
