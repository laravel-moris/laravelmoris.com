<?php

declare(strict_types=1);

namespace App\Filament\Resources\OnlineLocations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OnlineLocationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('platform'),
                TextEntry::make('url')
                    ->url(fn (?string $state): ?string => $state),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
