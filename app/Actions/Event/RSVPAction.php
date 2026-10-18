<?php

declare(strict_types=1);

namespace App\Actions\Event;

use App\Enums\RsvpStatus;
use App\Exceptions\EventFullException;
use App\Mail\RsvpCancellation;
use App\Mail\RsvpConfirmation;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

final readonly class RSVPAction
{
    public function execute(User $user, Event $event, ?RsvpStatus $status = null): void
    {
        $this->capacityCheck($event, $user, $status);

        $currentRsvp = $user->rsvps()->where('event_id', $event->id)->first();
        $hadConfirmedRsvp = $currentRsvp && $currentRsvp->rsvp->status === RsvpStatus::Going->value;

        if ($status === RsvpStatus::NotGoing) {
            $this->handleNotGoingRsvp($user, $event, $hadConfirmedRsvp);

            return;
        }

        $newStatus = $status ?? RsvpStatus::Going;

        $this->handleNewOrUpdatedRsvp($user, $event, $newStatus, $hadConfirmedRsvp);
    }

    /**
     * Handle a user marking themselves as "Not Going" to an event.
     * Detaches the RSVP and, if they had previously confirmed, sends a cancellation email
     * and decrements their RSVP count cache.
     */
    private function handleNotGoingRsvp(User $user, Event $event, bool $hadConfirmedRsvp): void
    {
        $user->rsvps()->detach($event->id);

        if ($hadConfirmedRsvp) {
            Mail::to($user)->queue(new RsvpCancellation($event, $user));

            $user->newQuery()
                ->whereKey($user->getKey())
                ->decrement('rsvps_count_cache', 1, ['counts_cached_at' => now()]);
        }
    }

    /**
     * Handle a new or updated RSVP for an event.
     *
     * Syncs the RSVP status, sends a confirmation email if the user is now "Going",
     * and adjusts the RSVP count cache based on whether their confirmed status has changed.
     */
    private function handleNewOrUpdatedRsvp(User $user, Event $event, RsvpStatus $status, bool $hadConfirmedRsvp): void
    {
        $isConfirmed = $status === RsvpStatus::Going;

        $user->rsvps()->syncWithoutDetaching([
            $event->id => ['status' => $status->value],
        ]);

        if ($isConfirmed) {
            Mail::to($user)->queue(new RsvpConfirmation($event, $user));
        }

        if ($isConfirmed && ! $hadConfirmedRsvp) {
            $user->newQuery()
                ->whereKey($user->getKey())
                ->increment('rsvps_count_cache', 1, ['counts_cached_at' => now()]);
        } elseif (! $isConfirmed && $hadConfirmedRsvp) {
            $user->newQuery()
                ->whereKey($user->getKey())
                ->decrement('rsvps_count_cache', 1, ['counts_cached_at' => now()]);
        }
    }

    private function capacityCheck(Event $event, User $user, ?RsvpStatus $status = null): void
    {
        $newStatus = $status ?? RsvpStatus::Going;

        if ($newStatus !== RsvpStatus::Going) {
            return;
        }

        if ($event->capacity === null) {
            return;
        }

        $currentRsvp = $user->rsvps()->where('event_id', $event->id)->first();
        $isAlreadyConfirmed = $currentRsvp && $currentRsvp->rsvp->status === RsvpStatus::Going->value;

        if ($isAlreadyConfirmed) {
            return;
        }

        $confirmedCount = $event->attendees()
            ->where('event_user.status', RsvpStatus::Going->value)
            ->count();

        throw_if($confirmedCount >= $event->capacity, EventFullException::class);
    }
}
