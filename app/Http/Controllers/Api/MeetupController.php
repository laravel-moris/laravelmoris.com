<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\IndexMeetupsRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final readonly class MeetupController
{
    /**
     * List all meetups.
     */
    public function index(IndexMeetupsRequest $request): AnonymousResourceCollection
    {
        $perPage = $request->integer('per_page', 15);

        $events = Event::with('location')
            ->oldest('starts_at')
            ->paginate($perPage)
            ->withQueryString();

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
