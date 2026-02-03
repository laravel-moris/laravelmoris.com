<?php

declare(strict_types=1);

namespace App\Filament\Resources\Rsvps\Schemas;

use App\Enums\RsvpStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RsvpInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('event.title')
                    ->label('Event'),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('status')
                    ->badge()
                    ->formatStateUsing(fn (RsvpStatus|string|null $state): string => $state instanceof RsvpStatus
                        ? $state->label()
                        : (RsvpStatus::tryFrom((string) $state)?->label() ?? (string) $state))
                    ->color(fn (RsvpStatus|string|null $state): string => match ($state instanceof RsvpStatus ? $state : RsvpStatus::tryFrom((string) $state)) {
                        RsvpStatus::Going => 'success',
                        RsvpStatus::Maybe => 'warning',
                        RsvpStatus::NotGoing => 'danger',
                        default => 'gray',
                    }),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
