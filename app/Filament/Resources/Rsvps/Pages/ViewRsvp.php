<?php

declare(strict_types=1);

namespace App\Filament\Resources\Rsvps\Pages;

use App\Filament\Resources\Rsvps\RsvpResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRsvp extends ViewRecord
{
    protected static string $resource = RsvpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
