<?php

declare(strict_types=1);

namespace App\Actions\Event;

use App\Data\Event\ImportCsvAttendeesData;
use App\Enums\RsvpStatus;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Str;

final readonly class ImportCsvAttendeesAction
{
    /**
     * @return array{imported: int, skipped: int, errors: list<string>}
     */
    public function execute(Event $event, ImportCsvAttendeesData $data): array
    {
        $lines = explode("\n", trim($data->csvData));
        $imported = 0;
        $skipped = 0;
        $errors = [];

        foreach ($lines as $index => $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $parts = str_getcsv($line);

            if ($index === 0 && $this->isHeaderRow($parts)) {
                continue;
            }

            if (count($parts) < 1 || trim($parts[0]) === '') {
                $errors[] = 'Line '.($index + 1).': missing name';

                continue;
            }

            $name = trim($parts[0]);
            $email = isset($parts[1]) ? trim($parts[1]) : null;

            if ($email !== null && $email !== '') {
                $user = User::query()->firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $name,
                        'password' => bcrypt(Str::random(32)),
                    ],
                );
            } else {
                $email = Str::slug($name).'@attendee.laravelmoris.com';
                $counter = 1;

                while (User::query()->where('email', $email)->exists()) {
                    $email = Str::slug($name).'-'.$counter.'@attendee.laravelmoris.com';
                    $counter++;
                }

                $user = User::query()->create([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt(Str::random(32)),
                ]);
            }

            if ($event->attendees()->where('user_id', $user->id)->exists()) {
                $skipped++;

                continue;
            }

            $event->attendees()->attach($user->id, ['status' => RsvpStatus::Going->value]);
            $imported++;
        }

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'errors' => $errors,
        ];
    }

    /**
     * @param  list<string>  $parts
     */
    private function isHeaderRow(array $parts): bool
    {
        if (count($parts) < 2) {
            return false;
        }

        return Str::lower(trim($parts[0])) === 'name' && Str::lower(trim($parts[1])) === 'email';
    }
}
