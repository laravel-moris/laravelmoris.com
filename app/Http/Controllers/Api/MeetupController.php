<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MeetupController
{
    /**
     * List all meetups.
     */
    public function index(): AnonymousResourceCollection
    {
        $events = Event::with('location')
            ->oldest('starts_at')
            ->get();

        return EventResource::collection($events);
    }

    /**
     * Show a specific meetup with all relations.
     */
    public function show(Event $event): EventResource
    {
        $event->load(['location']);

        return EventResource::make($event);
    }
}
