<?php

declare(strict_types=1);

namespace App\Filament\Resources\Events\Schemas;

use App\Enums\EventLocation;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
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
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->dateTime(),
            ]);
    }
}
