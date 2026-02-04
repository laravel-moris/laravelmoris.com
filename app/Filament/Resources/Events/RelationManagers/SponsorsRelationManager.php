<?php

declare(strict_types=1);

namespace App\Filament\Resources\Events\RelationManagers;

use App\Filament\Resources\Sponsors\SponsorResource;
use App\Models\Sponsor;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SponsorsRelationManager extends RelationManager
{
    protected static string $relationship = 'sponsors';

    protected static ?string $title = 'Sponsors';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(fn () => asset('images/default-sponsor.png'))
                    ->imageSize(50),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('website')
                    ->url(fn (?string $state): ?string => $state)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Create Sponsor')
                    ->modalHeading('Create New Sponsor')
                    ->mutateDataUsing(function (array $data, RelationManager $livewire): array {
                        $data['event_id'] = $livewire->ownerRecord->id;

                        return $data;
                    }),
                AttachAction::make()
                    ->recordSelectSearchColumns(['name', 'website'])
                    ->preloadRecordSelect()
                    ->recordTitle(fn (Sponsor $record): string => $record->name),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record): string => SponsorResource::getUrl('view', ['record' => $record->getKey()])),
                DetachAction::make(),
            ])
            ->toolbarActions([
                DetachBulkAction::make(),
            ]);
    }
}
