<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\User;

readonly class GetMemberProfileQuery
{
    /**
     * Load member profile with speaking and attendance history.
     */
    public function execute(User $member): User
    {
        $member->load([
            'speakingEvents' => function ($query) {
                $query->orderBy('starts_at', 'desc');
            },
            'rsvps' => function ($query) {
                $query->orderBy('starts_at', 'desc');
            },
        ]);

        return $member;
    }
}
