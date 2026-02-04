<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('avatar')
                    ->storeFiles(false)
                    ->image()
                    ->imageEditor()
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                    ->helperText('Upload an avatar for the user. Supported formats: JPEG, PNG, WebP, SVG.')
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('title')
                    ->maxLength(255)
                    ->nullable(),
                TextInput::make('bio')
                    ->maxLength(500)
                    ->nullable()
                    ->columnSpanFull(),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->maxLength(255)
                    ->nullable(),
            ]);
    }
}
