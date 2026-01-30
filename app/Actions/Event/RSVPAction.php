<?php

declare(strict_types=1);

namespace App\Actions\Event;

use App\Enums\RsvpStatus;
use App\Models\Event;
use App\Models\User;

final readonly class RSVPAction
{
    public function execute(User $user, Event $event, ?RsvpStatus $status = null): void
    {
        if ($status === RsvpStatus::NotGoing) {
            $user->rsvps()->detach($event->id);

            return;
        }

        $user->rsvps()->syncWithoutDetaching([
            $event->id => ['status' => ($status ?? RsvpStatus::Going)->value],
        ]);
    }
}
