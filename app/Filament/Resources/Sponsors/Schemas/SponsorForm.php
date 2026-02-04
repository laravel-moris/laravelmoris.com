<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sponsors\Schemas;

use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SponsorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('website')
                    ->url()
                    ->maxLength(255)
                    ->prefixIcon('heroicon-m-globe-alt'),
                FileUpload::make('logo')
                    ->disk('public')
                    ->directory('sponsors-logos-temp')
                    ->image()
                    ->imageEditor()
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/svg+xml'])
                    ->storeFiles(false)
                    ->dehydrated(false)
                    ->deleteUploadedFileUsing(function ($file) {
                        if ($file !== null) {
                            Storage::disk('public')->delete($file);
                        }
                    })
                    ->previewable(true),
            ]);
    }
}
