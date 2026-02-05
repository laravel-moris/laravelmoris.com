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
        // Get current RSVP status before making changes
        $currentRsvp = $user->rsvps()->where('event_id', $event->id)->first();
        $hadConfirmedRsvp = $currentRsvp && $currentRsvp->rsvp->status === RsvpStatus::Going->value;

        if ($status === RsvpStatus::NotGoing) {
            $user->rsvps()->detach($event->id);

            // Decrement if they had a confirmed RSVP
            if ($hadConfirmedRsvp) {
                $user->decrement('rsvps_count_cache');
                $user->update(['counts_cached_at' => now()]);
            }

            return;
        }

        // Determine the new status
        $newStatus = $status ?? RsvpStatus::Going;
        $isConfirmed = $newStatus === RsvpStatus::Going;

        $user->rsvps()->syncWithoutDetaching([
            $event->id => ['status' => $newStatus->value],
        ]);

        // Update cache based on change
        if ($isConfirmed && ! $hadConfirmedRsvp) {
            // New confirmed RSVP
            $user->increment('rsvps_count_cache');
            $user->update(['counts_cached_at' => now()]);
        } elseif (! $isConfirmed && $hadConfirmedRsvp) {
            // Changed from confirmed to something else
            $user->decrement('rsvps_count_cache');
            $user->update(['counts_cached_at' => now()]);
        }
    }
}
