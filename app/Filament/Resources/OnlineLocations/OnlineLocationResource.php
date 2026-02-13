<?php

declare(strict_types=1);

namespace App\Filament\Resources\OnlineLocations;

use App\Filament\Resources\OnlineLocations\Pages\CreateOnlineLocation;
use App\Filament\Resources\OnlineLocations\Pages\EditOnlineLocation;
use App\Filament\Resources\OnlineLocations\Pages\ListOnlineLocations;
use App\Filament\Resources\OnlineLocations\Pages\ViewOnlineLocation;
use App\Filament\Resources\OnlineLocations\RelationManagers\EventsRelationManager;
use App\Filament\Resources\OnlineLocations\Schemas\OnlineLocationForm;
use App\Filament\Resources\OnlineLocations\Schemas\OnlineLocationInfolist;
use App\Filament\Resources\OnlineLocations\Tables\OnlineLocationsTable;
use App\Models\OnlineLocation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class OnlineLocationResource extends Resource
{
    protected static ?string $model = OnlineLocation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static string|UnitEnum|null $navigationGroup = 'Locations';

    protected static ?int $navigationSort = 40;

    protected static ?string $recordTitleAttribute = 'platform';

    public static function form(Schema $schema): Schema
    {
        return OnlineLocationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OnlineLocationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OnlineLocationsTable::configure($table);
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
            'index' => ListOnlineLocations::route('/'),
            'create' => CreateOnlineLocation::route('/create'),
            'edit' => EditOnlineLocation::route('/{record}/edit'),
            'view' => ViewOnlineLocation::route('/{record}'),
        ];
    }
}
