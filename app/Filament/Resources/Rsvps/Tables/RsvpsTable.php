<?php

declare(strict_types=1);

namespace App\Filament\Resources\Rsvps\Tables;

use App\Enums\RsvpStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RsvpsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultKeySort(false)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
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
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('event')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->options([
                        RsvpStatus::Going->value => RsvpStatus::Going->label(),
                        RsvpStatus::Maybe->value => RsvpStatus::Maybe->label(),
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
