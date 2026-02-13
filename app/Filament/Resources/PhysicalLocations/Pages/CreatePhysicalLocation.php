<?php

declare(strict_types=1);

namespace App\Filament\Resources\PhysicalLocations\Pages;

use App\Filament\Resources\PhysicalLocations\PhysicalLocationResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePhysicalLocation extends CreateRecord
{
    protected static string $resource = PhysicalLocationResource::class;
}
