<?php

declare(strict_types=1);

namespace App\Filament\Resources\PhysicalLocations\Pages;

use App\Filament\Resources\PhysicalLocations\PhysicalLocationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPhysicalLocations extends ListRecords
{
    protected static string $resource = PhysicalLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
