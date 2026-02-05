<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class GetMembersQuery
{
    /**
     * Get paginated members with counts.
     * Uses cached counts for performance. Falls back to withCount if cache is stale.
     *
     * @return LengthAwarePaginator<User>
     */
    public function execute(int $perPage = 12): LengthAwarePaginator
    {
        return User::query()
            ->select([
                'id',
                'name',
                'email',
                'title',
                'bio',
                'avatar',
                'papers_count_cache',
                'rsvps_count_cache',
            ])
            ->orderBy('name')
            ->paginate($perPage)
            ->through(function (User $user): User {
                // Map cached counts to the expected attribute names for the view
                $user->papers_count = $user->papers_count_cache;
                $user->rsvps_count = $user->rsvps_count_cache;

                return $user;
            });
    }
}
