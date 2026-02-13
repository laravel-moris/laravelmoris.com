<?php

declare(strict_types=1);

namespace App\Filament\Resources\OnlineLocations\Pages;

use App\Filament\Resources\OnlineLocations\OnlineLocationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOnlineLocation extends EditRecord
{
    protected static string $resource = OnlineLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
