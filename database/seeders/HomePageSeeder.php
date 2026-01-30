<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\EventLocation;
use App\Enums\PaperStatus;
use App\Models\Event;
use App\Models\OnlineLocation;
use App\Models\Paper;
use App\Models\PhysicalLocation;
use App\Models\Sponsor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class HomePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cache::forget('homepage:happening-now');

        $timezone = 'Indian/Mauritius';

        $avatarContent = File::get(database_path('seeders/data/john-cena.png'));
        $disk = Storage::disk('public');

        $speakers = collect([
            User::factory()->create([
                'name' => 'Jane Developer',
                'email' => 'jane@example.com',
                'title' => 'Senior Laravel Developer',
                'bio' => 'Building Laravel apps in production.',
                'timezone' => $timezone,
            ]),
            User::factory()->create([
                'name' => 'Sam Backend',
                'email' => 'sam@example.com',
                'title' => 'Backend Engineer',
                'bio' => 'APIs, queues, and performance.',
                'timezone' => $timezone,
            ]),
            User::factory()->create([
                'name' => 'Alex Fullstack',
                'email' => 'alex@example.com',
                'title' => 'Full-stack Developer',
                'bio' => 'UI + API, shipped weekly.',
                'timezone' => $timezone,
            ]),
            User::factory()->create([
                'name' => 'Chris Speaker',
                'email' => 'chris@example.com',
                'title' => 'Community Speaker',
                'bio' => 'Talks about testing and DX.',
                'timezone' => $timezone,
            ]),
            User::factory()->create([
                'name' => 'Pat Online',
                'email' => 'pat@example.com',
                'title' => 'Developer Advocate',
                'bio' => 'Ships content and demos.',
                'timezone' => $timezone,
            ]),
            User::factory()->create([
                'name' => 'Taylorish',
                'email' => 'taylorish@example.com',
                'title' => 'Engineering Lead',
                'bio' => 'Building teams and systems.',
                'timezone' => $timezone,
            ]),
        ]);

        // 50/50 avatar strategy: some users get stored avatar paths, some remain null.
        foreach ([0, 2, 4] as $index) {
            /** @var User $user */
            $user = $speakers[$index];
            $path = sprintf('avatars/speaker-%d.png', $user->id);
            $disk->put($path, $avatarContent);
            $user->forceFill(['avatar' => $path])->save();
        }

        $now = now()->timezone($timezone);

        // Happening now (live) - online.
        $liveEvent = $this->createEventWithLocation([
            'title' => 'Laravel Moris Live: Hot Seat Q&A',
            'description' => 'Live session happening right now: quick talks + Q&A.',
            'starts_at' => $now->copy()->subHours(2)->utc(),
            'ends_at' => $now->copy()->addHours(10)->utc(),
            'type' => EventLocation::Online,
            'online' => [
                'platform' => 'meetup',
                'url' => 'https://www.meetup.com/laravel-moris/',
            ],
        ]);
        $this->attachApprovedSpeakers($liveEvent, $speakers->shuffle()->take(2)->values());

        // Upcoming events (mix physical + online).
        $upcomingEvents = collect([
            [
                'title' => 'Laravel Moris February 2026 Meetup',
                'description' => 'Monthly meetup with 3 talks, networking.',
                'starts_at' => Carbon::parse($now->copy()->addDays(10)->setTime(10, 0))->utc(),
                'ends_at' => Carbon::parse($now->copy()->addDays(10)->setTime(14, 0))->utc(),
                'type' => EventLocation::Physical,
                'physical' => [
                    'venue_name' => 'Workshop17 Vivea',
                    'address' => 'Rue des Fascines, 81406',
                    'city' => 'Moka',
                    'directions_url' => 'https://www.google.com/maps/search/?api=1&query=-20.22621%2C%2057.53534',
                ],
            ],
            [
                'title' => 'Laravel Moris Online: Testing Night',
                'description' => 'Online meetup focused on Pest and testing patterns.',
                'starts_at' => Carbon::parse($now->copy()->addDays(20)->setTime(19, 0))->utc(),
                'ends_at' => Carbon::parse($now->copy()->addDays(20)->setTime(21, 0))->utc(),
                'type' => EventLocation::Online,
                'online' => [
                    'platform' => 'meetup',
                    'url' => 'https://www.meetup.com/laravel-moris/',
                ],
            ],
            [
                'title' => 'Laravel Moris March 2026 Meetup',
                'description' => 'Community meetup with 2-3 technical talks.',
                'starts_at' => Carbon::parse($now->copy()->addDays(35)->setTime(10, 0))->utc(),
                'ends_at' => Carbon::parse($now->copy()->addDays(35)->setTime(14, 0))->utc(),
                'type' => EventLocation::Physical,
                'physical' => [
                    'venue_name' => 'Icon Ebene',
                    'address' => "Rue de L'Institut, Ebene",
                    'city' => 'Quatre Bornes',
                    'directions_url' => 'https://maps.app.goo.gl/yXU7Yu1zaSpgpE9b9',
                ],
            ],
            [
                'title' => 'Laravel Moris Online: Livewire Patterns',
                'description' => 'Livewire architecture, components, and testing.',
                'starts_at' => Carbon::parse($now->copy()->addDays(50)->setTime(19, 0))->utc(),
                'ends_at' => Carbon::parse($now->copy()->addDays(50)->setTime(21, 0))->utc(),
                'type' => EventLocation::Online,
                'online' => [
                    'platform' => 'meetup',
                    'url' => 'https://www.meetup.com/laravel-moris/',
                ],
            ],
        ])->map(fn (array $payload) => $this->createEventWithLocation($payload));

        foreach ($upcomingEvents as $event) {
            $this->attachApprovedSpeakers($event, $speakers->shuffle()->take(2)->values());
        }

        // Past events (for grid filler).
        $pastEvents = collect([
            [
                'title' => 'Laravel Moris November 2025 Meetup',
                'description' => 'End-of-year meetup with talks and networking.',
                'starts_at' => Carbon::parse($now->copy()->subDays(90)->setTime(10, 0))->utc(),
                'ends_at' => Carbon::parse($now->copy()->subDays(90)->setTime(14, 0))->utc(),
                'type' => EventLocation::Physical,
                'physical' => [
                    'venue_name' => 'Black Pirates Resto Pub',
                    'address' => 'Royal Road, Black River 90101',
                    'city' => 'Riviere Noire District',
                    'directions_url' => 'https://maps.app.goo.gl/Lic6TGuFTpqNzDV26',
                ],
            ],
            [
                'title' => 'Laravel Moris August 2025 Meetup',
                'description' => 'Laravel updates + live demo.',
                'starts_at' => Carbon::parse($now->copy()->subDays(150)->setTime(10, 0))->utc(),
                'ends_at' => Carbon::parse($now->copy()->subDays(150)->setTime(14, 0))->utc(),
                'type' => EventLocation::Physical,
                'physical' => [
                    'venue_name' => 'African Leadership College (ALCHE)',
                    'address' => 'Powder Mill Road, Pamplemousses',
                    'city' => 'Pamplemousses',
                    'directions_url' => 'https://www.google.com/maps/search/?api=1&query=Powder%20Mill%20Road%2C%20Pamplemousses',
                ],
            ],
            [
                'title' => 'Laravel Moris Online: API Mastery',
                'description' => 'Online session about building APIs.',
                'starts_at' => Carbon::parse($now->copy()->subDays(200)->setTime(19, 0))->utc(),
                'ends_at' => Carbon::parse($now->copy()->subDays(200)->setTime(21, 0))->utc(),
                'type' => EventLocation::Online,
                'online' => [
                    'platform' => 'meetup',
                    'url' => 'https://www.meetup.com/laravel-moris/',
                ],
            ],
        ])->map(fn (array $payload) => $this->createEventWithLocation($payload));

        foreach ($pastEvents as $event) {
            $this->attachApprovedSpeakers($event, $speakers->shuffle()->take(2)->values());
        }

        $this->seedSponsorsForPastEvents($pastEvents);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Event>  $pastEvents
     */
    private function seedSponsorsForPastEvents($pastEvents): void
    {
        $disk = Storage::disk('public');
        $logosPath = database_path('seeders/data/logos');

        $sponsorsConfig = [
            ['name' => 'ESOKIA', 'slug' => 'esokia', 'website' => 'https://esokia.com'],
            ['name' => 'ALCHE', 'slug' => 'alche', 'website' => 'https://alcheducation.com'],
            ['name' => 'NATIVEPHP', 'slug' => 'nativephp', 'website' => 'https://nativephp.com'],
            ['name' => 'RINGIER', 'slug' => 'ringier', 'website' => 'https://www.ringier.com'],
            ['name' => 'BLACK PIRATES', 'slug' => 'black-pirates', 'website' => 'https://www.blackpiratesresto.com'],
            ['name' => 'LARAVEL', 'slug' => 'laravel', 'website' => 'https://laravel.com'],
            ['name' => 'CERTIFICATION', 'slug' => 'certification', 'website' => 'https://www.certificationforlaravel.com'],
        ];

        /** @var array<string, Sponsor> $sponsors */
        $sponsors = [];

        foreach ($sponsorsConfig as $config) {
            // Try PNG first, then SVG
            $pngFile = "{$logosPath}/{$config['slug']}.png";
            $svgFile = "{$logosPath}/{$config['slug']}.svg";

            $logoPath = null;
            if (File::exists($pngFile)) {
                $logoPath = "sponsors/{$config['slug']}.png";
                $disk->put($logoPath, File::get($pngFile));
            } elseif (File::exists($svgFile)) {
                $logoPath = "sponsors/{$config['slug']}.svg";
                $disk->put($logoPath, File::get($svgFile));
            }

            $sponsors[$config['name']] = Sponsor::create([
                'name' => $config['name'],
                'logo' => $logoPath,
                'website' => $config['website'],
            ]);
        }

        $november = $pastEvents->firstWhere('title', 'Laravel Moris November 2025 Meetup');
        $august = $pastEvents->firstWhere('title', 'Laravel Moris August 2025 Meetup');
        $api = $pastEvents->firstWhere('title', 'Laravel Moris Online: API Mastery');

        if ($november) {
            $november->sponsors()->syncWithoutDetaching([
                $sponsors['BLACK PIRATES']->id,
                $sponsors['LARAVEL']->id,
            ]);
        }

        if ($august) {
            $august->sponsors()->syncWithoutDetaching([
                $sponsors['ESOKIA']->id,
                $sponsors['RINGIER']->id,
            ]);
        }

        if ($api) {
            $api->sponsors()->syncWithoutDetaching([
                $sponsors['ALCHE']->id,
                $sponsors['NATIVEPHP']->id,
                $sponsors['CERTIFICATION']->id,
            ]);
        }
    }

    private function createEventWithLocation(array $payload): Event
    {
        $event = Event::create([
            'title' => $payload['title'],
            'description' => $payload['description'] ?? null,
            'starts_at' => $payload['starts_at'],
            'ends_at' => $payload['ends_at'],
            'type' => $payload['type'],
        ]);

        $location = match ($payload['type']) {
            EventLocation::Online => OnlineLocation::create([
                'platform' => $payload['online']['platform'] ?? null,
                'url' => $payload['online']['url'] ?? null,
            ]),
            EventLocation::Physical => PhysicalLocation::create([
                'venue_name' => $payload['physical']['venue_name'] ?? null,
                'address' => $payload['physical']['address'] ?? null,
                'city' => $payload['physical']['city'] ?? null,
                'directions_url' => $payload['physical']['directions_url'] ?? null,
            ]),
        };

        $event->location()->associate($location)->save();

        return $event;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, User>  $speakers
     */
    private function attachApprovedSpeakers(Event $event, $speakers): void
    {
        foreach ($speakers as $speaker) {
            Paper::create([
                'user_id' => $speaker->id,
                'event_id' => $event->id,
                'title' => 'Talk: '.fake()->sentence(4),
                'description' => fake()->paragraph(),
                'status' => PaperStatus::Approved,
            ]);
        }
    }
}
