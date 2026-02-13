<?php

declare(strict_types=1);

namespace App\Filament\Resources\OnlineLocations\RelationManagers;

use App\Filament\Resources\Events\EventResource;
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

    protected static ?string $title = 'Events';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('type')
                    ->badge(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->recordSelectSearchColumns(['title'])
                    ->preloadRecordSelect(),
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
