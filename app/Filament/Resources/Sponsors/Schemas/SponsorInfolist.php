<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sponsors\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SponsorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('website')
                    ->url(fn (?string $state): ?string => $state),
                ImageEntry::make('logo')
                    ->disk('public')
                    ->square()
                    ->size('12rem'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
