<?php

declare(strict_types=1);

namespace App\Filament\Resources\Papers\Tables;

use App\Actions\Paper\MovePaperToEventAction;
use App\Actions\Paper\UpdatePaperStatusAction;
use App\Enums\PaperStatus;
use App\Models\Event;
use App\Models\Paper;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PapersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('speaker.name')
                    ->label('Speaker')
                    ->searchable(),
                TextColumn::make('event.title')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (PaperStatus|string|null $state): ?string => $state instanceof PaperStatus ? $state->color() : PaperStatus::tryFrom((string) $state)?->color())
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
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
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
