<?php

declare(strict_types=1);

namespace App\Filament\Resources\OnlineLocations\Pages;

use App\Filament\Resources\OnlineLocations\OnlineLocationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOnlineLocation extends ViewRecord
{
    protected static string $resource = OnlineLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
