<?php

declare(strict_types=1);

namespace App\Filament\Resources\Events\RelationManagers;

use App\Enums\RsvpStatus;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RsvpsRelationManager extends RelationManager
{
    protected static string $relationship = 'attendees';

    protected static ?string $title = 'RSVPs';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('User')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rsvp.status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(function (RsvpStatus|string|null $state): string {
                        if ($state instanceof RsvpStatus) {
                            return $state->label();
                        }

                        return RsvpStatus::tryFrom((string) $state)?->label() ?? (string) $state;
                    })
                    ->color(function (RsvpStatus|string|null $state): string {
                        $status = $state instanceof RsvpStatus
                            ? $state
                            : RsvpStatus::tryFrom((string) $state);

                        return match ($status) {
                            RsvpStatus::Going => 'success',
                            RsvpStatus::Maybe => 'warning',
                            RsvpStatus::NotGoing => 'danger',
                            default => 'gray',
                        };
                    }),
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
