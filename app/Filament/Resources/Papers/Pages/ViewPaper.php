<?php

declare(strict_types=1);

namespace App\Filament\Resources\Papers\Pages;

use App\Actions\Paper\MovePaperToEventAction;
use App\Actions\Paper\UpdatePaperStatusAction;
use App\Enums\PaperStatus;
use App\Filament\Resources\Papers\PaperResource;
use App\Models\Event;
use App\Models\Paper;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewPaper extends ViewRecord
{
    protected static string $resource = PaperResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('changeStatus')
                ->label('Change status')
                ->schema([
                    Select::make('status')
                        ->options(collect(PaperStatus::cases())->mapWithKeys(fn (PaperStatus $status): array => [$status->value => $status->label()])->all())
                        ->required(),
                ])
                ->fillForm(fn (Paper $record): array => [
                    'status' => $record->status instanceof PaperStatus ? $record->status->value : (string) $record->status,
                ])
                ->action(function (array $data, Paper $record, UpdatePaperStatusAction $updatePaperStatusAction): void {
                    $updatePaperStatusAction->execute($record, PaperStatus::from($data['status']));

                    Notification::make()
                        ->title('Paper status updated')
                        ->success()
                        ->send();
                }),
            Action::make('moveToEvent')
                ->label('Move to event')
                ->schema([
                    Select::make('event_id')
                        ->label('Event')
                        ->options(fn (): array => Event::query()->latest('starts_at')->pluck('title', 'id')->all())
                        ->searchable()
                        ->required(),
                ])
                ->fillForm(fn (Paper $record): array => [
                    'event_id' => $record->event_id,
                ])
                ->action(function (array $data, Paper $record, MovePaperToEventAction $movePaperToEventAction): void {
                    $event = Event::query()->findOrFail($data['event_id']);

                    $movePaperToEventAction->execute($record, $event);

                    Notification::make()
                        ->title('Paper moved to event')
                        ->success()
                        ->send();
                }),
        ];
    }
}
