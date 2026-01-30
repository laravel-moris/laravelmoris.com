<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Queries\GetMemberProfileQuery;
use App\Queries\GetMembersQuery;
use Illuminate\View\View;

final readonly class MembersController
{
    public function __construct(
        private GetMembersQuery $getMembersQuery,
        private GetMemberProfileQuery $getMemberProfileQuery,
    ) {}

    /**
     * Display a listing of all community members.
     */
    public function index(): View
    {
        $members = $this->getMembersQuery->execute();

        return view('pages.members.index', [
            'members' => $members,
        ]);
    }

    /**
     * Display a member's profile with speaking and attendance history.
     */
    public function show(User $member): View
    {
        $member = $this->getMemberProfileQuery->execute($member);

        return view('pages.members.show', [
            'member' => $member,
        ]);
    }
}
