<?php

declare(strict_types=1);

namespace App\Enums;

enum RsvpStatus: string
{
    case Going = 'going';
    case Maybe = 'maybe';
    case NotGoing = 'not_going';

    public function label(): string
    {
        return match ($this) {
            self::Going => 'Going',
            self::Maybe => 'Maybe',
            self::NotGoing => 'Not Going',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Going => 'green',
            self::Maybe => 'gold',
            self::NotGoing => 'coral',
        };
    }
}
