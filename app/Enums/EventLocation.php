<?php

declare(strict_types=1);

namespace App\Enums;

enum EventLocation: string
{
    case Physical = 'physical';
    case Online = 'online';
}
