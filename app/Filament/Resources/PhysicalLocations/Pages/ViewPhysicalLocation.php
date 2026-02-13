<?php

declare(strict_types=1);

namespace App\Filament\Resources\PhysicalLocations\Pages;

use App\Filament\Resources\PhysicalLocations\PhysicalLocationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPhysicalLocation extends ViewRecord
{
    protected static string $resource = PhysicalLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
