<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class GetMembersQuery
{
    /**
     * Get paginated members with counts.
     *
     * @return LengthAwarePaginator<User>
     */
    public function execute(int $perPage = 12): LengthAwarePaginator
    {
        return User::query()
            ->withCount(['papers', 'rsvps'])
            ->orderBy('name')
            ->paginate($perPage);
    }
}
