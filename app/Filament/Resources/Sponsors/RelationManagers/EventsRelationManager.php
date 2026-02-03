<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sponsors\RelationManagers;

use App\Enums\EventLocation;
use App\Filament\Resources\Events\EventResource;
use App\Models\Event;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    protected static ?string $title = 'Sponsored Events';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (EventLocation|string|null $state): string => $state instanceof EventLocation
                        ? $state->label()
                        : (EventLocation::tryFrom((string) $state)?->label() ?? (string) $state)),
                TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                AttachAction::make()
                    ->recordSelectSearchColumns(['title'])
                    ->preloadRecordSelect()
                    ->recordTitle(fn (Event $record): string => $record->title),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record): string => EventResource::getUrl('view', ['record' => $record->getKey()])),
                DetachAction::make(),
            ])
            ->toolbarActions([
                DetachBulkAction::make(),
            ]);
    }
}
