<?php

declare(strict_types=1);

namespace App\Filament\Resources\Papers\Schemas;

use App\Enums\PaperStatus;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaperInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()
                    ->columns(2)
                    ->schema([
                        Section::make('Paper Details')
                            ->schema([
                                TextEntry::make('title')
                                    ->label('Paper Title'),
                                TextEntry::make('description')
                                    ->label('Description'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (PaperStatus|string|null $state): ?string => $state instanceof PaperStatus ? $state->color() : PaperStatus::tryFrom((string) $state)?->color()),
                                TextEntry::make('event.title')
                                    ->label('Event'),
                            ]),

                        Section::make('Speaker Information')
                            ->schema([
                                ImageEntry::make('speaker.avatar')
                                    ->label('Photo')
                                    ->disk('public')
                                    ->circular()
                                    ->imageSize('8rem'),
                                TextEntry::make('speaker.name')
                                    ->label('Name'),
                                TextEntry::make('speaker.title')
                                    ->label('Title'),
                                TextEntry::make('speaker.email')
                                    ->label('Email'),
                                TextEntry::make('speaker.secondary_email')
                                    ->label('Secondary Email'),
                                TextEntry::make('speaker.phone')
                                    ->label('Phone'),
                            ]),
                    ]),
            ]);
    }
}
