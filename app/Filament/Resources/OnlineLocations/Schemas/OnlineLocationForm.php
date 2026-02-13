<?php

declare(strict_types=1);

namespace App\Filament\Resources\OnlineLocations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OnlineLocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('platform')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., Zoom, Google Meet, Microsoft Teams')
                    ->helperText('The name of the online meeting platform'),
                TextInput::make('url')
                    ->required()
                    ->url()
                    ->maxLength(255)
                    ->prefixIcon('heroicon-m-link')
                    ->placeholder('https://meet.google.com/abc-defg-hij')
                    ->helperText('The full meeting URL')
                    ->columnSpanFull(),
            ]);
    }
}
