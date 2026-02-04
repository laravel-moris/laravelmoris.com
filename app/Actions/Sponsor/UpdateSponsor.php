<?php

declare(strict_types=1);

namespace App\Actions\Sponsor;

use Illuminate\Http\UploadedFile;
use App\Data\Sponsor\UpdateSponsorData;
use App\Models\Sponsor;

final readonly class UpdateSponsor
{
    public function execute(Sponsor $sponsor, UpdateSponsorData $data): Sponsor
    {
        $sponsor->update([
            'name' => $data->name,
            'website' => $data->website,
        ]);

        // Handle logo deletion
        if ($data->deleteLogo) {
            $sponsor->clearMediaCollection('logo');
        }

        // Handle new logo upload
        if ($data->logo instanceof UploadedFile) {
            // Clear existing logo first
            $sponsor->clearMediaCollection('logo');

            $sponsor->addMedia($data->logo)
                ->toMediaCollection('logo');
        }

        return $sponsor->fresh();
    }
}
