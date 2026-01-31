<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Paper;
use Illuminate\Support\Collection;

readonly class GetPapersQuery
{
    /**
     * Get all papers.
     *
     * @return Collection<int, Paper>
     */
    public function execute(): Collection
    {
        return Paper::query()
            ->with(['speaker', 'event'])
            ->latest()
            ->get();
    }
}
