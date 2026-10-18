<?php

declare(strict_types=1);

namespace App\Actions\Event;

use App\Models\Event;
use App\Models\OnlineLocation;
use App\Models\PhysicalLocation;
use Illuminate\Support\Str;

final readonly class GenerateCalendarAction
{
    public function execute(Event $event): string
    {
        $slug = Str::slug($event->title);
        $uid = "laravel-moris-event-{$event->id}@laravelmoris.com";
        $dtStart = $event->starts_at->utc()->format('Ymd\THis\Z');
        $dtEnd = $event->ends_at->utc()->format('Ymd\THis\Z');
        $location = $this->formatLocation($event);
        $description = $event->description ? $this->escapeText($event->description) : '';
        $summary = $this->escapeText($event->title);

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Laravel Moris//Events//EN',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'BEGIN:VEVENT',
            "UID:$uid",
            "DTSTART:$dtStart",
            "DTEND:$dtEnd",
            "SUMMARY:$summary",
        ];

        if ($description !== '') {
            $lines[] = "DESCRIPTION:$description";
        }

        if ($location !== '') {
            $lines[] = "LOCATION:$location";
        }

        $lines[] = 'END:VEVENT';
        $lines[] = 'END:VCALENDAR';

        $content = implode("\r\n", $lines)."\r\n";

        return $this->foldLines($content);
    }

    private function formatLocation(Event $event): string
    {
        if (! $event->relationLoaded('location')) {
            $event->load('location');
        }

        $location = $event->location;

        if ($location === null) {
            return '';
        }

        if ($location instanceof PhysicalLocation) {
            $parts = array_filter([
                $location->venue_name,
                $location->address,
                $location->city,
            ]);

            return $this->escapeText(implode(', ', $parts));
        }

        if ($location instanceof OnlineLocation) {
            $parts = array_filter([
                $location->platform ? "Online via {$location->platform}" : null,
                $location->url,
            ]);

            return $this->escapeText(implode(' - ', $parts));
        }

        return '';
    }

    private function escapeText(string $text): string
    {
        $text = str_replace(['\\', ';', ',', "\n"], ['\\\\', '\\;', '\\,', '\\n'], $text);

        return $text;
    }

    private function foldLines(string $content): string
    {
        $lines = explode("\r\n", $content);
        $folded = [];

        foreach ($lines as $line) {
            if (mb_strlen($line) > 75) {
                $folded[] = mb_substr($line, 0, 75);
                $remaining = mb_substr($line, 75);

                while (mb_strlen($remaining) > 74) {
                    $folded[] = ' '.mb_substr($remaining, 0, 74);
                    $remaining = mb_substr($remaining, 74);
                }

                if ($remaining !== '') {
                    $folded[] = " $remaining";
                }
            } else {
                $folded[] = $line;
            }
        }

        return implode("\r\n", $folded);
    }

    public function filename(Event $event): string
    {
        return 'laravel-moris-'.Str::slug($event->title).'.ics';
    }
}
