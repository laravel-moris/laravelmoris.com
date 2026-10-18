<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Event\GenerateCalendarAction;
use App\Models\Event;
use App\Queries\GetEventDetailQuery;
use App\Queries\GetEventsQuery;
use Illuminate\Http\Response;
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

        if (auth()->check()) {
            $event->load(['attendees' => function ($query) {
                $query->where('user_id', auth()->id());
            }]);
        }

        return view('pages.events.show', [
            'event' => $event,
        ]);
    }

    /**
     * Download the calendar invite (.ics) for an event.
     */
    public function calendar(Event $event, GenerateCalendarAction $generateCalendar): Response
    {
        $ics = $generateCalendar->execute($event);

        return response($ics, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$generateCalendar->filename($event).'"',
        ]);
    }
}
