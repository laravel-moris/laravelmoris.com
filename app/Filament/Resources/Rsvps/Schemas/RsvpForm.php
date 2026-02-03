<?php

declare(strict_types=1);

namespace App\Filament\Resources\Rsvps\Schemas;

use App\Enums\RsvpStatus;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class RsvpForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('event_id')
                    ->label('Event')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabledOn(['edit', 'view']),
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'email')
                    ->getOptionLabelFromRecordUsing(function (User $record): string {
                        if (filled($record->name) && filled($record->email)) {
                            return "{$record->name} ({$record->email})";
                        }

                        if (filled($record->name)) {
                            return $record->name;
                        }

                        if (filled($record->email)) {
                            return $record->email;
                        }

                        return "User #{$record->getKey()}";
                    })
                    ->searchable(['name', 'email'])
                    ->preload()
                    ->required()
                    ->disabledOn(['edit', 'view']),
                Select::make('status')
                    ->options([
                        RsvpStatus::Going->value => RsvpStatus::Going->label(),
                        RsvpStatus::Maybe->value => RsvpStatus::Maybe->label(),
                    ])
                    ->default(RsvpStatus::Maybe->value)
                    ->required(),
            ]);
    }
}
