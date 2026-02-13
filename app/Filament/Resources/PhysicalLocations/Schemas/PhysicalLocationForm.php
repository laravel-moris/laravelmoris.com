<?php

declare(strict_types=1);

namespace App\Filament\Resources\PhysicalLocations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PhysicalLocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('venue_name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., TechHub Coworking Space')
                    ->helperText('The name of the venue'),
                TextInput::make('address')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('123 Main Street, Port Louis')
                    ->helperText('The street address'),
                TextInput::make('city')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Port Louis')
                    ->helperText('The city where the venue is located'),
                TextInput::make('directions_url')
                    ->url()
                    ->maxLength(255)
                    ->prefixIcon('heroicon-m-map')
                    ->placeholder('https://maps.google.com/...')
                    ->helperText('Link to Google Maps or other directions')
                    ->columnSpanFull(),
            ]);
    }
}
