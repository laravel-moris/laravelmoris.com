<?php

declare(strict_types=1);

namespace App\Filament\Resources\Rsvps;

use App\Filament\Resources\Rsvps\Pages\CreateRsvp;
use App\Filament\Resources\Rsvps\Pages\EditRsvp;
use App\Filament\Resources\Rsvps\Pages\ListRsvps;
use App\Filament\Resources\Rsvps\Pages\ViewRsvp;
use App\Filament\Resources\Rsvps\Schemas\RsvpForm;
use App\Filament\Resources\Rsvps\Schemas\RsvpInfolist;
use App\Filament\Resources\Rsvps\Tables\RsvpsTable;
use App\Models\Rsvp;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RsvpResource extends Resource
{
    protected static ?string $model = Rsvp::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'RSVPs';

    protected static string|UnitEnum|null $navigationGroup = 'Community';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return RsvpForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RsvpInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RsvpsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRsvps::route('/'),
            'create' => CreateRsvp::route('/create'),
            'view' => ViewRsvp::route('/{record}'),
            'edit' => EditRsvp::route('/{record}/edit'),
        ];
    }
}
