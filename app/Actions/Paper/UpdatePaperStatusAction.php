<?php

declare(strict_types=1);

namespace App\Actions\Paper;

use App\Enums\PaperStatus;
use App\Models\Paper;

final readonly class UpdatePaperStatusAction
{
    public function execute(Paper $paper, PaperStatus $newStatus): void
    {
        $paper->update([
            'status' => $newStatus,
        ]);
    }
}
