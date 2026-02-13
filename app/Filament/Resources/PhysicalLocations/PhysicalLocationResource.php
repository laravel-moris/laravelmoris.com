<?php

declare(strict_types=1);

namespace App\Filament\Resources\PhysicalLocations;

use App\Filament\Resources\PhysicalLocations\Pages\CreatePhysicalLocation;
use App\Filament\Resources\PhysicalLocations\Pages\EditPhysicalLocation;
use App\Filament\Resources\PhysicalLocations\Pages\ListPhysicalLocations;
use App\Filament\Resources\PhysicalLocations\Pages\ViewPhysicalLocation;
use App\Filament\Resources\PhysicalLocations\RelationManagers\EventsRelationManager;
use App\Filament\Resources\PhysicalLocations\Schemas\PhysicalLocationForm;
use App\Filament\Resources\PhysicalLocations\Schemas\PhysicalLocationInfolist;
use App\Filament\Resources\PhysicalLocations\Tables\PhysicalLocationsTable;
use App\Models\PhysicalLocation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PhysicalLocationResource extends Resource
{
    protected static ?string $model = PhysicalLocation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|UnitEnum|null $navigationGroup = 'Locations';

    protected static ?int $navigationSort = 41;

    protected static ?string $recordTitleAttribute = 'venue_name';

    public static function form(Schema $schema): Schema
    {
        return PhysicalLocationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PhysicalLocationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PhysicalLocationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            EventsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPhysicalLocations::route('/'),
            'create' => CreatePhysicalLocation::route('/create'),
            'edit' => EditPhysicalLocation::route('/{record}/edit'),
            'view' => ViewPhysicalLocation::route('/{record}'),
        ];
    }
}
