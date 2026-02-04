<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('avatar')
                    ->label('Avatar')
                    ->imageSize(150),
                TextEntry::make('name'),
                TextEntry::make('email'),
                TextEntry::make('email_verified_at')
                    ->label('Email Verified')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
            ]);
    }
}
