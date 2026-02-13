<?php

declare(strict_types=1);

namespace App\Filament\Resources\Events\Schemas;

use App\Enums\EventLocation;
use App\Models\OnlineLocation;
use App\Models\PhysicalLocation;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('type')
                    ->options(collect(EventLocation::cases())->mapWithKeys(fn (EventLocation $type): array => [$type->value => $type->label()])->all())
                    ->default(EventLocation::Physical->value)
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Select $component) => $component
                        ->getContainer()
                        ->getComponent('dynamicLocationFields')
                        ->getChildSchema()
                        ->fill()),
                Grid::make(2)
                    ->schema(fn (Get $get): array => match ($get('type')) {
                        EventLocation::Physical->value => [
                            Hidden::make('location_type')
                                ->default(PhysicalLocation::class),
                            Select::make('location_id')
                                ->label('Venue')
                                ->options(PhysicalLocation::query()->pluck('venue_name', 'id'))
                                ->searchable()
                                ->preload()
                                ->required()
                                ->createOptionForm([
                                    TextInput::make('venue_name')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('address')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('city')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('directions_url')
                                        ->url()
                                        ->maxLength(255)
                                        ->prefixIcon('heroicon-m-map'),
                                ])
                                ->createOptionUsing(function (array $data): int {
                                    $location = PhysicalLocation::query()->create($data);

                                    return $location->getKey();
                                }),
                        ],
                        EventLocation::Online->value => [
                            Hidden::make('location_type')
                                ->default(OnlineLocation::class),
                            TextInput::make('online_platform')
                                ->label('Platform')
                                ->maxLength(255)
                                ->placeholder('e.g., Zoom, Google Meet, Teams'),
                            TextInput::make('meeting_url')
                                ->label('Meeting Link')
                                ->url()
                                ->required()
                                ->maxLength(255)
                                ->prefixIcon('heroicon-m-link')
                                ->placeholder('https://meet.google.com/...'),
                        ],
                        default => [],
                    })
                    ->key('dynamicLocationFields'),
                DateTimePicker::make('starts_at')
                    ->required()
                    ->native(false),
                DateTimePicker::make('ends_at')
                    ->required()
                    ->after('starts_at')
                    ->native(false),
            ]);
    }
}
