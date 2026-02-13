<?php

declare(strict_types=1);

namespace App\Filament\Resources\Events\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use App\Actions\UpdateOnlineEvent;
use App\Actions\UpdatePhysicalEvent;
use App\Data\UpdateOnlineEventData;
use App\Data\UpdatePhysicalEventData;
use App\Enums\EventLocation;
use App\Filament\Resources\Events\EventResource;
use App\Models\Event;
use App\Models\OnlineLocation;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Fill online location fields for existing online events
        if ($data['type'] === EventLocation::Online->value &&
            filled($data['location_id']) &&
            $data['location_type'] === OnlineLocation::class) {

            $onlineLocation = OnlineLocation::query()->find($data['location_id']);

            if ($onlineLocation) {
                $data['online_platform'] = $onlineLocation->platform;
                $data['meeting_url'] = $onlineLocation->url;
            }
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var Event $event */
        $event = $record;

        // For online events, use the UpdateOnlineEvent action
        if ($data['type'] === EventLocation::Online->value) {
            $updateData = UpdateOnlineEventData::from([
                'eventId' => $event->id,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'starts_at' => Date::parse($data['starts_at']),
                'ends_at' => Date::parse($data['ends_at']),
                'platform' => $data['online_platform'] ?? null,
                'meeting_url' => $data['meeting_url'] ?? '',
            ]);

            return app(UpdateOnlineEvent::class)->execute($updateData);
        }

        // For physical events, use the UpdatePhysicalEvent action
        if ($data['type'] === EventLocation::Physical->value) {
            $updateData = UpdatePhysicalEventData::from([
                'eventId' => $event->id,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'starts_at' => Date::parse($data['starts_at']),
                'ends_at' => Date::parse($data['ends_at']),
                'location_id' => $data['location_id'],
            ]);

            return app(UpdatePhysicalEvent::class)->execute($updateData);
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
