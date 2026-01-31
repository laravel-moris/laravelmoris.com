<?php

declare(strict_types=1);

namespace App\Actions\Paper;

use App\Models\Event;
use App\Models\Paper;

final readonly class MovePaperToEventAction
{
    public function execute(Paper $paper, Event $newEvent): void
    {
        $paper->update([
            'event_id' => $newEvent->id,
        ]);
    }
}
