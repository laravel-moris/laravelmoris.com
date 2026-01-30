<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\GetCommunityLinksQuery;
use App\Queries\GetFeaturedSpeakersQuery;
use App\Queries\GetHappeningNowQuery;
use App\Queries\GetPastSponsorsQuery;
use App\Queries\GetUpcomingMeetupsQuery;
use Illuminate\Contracts\View\View;

class HomeController
{
    public function __invoke(
        GetHappeningNowQuery $getHappeningNowQuery,
        GetUpcomingMeetupsQuery $getUpcomingMeetupsQuery,
        GetFeaturedSpeakersQuery $getFeaturedSpeakersQuery,
        GetCommunityLinksQuery $getCommunityLinksQuery,
        GetPastSponsorsQuery $getPastSponsorsQuery,
    ): View {

        return view('pages.home', [
            'happeningNow' => $getHappeningNowQuery->execute(),
            'meetups' => $getUpcomingMeetupsQuery->execute(),
            'speakers' => $getFeaturedSpeakersQuery->execute(),
            'communityLinks' => $getCommunityLinksQuery->execute(),
            'sponsors' => $getPastSponsorsQuery->execute(),
        ]);
    }
}
