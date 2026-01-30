<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Event;
use Illuminate\Support\Collection;

readonly class GetEventsQuery
{
    /**
     * Get all events grouped by upcoming and past.
     *
     * @return array<string, Collection<int, Event>>
     */
    public function execute(): array
    {
        $now = now();

        $upcoming = Event::query()
            ->withCount(['speakers', 'attendees'])
            ->where('starts_at', '>=', $now)
            ->oldest('starts_at')
            ->get();

        $past = Event::query()
            ->withCount(['speakers', 'attendees'])
            ->where('starts_at', '<', $now)
            ->latest('starts_at')
            ->get();

        return [
            'upcoming' => $upcoming,
            'past' => $past,
        ];
    }
}
