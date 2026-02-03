<?php

declare(strict_types=1);

namespace App\Filament\Resources\Events\RelationManagers;

use App\Enums\PaperStatus;
use App\Filament\Resources\Papers\PaperResource;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PapersRelationManager extends RelationManager
{
    protected static string $relationship = 'papers';

    protected static ?string $title = 'Papers';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('speaker.name')
                    ->label('Speaker')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (PaperStatus|string|null $state): ?string => $state instanceof PaperStatus ? $state->color() : PaperStatus::tryFrom((string) $state)?->color()),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([])
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record): string => PaperResource::getUrl('view', ['record' => $record->getKey()])),
                EditAction::make()
                    ->url(fn ($record): string => PaperResource::getUrl('edit', ['record' => $record->getKey()])),
            ])
            ->toolbarActions([]);
    }
}
