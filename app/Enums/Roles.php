<?php

declare(strict_types=1);

namespace App\Enums;

enum Roles: string
{
    case SuperAdmin = 'SUPER ADMIN';
    case Organizer = 'ORGANIZER';
    case Member = 'MEMBER';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Organizer => 'Organizer',
            self::Member => 'Member',
        };
    }
}
