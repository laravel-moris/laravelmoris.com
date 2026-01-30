<?php

declare(strict_types=1);

use App\Data\EventData;
use App\Data\EventLocationData;
use App\Enums\EventLocation;
use App\Models\Event;
use App\Models\OnlineLocation;
use App\Models\PhysicalLocation;
use TimoKoerber\LaravelOneTimeOperations\OneTimeOperation;

return new class extends OneTimeOperation
{
    /**
     * Determine if the operation is being processed asynchronously.
     */
    protected bool $async = false;

    /**
     * The queue that the job will be dispatched to.
     */
    protected string $queue = 'default';

    /**
     * A tag name, that this operation can be filtered by.
     */
    protected ?string $tag = null;

    /**
     * Process the operation.
     */
    public function process(): void
    {
        collect([
            EventData::make(
                title: 'February 2026 - Laravel Moris Meetup',
                date: '2026-02-28',
                startsAt: '10:00',
                endsAt: '14:00',
                timezone: '+04:00',
                description: 'First meetup of 2026. 3 technical talks, networking, and pizza. Free entry; RSVP required.',
                type: EventLocation::Physical,
                location: new EventLocationData(
                    class: PhysicalLocation::class,
                    attributes: [
                        'venue_name' => 'Spoon Consulting',
                        'address' => 'Vivéa Business Park, 1st FLoor, Mountain View Building',
                        'city' => 'St Pierre',
                        'directions_url' => 'https://maps.app.goo.gl/sFPTjoom1xkfrp2E6',
                    ]
                )
            ),
            EventData::make(
                title: 'November 2025 - Laravel Moris Meetup',
                date: '2025-11-29',
                startsAt: '10:00',
                endsAt: '14:00',
                timezone: '+04:00',
                description: 'Final meetup of 2025. 4 technical talks (Laravel/Livewire/modern PHP), networking, and pizza. Free entry; RSVP required. Meetup: https://www.meetup.com/laravel-moris/events/312003794/',
                type: EventLocation::Physical,
                location: new EventLocationData(
                    class: PhysicalLocation::class,
                    attributes: [
                        'venue_name' => 'Black Pirates Resto Pub',
                        'address' => 'La Mivoie, MU, Royal Road, Black River 90101',
                        'city' => 'Riviere Noire',
                        'directions_url' => 'https://maps.app.goo.gl/Lic6TGuFTpqNzDV26',
                    ]
                )
            ),
            EventData::make(
                title: 'August 2025 - Laravel Moris Meetup',
                date: '2025-08-23',
                startsAt: '10:00',
                endsAt: '14:00',
                timezone: '+04:00',
                description: 'Community meetup with Laravel 12 highlights, real-world use cases, and a live coding demo. Networking. Bonus: certification voucher discount. Meetup: https://www.meetup.com/laravel-moris/events/309163628/',
                type: EventLocation::Physical,
                location: new EventLocationData(
                    class: PhysicalLocation::class,
                    attributes: [
                        'venue_name' => 'Icon Ebene',
                        'address' => "Office 3 Level 1, Lot B441 MU, Rue de L'Institut, ICONEBENE1 80817",
                        'city' => 'Quatre Bornes',
                        'directions_url' => 'https://maps.app.goo.gl/yXU7Yu1zaSpgpE9b9',
                    ]
                )
            ),
            EventData::make(
                title: 'May 2025 - Laravel Moris Meetup',
                date: '2025-05-31',
                startsAt: '10:00',
                endsAt: '14:00',
                timezone: '+04:00',
                description: 'Community meetup with technical talks, live demo, and networking for Laravel/PHP developers of all levels. Bonus: certification voucher discount. Meetup: https://www.meetup.com/laravel-moris/events/307399690/',
                type: EventLocation::Physical,
                location: new EventLocationData(
                    class: PhysicalLocation::class,
                    attributes: [
                        'venue_name' => 'African Leadership College Of Higher Education (ALCHE)',
                        'address' => '21001, Powder Mill Road, Pamplemousses',
                        'city' => 'Pamplemousses',
                        'directions_url' => 'https://www.google.com/maps/search/?api=1&query=21001%2C%20Powder%20Mill%20Road%2C%20Pamplemousses%2C%20Pamplemousses',
                    ]
                )
            ),
            EventData::make(
                title: 'March 2025 - Laravel Moris Meetup',
                date: '2025-03-29',
                startsAt: '10:00',
                endsAt: '14:00',
                timezone: '+04:00',
                description: 'Laravel Moris meetup focused on expert talks/demos, live coding, best practices, and networking. Meetup: https://www.meetup.com/laravel-moris/events/306376440/',
                type: EventLocation::Physical,
                location: new EventLocationData(
                    class: PhysicalLocation::class,
                    attributes: [
                        'venue_name' => 'Workshop17 Vivea',
                        'address' => 'MU, Rue des Fascines, 81406',
                        'city' => 'Moka',
                        'directions_url' => 'https://www.google.com/maps/search/?api=1&query=-20.22621%2C%2057.53534',
                    ]
                )
            ),
            EventData::make(
                title: 'February 2025 - Laravel Moris Meetup',
                date: '2025-02-15',
                startsAt: '10:00',
                endsAt: '14:00',
                timezone: '+04:00',
                description: 'February edition: engaging talks, networking, Q&A, plus a quiz to win a Laravel certification voucher. Meetup: https://www.meetup.com/laravel-moris/events/305451793/',
                type: EventLocation::Physical,
                location: new EventLocationData(
                    class: PhysicalLocation::class,
                    attributes: [
                        'venue_name' => 'Ringier',
                        'address' => 'J9JJ+CMP Grande Riviere Noire, Mauritius',
                        'city' => 'Riviere Noire District',
                        'directions_url' => 'https://maps.app.goo.gl/7hTMynts3PWUMfBQA',
                    ]
                )
            ),
            EventData::make(
                title: 'Laravel: Livewire, Reverb Integration, and API Mastery with Saloon',
                date: '2024-08-31',
                startsAt: '10:00',
                endsAt: '13:00',
                timezone: '+04:00',
                description: 'YouTube livestream with 3 talks: Livewire dynamic UIs (Ravish), Reverb + Livewire realtime (Bruno), and building APIs with Saloon (Percy). Meetup: https://www.meetup.com/laravel-moris/events/303073100/',
                type: EventLocation::Online,
                location: new EventLocationData(
                    class: OnlineLocation::class,
                    attributes: [
                        'platform' => 'YouTube',
                        'url' => 'https://www.youtube.com/watch?v=EFtrbfub3wU',
                    ]
                )
            ),
        ])->each(function (EventData $event): void {
            $location = $event->location->class::firstOrCreate($event->location->attributes);

            Event::firstOrCreate(
                [
                    'title' => $event->title,
                    'starts_at' => $event->startsAt,
                ],
                [
                    'description' => $event->description,
                    'ends_at' => $event->endsAt,
                    'type' => $event->type,
                    'location_type' => $event->location->class,
                    'location_id' => $location->id,
                ]
            );
        });
    }
};
