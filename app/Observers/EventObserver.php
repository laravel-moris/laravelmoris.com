<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\RsvpStatus;
use App\Models\Event;
use App\Models\Rsvp;
use App\Models\User;

final readonly class EventObserver
{
    /**
     * Handle soft-deletion of an event.
     *
     * Cascades the delete to all associated papers and decrements the cached
     * RSVP count for every confirmed attendee.
     */
    public function deleted(Event $event): void
    {
        $event->papers()->delete();

        Rsvp::query()
            ->where('event_id', $event->id)
            ->where('status', RsvpStatus::Going->value)
            ->orderBy('id')
            ->each(function (Rsvp $rsvp) {
                User::query()
                    ->whereKey($rsvp->user_id)
                    ->decrement('rsvps_count_cache');
            });
    }

    /**
     * Handle restoration of a soft-deleted event.
     *
     * Restores all associated papers and increments the cached RSVP count for
     * every confirmed attendee.
     */
    public function restored(Event $event): void
    {
        $event->papers()->onlyTrashed()->restore();

        Rsvp::query()
            ->where('event_id', $event->id)
            ->where('status', RsvpStatus::Going->value)
            ->orderBy('id')
            ->each(function (Rsvp $rsvp) {
                User::query()
                    ->whereKey($rsvp->user_id)
                    ->increment('rsvps_count_cache');
            });
    }

    /**
     * Handle force-deletion of an event.
     *
     * No additional action is needed here because the database-level foreign key
     * cascade (cascadeOnDelete) already removes associated pivot records.
     */
    public function forceDeleted(Event $event): void
    {
        //
    }
}
