<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Event;

readonly class GetEventDetailQuery
{
    /**
     * Load event detail with speakers and sponsors.
     */
    public function execute(Event $event): Event
    {
        $event->load([
            'location',
            'speakers' => function ($query) {
                $query->orderBy('name');
            },
            'sponsors' => function ($query) {
                $query->orderBy('name');
            },
        ]);

        return $event;
    }
}
