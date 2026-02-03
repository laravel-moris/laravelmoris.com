<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sponsors\Schemas;

use App\Actions\Sponsor\StoreSponsorLogo;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

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
                    ->saveUploadedFileUsing(function ($file, StoreSponsorLogo $storeSponsorLogo) {
                        $content = $file->getContent();
                        $extension = $file->getClientOriginalExtension();

                        $path = $storeSponsorLogo->execute($content, $extension);

                        if ($path === null) {
                            return null;
                        }

                        // Clean up temp file
                        Storage::disk('public')->deleteDirectory('sponsors-logos-temp');

                        return $path;
                    })
                    ->deleteUploadedFileUsing(function ($file) {
                        if ($file !== null) {
                            Storage::disk('public')->delete($file);
                        }
                    })
                    ->previewable(true),
            ]);
    }
}
