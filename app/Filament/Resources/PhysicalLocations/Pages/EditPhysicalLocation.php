<?php

declare(strict_types=1);

namespace App\Filament\Resources\PhysicalLocations\Pages;

use App\Filament\Resources\PhysicalLocations\PhysicalLocationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPhysicalLocation extends EditRecord
{
    protected static string $resource = PhysicalLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
