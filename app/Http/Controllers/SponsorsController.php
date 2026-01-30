<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Sponsor;
use App\Queries\GetSponsorProfileQuery;
use App\Queries\GetSponsorsQuery;
use Illuminate\View\View;

final readonly class SponsorsController
{
    public function __construct(
        private GetSponsorsQuery $getSponsorsQuery,
        private GetSponsorProfileQuery $getSponsorProfileQuery,
    ) {}

    /**
     * Display a listing of all sponsors.
     */
    public function index(): View
    {
        $sponsors = $this->getSponsorsQuery->execute();

        return view('pages.sponsors.index', [
            'sponsors' => $sponsors,
        ]);
    }

    /**
     * Display a sponsor's profile with their event history.
     */
    public function show(Sponsor $sponsor): View
    {
        $sponsor = $this->getSponsorProfileQuery->execute($sponsor);
        $logoUrl = $this->getSponsorProfileQuery->getLogoUrl($sponsor);

        return view('pages.sponsors.show', [
            'sponsor' => $sponsor,
            'logoUrl' => $logoUrl,
        ]);
    }
}
