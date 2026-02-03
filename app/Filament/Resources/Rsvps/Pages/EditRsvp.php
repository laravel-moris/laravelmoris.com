<?php

declare(strict_types=1);

namespace App\Filament\Resources\Rsvps\Pages;

use App\Filament\Resources\Rsvps\RsvpResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRsvp extends EditRecord
{
    protected static string $resource = RsvpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
