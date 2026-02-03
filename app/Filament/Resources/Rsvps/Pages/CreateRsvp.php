<?php

declare(strict_types=1);

namespace App\Filament\Resources\Rsvps\Pages;

use App\Filament\Resources\Rsvps\RsvpResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRsvp extends CreateRecord
{
    protected static string $resource = RsvpResource::class;
}
