<?php

declare(strict_types=1);

namespace App\Filament\Resources\Events\Schemas;

use App\Enums\EventLocation;
use App\Models\OnlineLocation;
use App\Models\PhysicalLocation;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Event Details')
                    ->schema([
                        TextEntry::make('title'),
                        TextEntry::make('description'),
                        TextEntry::make('type')
                            ->badge()
                            ->formatStateUsing(fn (EventLocation|string|null $state): string => $state instanceof EventLocation
                                ? $state->label()
                                : (EventLocation::tryFrom((string) $state)?->label() ?? (string) $state)),
                        TextEntry::make('starts_at')
                            ->dateTime(),
                        TextEntry::make('ends_at')
                            ->dateTime(),
                    ])
                    ->columns(2),

                Section::make('Location')
                    ->schema([
                        // Physical location fields
                        TextEntry::make('location.venue_name')
                            ->label('Venue')
                            ->visible(fn ($record): bool => $record->location instanceof PhysicalLocation),
                        TextEntry::make('location.address')
                            ->label('Address')
                            ->visible(fn ($record): bool => $record->location instanceof PhysicalLocation),
                        TextEntry::make('location.city')
                            ->label('City')
                            ->visible(fn ($record): bool => $record->location instanceof PhysicalLocation),
                        TextEntry::make('location.directions_url')
                            ->label('Directions')
                            ->url(fn ($record): string => $record->location?->directions_url ?? '')
                            ->openUrlInNewTab()
                            ->visible(fn ($record): bool => $record->location instanceof PhysicalLocation && filled($record->location->directions_url)),

                        // Online location fields
                        TextEntry::make('location.platform')
                            ->label('Platform')
                            ->badge()
                            ->visible(fn ($record): bool => $record->location instanceof OnlineLocation && filled($record->location->platform)),
                        TextEntry::make('location.url')
                            ->label('Meeting Link')
                            ->url(fn ($record): string => $record->location?->url ?? '')
                            ->openUrlInNewTab()
                            ->visible(fn ($record): bool => $record->location instanceof OnlineLocation),
                    ])
                    ->visible(fn ($record): bool => $record->location !== null)
                    ->columns(2),

                Section::make('Metadata')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->visible(fn ($record): bool => $record->deleted_at !== null),
                    ])
                    ->columns(3)
                    ->collapsible(),
            ]);
    }
}
