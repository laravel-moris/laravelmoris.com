<?php

declare(strict_types=1);

namespace App\Filament\Resources\PhysicalLocations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PhysicalLocationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('venue_name'),
                TextEntry::make('address'),
                TextEntry::make('city'),
                TextEntry::make('directions_url')
                    ->url(fn (?string $state): ?string => $state),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
