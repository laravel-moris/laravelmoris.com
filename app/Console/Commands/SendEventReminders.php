<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\RsvpStatus;
use App\Mail\EventReminder;
use App\Models\Event;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

final class SendEventReminders extends Command
{
    protected $signature = 'events:send-reminders';

    protected $description = 'Send reminder emails for upcoming events within 24 hours';

    public function handle(): int
    {
        $now = CarbonImmutable::now();
        $tomorrow = $now->addDay();

        $events = Event::query()
            ->with(['attendees'])
            ->whereBetween('starts_at', [$now, $tomorrow])
            ->get();

        $sent = 0;

        foreach ($events as $event) {
            $confirmedAttendees = $event->attendees
                ->filter(fn ($user) => $user->rsvp->status === RsvpStatus::Going->value);

            foreach ($confirmedAttendees as $attendee) {
                Mail::to($attendee)->queue(new EventReminder($event, $attendee));
                $sent++;
            }

            $this->info("Sent {$sent} reminders for '{$event->title}'");
        }

        $this->comment("Sent {$sent} event reminder(s).");

        return self::SUCCESS;
    }
}
