<?php

declare(strict_types=1);

namespace App\Queries;

use App\Data\CommunityLinkData;
use App\Models\CommunityLink;
use Spatie\LaravelData\DataCollection;

readonly class GetCommunityLinksQuery
{
    public function execute(): DataCollection
    {
        return CommunityLinkData::collect(
            CommunityLink::query()
                ->orderBy('name')
                ->get()
                ->map(fn (CommunityLink $link) => new CommunityLinkData(
                    iconKey: $link->icon_key,
                    name: $link->name,
                    description: $link->description,
                    url: $link->url,
                )),
            DataCollection::class,
        );
    }
}
