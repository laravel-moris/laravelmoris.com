<?php

declare(strict_types=1);

namespace App\Filament\Resources\Events\RelationManagers;

use App\Actions\Event\ImportCsvAttendeesAction;
use App\Data\Event\ImportCsvAttendeesData;
use App\Enums\RsvpStatus;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttendeesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendees';

    protected static ?string $title = 'Attendees';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rsvp.status')
                    ->label('RSVP Status')
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
            ->headerActions([
                Action::make('importCsv')
                    ->label('Import Attendees')
                    ->icon('heroicon-m-arrow-up-tray')
                    ->schema([
                        Textarea::make('csv_data')
                            ->label('Paste CSV Data')
                            ->placeholder("name,email\nJohn Doe,john@example.com\nJane Smith,jane@example.com")
                            ->rows(10)
                            ->helperText('CSV format: name,email — one attendee per line. Header row is optional.')
                            ->required(),
                    ])
                    ->action(function (array $data, RelationManager $livewire, ImportCsvAttendeesAction $importCsvAttendeesAction): void {
                        $result = $importCsvAttendeesAction->execute(
                            $livewire->ownerRecord,
                            ImportCsvAttendeesData::from(['csvData' => $data['csv_data']]),
                        );

                        $message = 'Imported '.$result['imported'].' attendee(s).';

                        if ($result['skipped'] > 0) {
                            $message .= ' '.$result['skipped'].' skipped (already RSVP\'d).';
                        }

                        Notification::make()
                            ->title($message)
                            ->success()
                            ->send();

                        if ($result['errors'] !== []) {
                            Notification::make()
                                ->title('Import completed with errors')
                                ->body(implode("\n", array_slice($result['errors'], 0, 5)))
                                ->warning()
                                ->send();
                        }
                    }),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
