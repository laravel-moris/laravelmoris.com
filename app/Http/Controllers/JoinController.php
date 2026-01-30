<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\GetCommunityLinksQuery;
use Illuminate\View\View;

final readonly class JoinController
{
    public function __construct(
        private GetCommunityLinksQuery $getCommunityLinksQuery,
    ) {}

    /**
     * Display the Join Our Community page.
     */
    public function index(): View
    {
        $communityLinks = $this->getCommunityLinksQuery->execute();

        return view('pages.join.index', [
            'communityLinks' => $communityLinks,
        ]);
    }
}
