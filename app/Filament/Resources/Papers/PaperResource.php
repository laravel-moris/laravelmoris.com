<?php

declare(strict_types=1);

namespace App\Filament\Resources\Papers;

use App\Filament\Resources\Papers\Pages\CreatePaper;
use App\Filament\Resources\Papers\Pages\EditPaper;
use App\Filament\Resources\Papers\Pages\ListPapers;
use App\Filament\Resources\Papers\Pages\ViewPaper;
use App\Filament\Resources\Papers\RelationManagers\SpeakerRelationManager;
use App\Filament\Resources\Papers\Schemas\PaperForm;
use App\Filament\Resources\Papers\Schemas\PaperInfolist;
use App\Filament\Resources\Papers\Tables\PapersTable;
use App\Models\Paper;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class PaperResource extends Resource
{
    protected static ?string $model = Paper::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Community';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return PaperForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PaperInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PapersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SpeakerRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPapers::route('/'),
            'create' => CreatePaper::route('/create'),
            'edit' => EditPaper::route('/{record}/edit'),
            'view' => ViewPaper::route('/{record}'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
