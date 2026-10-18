<?php

declare(strict_types=1);

namespace App\Http\Controllers\Event;

use App\Actions\Event\RSVPAction;
use App\Data\Event\RsvpData;
use App\Enums\RsvpStatus;
use App\Exceptions\EventFullException;
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

        $data = RsvpData::validateAndCreate($request->all());
        $status = $data->status ? RsvpStatus::from($data->status) : null;

        try {
            $this->rsvpAction->execute($user, $event, $status);
        } catch (EventFullException $e) {
            Session::flash('error', $e->getMessage());

            return to_route('events.show', $event);
        }

        return to_route('events.show', $event);
    }
}
