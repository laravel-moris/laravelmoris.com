<?php

declare(strict_types=1);

namespace App\Filament\Resources\Papers\Schemas;

use App\Enums\PaperStatus;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaperForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Speaker')
                    ->relationship('speaker', 'name')
                    ->getOptionLabelFromRecordUsing(fn (User $record): string => "{$record->name} ({$record->email})")
                    ->searchable(['name', 'email'])
                    ->preload()
                    ->required(),
                Select::make('event_id')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('status')
                    ->options(collect(PaperStatus::cases())->mapWithKeys(fn (PaperStatus $status): array => [$status->value => $status->label()])->all())
                    ->default(PaperStatus::Draft->value)
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
