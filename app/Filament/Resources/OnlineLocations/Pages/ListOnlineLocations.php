<?php

declare(strict_types=1);

namespace App\Filament\Resources\OnlineLocations\Pages;

use App\Filament\Resources\OnlineLocations\OnlineLocationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOnlineLocations extends ListRecords
{
    protected static string $resource = OnlineLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
