<?php

declare(strict_types=1);

namespace App\Actions\Paper;

use App\Data\Paper\SubmitPaperData;
use App\Enums\PaperStatus;
use App\Models\Event;
use App\Models\User;

final readonly class SubmitPaperAction
{
    public function execute(User $user, Event $event, SubmitPaperData $data): void
    {
        $updateData = [];

        if ($data->phone) {
            $updateData['phone'] = $data->phone;
        }

        if ($data->secondaryEmail) {
            $updateData['secondary_email'] = $data->secondaryEmail;
        }

        if (filled($updateData)) {
            $user->update($updateData);
        }

        $user->papers()->create([
            'event_id' => $event->id,
            'title' => $data->title,
            'description' => $data->description,
            'status' => PaperStatus::Submitted,
        ]);
    }
}
