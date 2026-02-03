<?php

declare(strict_types=1);

namespace App\Filament\Resources\Rsvps\Pages;

use App\Filament\Resources\Rsvps\RsvpResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRsvps extends ListRecords
{
    protected static string $resource = RsvpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
