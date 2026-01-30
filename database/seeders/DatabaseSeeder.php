<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\EventLocation;
use App\Enums\PaperStatus;
use App\Models\Event;
use App\Models\OnlineLocation;
use App\Models\Paper;
use App\Models\PhysicalLocation;
use App\Models\User;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    /**
     * @throws FileNotFoundException
     * @throws ConnectionException
     */
    public function run(): void
    {
        $timezone = 'Indian/Mauritius';

        $owner = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'bio' => 'PHP Developer',
            'timezone' => $timezone,
        ]);

        $attendeesByEventId = $this->attendeesByEventId();

        $events = collect([
            [
                'title' => 'November 2025 - Laravel Moris Meetup',
                'description' => 'Final meetup of 2025. 3-4 technical talks (Laravel/Livewire/modern PHP), networking, and pizza. Free entry; RSVP required. Meetup: https://www.meetup.com/laravel-moris/events/312003794/',
                'starts_at' => '2025-11-29T10:00:00+04:00',
                'ends_at' => '2025-11-29T14:00:00+04:00',
                'url' => 'https://www.meetup.com/laravel-moris/events/312003794/',
                'type' => EventLocation::Physical,
                'venue_name' => 'Black Pirates Resto Pub',
                'address' => 'La Mivoie, MU, Royal Road, Black River 90101',
                'city' => 'Riviere Noire District',
                'directions_url' => 'https://maps.app.goo.gl/Lic6TGuFTpqNzDV26',
            ],
            [
                'title' => 'August 2025 - Laravel Moris Meetup',
                'description' => 'Community meetup with Laravel 12 highlights, real-world use cases, and a live coding demo. Networking. Bonus: certification voucher discount. Meetup: https://www.meetup.com/laravel-moris/events/309163628/',
                'starts_at' => '2025-08-23T10:00:00+04:00',
                'ends_at' => '2025-08-23T14:00:00+04:00',
                'url' => 'https://www.meetup.com/laravel-moris/events/309163628/',
                'type' => EventLocation::Physical,
                'venue_name' => 'Icon Ebene',
                'address' => "Office 3 Level 1, Lot B441 MU, Rue de L'Institut, ICONEBENE1 80817",
                'city' => 'Quatre Bornes',
                'directions_url' => 'https://maps.app.goo.gl/yXU7Yu1zaSpgpE9b9',
            ],
            [
                'title' => 'May 2025 - Laravel Moris Meetup',
                'description' => 'Community meetup with technical talks, live demo, and networking for Laravel/PHP developers of all levels. Bonus: certification voucher discount. Meetup: https://www.meetup.com/laravel-moris/events/307399690/',
                'starts_at' => '2025-05-31T10:00:00+04:00',
                'ends_at' => '2025-05-31T14:00:00+04:00',
                'url' => 'https://www.meetup.com/laravel-moris/events/307399690/',
                'type' => EventLocation::Physical,
                'venue_name' => 'African Leadership College Of Higher Education (ALCHE)',
                'address' => '21001, Powder Mill Road, Pamplemousses',
                'city' => 'Pamplemousses',
                'directions_url' => 'https://www.google.com/maps/search/?api=1&query=21001%2C%20Powder%20Mill%20Road%2C%20Pamplemousses%2C%20Pamplemousses',
            ],
            [
                'title' => 'March 2025 - Laravel Moris Meetup',
                'description' => 'Laravel Moris meetup focused on expert talks/demos, live coding, best practices, and networking. Meetup: https://www.meetup.com/laravel-moris/events/306376440/',
                'starts_at' => '2025-03-29T10:00:00+04:00',
                'ends_at' => '2025-03-29T14:00:00+04:00',
                'url' => 'https://www.meetup.com/laravel-moris/events/306376440/',
                'type' => EventLocation::Physical,
                'venue_name' => 'Workshop17 Vivea',
                'address' => 'MU, Rue des Fascines, 81406',
                'city' => 'Moka',
                'directions_url' => 'https://www.google.com/maps/search/?api=1&query=-20.22621%2C%2057.53534',
            ],
            [
                'title' => 'February 2025 - Laravel Moris Meetup',
                'description' => 'February edition: engaging talks, networking, Q&A, plus a quiz to win a Laravel certification voucher. Meetup: https://www.meetup.com/laravel-moris/events/305451793/',
                'starts_at' => '2025-02-15T10:00:00+04:00',
                'ends_at' => '2025-02-15T14:00:00+04:00',
                'url' => 'https://www.meetup.com/laravel-moris/events/305451793/',
                'type' => EventLocation::Physical,
                'venue_name' => 'Ringier',
                'address' => 'J9JJ+CMP Grande Riviere Noire, Mauritius',
                'city' => 'Riviere Noire District',
                'directions_url' => 'https://maps.app.goo.gl/7hTMynts3PWUMfBQA',
            ],
            [
                'title' => 'Laravel: Livewire, Reverb Integration, and API Mastery with Saloon',
                'description' => 'YouTube livestream with 3 talks: Livewire dynamic UIs (Ravish), Reverb + Livewire realtime (Bruno), and building APIs with Saloon (Percy). Meetup: https://www.meetup.com/laravel-moris/events/303073100/',
                'starts_at' => '2024-08-31T10:00:00+04:00',
                'ends_at' => '2024-08-31T13:00:00+04:00',
                'url' => 'https://www.meetup.com/laravel-moris/events/303073100/',
                'type' => EventLocation::Online,
            ],
        ]);

        $usersByName = collect();
        $attendeeCounter = 1;

        $events->each(function (array $payload) use (
            $timezone,
            $attendeesByEventId,
            &$attendeeCounter,
            $usersByName
        ) {
            $event = Event::create([
                'title' => $payload['title'],
                'description' => $payload['description'],
                'starts_at' => Carbon::parse($payload['starts_at']),
                'ends_at' => filled($payload['ends_at'] ?? null) ? Carbon::parse($payload['ends_at']) : null,
                'type' => $payload['type'],
            ]);

            $location = match ($payload['type']) {
                EventLocation::Online => OnlineLocation::create([
                    'platform' => 'meetup',
                    'url' => $payload['url'],
                ]),
                default => PhysicalLocation::create([
                    'venue_name' => $payload['venue_name'] ?? null,
                    'address' => $payload['address'],
                    'city' => $payload['city'],
                    'directions_url' => $payload['directions_url'],
                ]),
            };

            $event->location()->associate($location)->save();

            $eventId = $this->meetupEventId($payload['url'] ?? null);
            if (! $eventId) {
                return;
            }

            $pivotRows = collect($attendeesByEventId->get($eventId, []))
                ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
                ->mapWithKeys(/**
                 * @throws ConnectionException
                 */ function (array $attendee) use ($timezone, &$attendeeCounter, $usersByName) {
                    $name = $attendee['name'];

                    $avatarPath = $this->storeAvatarFromUrl($attendee['avatar'] ?? null);

                    /** @var User $user */
                    $user = $usersByName->get($name) ?? User::firstOrCreate(
                        ['name' => $name],
                        [
                            'email' => sprintf('attendee-%04d@meetup.com', $attendeeCounter++),
                            'avatar' => $avatarPath ?? null,
                            'timezone' => $timezone,
                            'password' => 'password',
                        ]
                    );

                    if (blank($user->avatar) && filled($avatarPath ?? null)) {
                        $user->forceFill(['avatar' => $avatarPath])->save();
                    }

                    $usersByName->put($name, $user);

                    return [$user->id => ['status' => 'confirmed']];
                })
                ->all();

            $event->attendees()->syncWithoutDetaching($pivotRows);
        });

        $firstEvent = Event::oldest('starts_at')->first();

        if ($firstEvent) {
            Paper::create([
                'user_id' => $owner->id,
                'event_id' => $firstEvent->id,
                'title' => 'Building APIs',
                'description' => 'How to build APIs',
                'status' => PaperStatus::Approved,
            ]);

            $firstEvent->attendees()->syncWithoutDetaching([
                $owner->id => ['status' => 'confirmed'],
            ]);
        }
    }

    private function meetupEventId(?string $url): ?string
    {
        $path = parse_url((string) $url, PHP_URL_PATH) ?: '';

        // /laravel-moris/events/312003794/ -> 312003794
        return Str::of($path)->match('~/events/(\d+)/~')->value() ?: null;
    }

    /**
     * @throws FileNotFoundException
     */
    private function attendeesByEventId()
    {
        $path = database_path('seeders/data/attendees.csv');

        if (! File::exists($path)) {
            return collect();
        }

        $lines = File::lines($path)
            ->map(fn (string $line) => str_getcsv($line))
            ->values();

        $header = $lines->first() ?? [];
        $rows = $lines->skip(1);

        $indexes = collect($header)
            ->map(fn ($h) => trim((string) $h))
            ->flip();

        if (! $indexes->has(['event_id', 'name', 'avatar_url'])) {
            throw new RuntimeException('Attendees csv has invalid header: '.$path);
        }

        return $rows
            ->map(fn (array $row) => [
                'event_id' => trim((string) ($row[$indexes['event_id']] ?? '')),
                'name' => trim((string) ($row[$indexes['name']] ?? '')),
                'avatar' => ($v = trim((string) ($row[$indexes['avatar_url']] ?? ''))) !== '' ? $v : null,
            ])
            ->filter(fn (array $r) => filled($r['event_id']) && filled($r['name']))
            ->groupBy('event_id')
            ->map(fn ($items) => $items->map(fn ($r) => [
                'name' => $r['name'],
                'avatar' => $r['avatar'],
            ])->values()->all());
    }

    /**
     * @throws ConnectionException
     */
    private function storeAvatarFromUrl(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        $disk = Storage::disk('public');

        /** @var Response $response */
        $response = Http::timeout(10)->retry(2, 200)->get($url);

        if (! $response->successful()) {
            return null;
        }

        $path = 'avatars/'.sha1($url).'.jpeg';

        if (! $disk->exists($path)) {
            $disk->put($path, $response->body());
        }

        return $path;
    }
}
