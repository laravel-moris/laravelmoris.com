<?php

declare(strict_types=1);

namespace App\Http\Controllers\Event;

use App\Actions\Event\RSVPAction;
use App\Data\Event\RsvpData;
use App\Enums\RsvpStatus;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

final readonly class RSVPController
{
    public function __construct(private RSVPAction $rsvpAction) {}

    public function __invoke(Event $event, Request $request): RedirectResponse
    {
        $user = auth()->user();

        if (! $user) {
            return to_route('login');
        }

        if ($event->starts_at->isPast()) {
            Session::flash('error', 'You cannot change your RSVP for past events.');

            return to_route('events.show', $event);
        }

        $data = RsvpData::validateAndCreate($request->all());
        $status = $data->status ? RsvpStatus::from($data->status) : null;

        $this->rsvpAction->execute($user, $event, $status);

        return to_route('events.show', $event);
    }
}
