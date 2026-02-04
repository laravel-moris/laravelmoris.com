<?php

declare(strict_types=1);

namespace App\Actions\Sponsor;

use Illuminate\Http\UploadedFile;
use App\Data\Sponsor\CreateSponsorData;
use App\Models\Sponsor;

final readonly class CreateSponsor
{
    public function execute(CreateSponsorData $data): Sponsor
    {
        $sponsor = Sponsor::query()->create([
            'name' => $data->name,
            'website' => $data->website,
        ]);

        if ($data->logo instanceof UploadedFile) {
            $sponsor->addMedia($data->logo)
                ->toMediaCollection('logo');
        }

        return $sponsor;
    }
}
