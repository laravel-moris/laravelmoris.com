<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class EventFullException extends Exception
{
    public function __construct(string $message = 'This event is full.')
    {
        parent::__construct($message);
    }
}
