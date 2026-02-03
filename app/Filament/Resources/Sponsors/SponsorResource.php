<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sponsors;

use App\Filament\Resources\Sponsors\Pages\CreateSponsor;
use App\Filament\Resources\Sponsors\Pages\EditSponsor;
use App\Filament\Resources\Sponsors\Pages\ListSponsors;
use App\Filament\Resources\Sponsors\Pages\ViewSponsor;
use App\Filament\Resources\Sponsors\RelationManagers\EventsRelationManager;
use App\Filament\Resources\Sponsors\Schemas\SponsorForm;
use App\Filament\Resources\Sponsors\Schemas\SponsorInfolist;
use App\Filament\Resources\Sponsors\Tables\SponsorsTable;
use App\Models\Sponsor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SponsorResource extends Resource
{
    protected static ?string $model = Sponsor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static string|UnitEnum|null $navigationGroup = 'Community';

    protected static ?int $navigationSort = 30;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return SponsorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SponsorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SponsorsTable::configure($table);
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
            'index' => ListSponsors::route('/'),
            'create' => CreateSponsor::route('/create'),
            'edit' => EditSponsor::route('/{record}/edit'),
            'view' => ViewSponsor::route('/{record}'),
        ];
    }
}
