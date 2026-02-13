<?php

declare(strict_types=1);

namespace App\Filament\Resources\OnlineLocations\Pages;

use App\Filament\Resources\OnlineLocations\OnlineLocationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOnlineLocation extends CreateRecord
{
    protected static string $resource = OnlineLocationResource::class;
}
