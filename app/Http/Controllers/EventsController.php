<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Event;
use App\Queries\GetEventDetailQuery;
use App\Queries\GetEventsQuery;
use Illuminate\View\View;

final readonly class EventsController
{
    public function __construct(
        private GetEventsQuery $getEventsQuery,
        private GetEventDetailQuery $getEventDetailQuery,
    ) {}

    /**
     * Display a listing of all events (upcoming and past).
     */
    public function index(): View
    {
        $events = $this->getEventsQuery->execute();

        return view('pages.events.index', [
            'upcomingEvents' => $events['upcoming'],
            'pastEvents' => $events['past'],
        ]);
    }

    /**
     * Display an event's details with speakers and sponsors.
     */
    public function show(Event $event): View
    {
        $event = $this->getEventDetailQuery->execute($event);

        return view('pages.events.show', [
            'event' => $event,
        ]);
    }
}
