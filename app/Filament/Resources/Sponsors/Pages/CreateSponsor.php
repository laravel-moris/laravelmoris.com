<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sponsors\Pages;

use App\Actions\Sponsor\CreateSponsor as CreateSponsorAction;
use App\Data\Sponsor\CreateSponsorData;
use App\Filament\Resources\Sponsors\SponsorResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class CreateSponsor extends CreateRecord
{
    protected static string $resource = SponsorResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {

        $hasUploaded = $data['logo'] instanceof UploadedFile;

        $createData = new CreateSponsorData(
            name: $data['name'],
            website: $data['website'],
            logo: $hasUploaded ? $data['logo'] : null,
        );

        return app(CreateSponsorAction::class)->execute($createData);
    }
}
