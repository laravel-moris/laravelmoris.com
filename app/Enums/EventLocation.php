<?php

declare(strict_types=1);

namespace App\Enums;

enum EventLocation: string
{
    case Physical = 'physical';
    case Online = 'online';

    public function label(): string
    {
        return match ($this) {
            self::Physical => 'In Person',
            self::Online => 'Online',
        };
    }
}
