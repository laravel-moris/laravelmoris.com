<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sponsors\Pages;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Actions\Sponsor\CreateSponsor as CreateSponsorAction;
use App\Data\Sponsor\CreateSponsorData;
use App\Filament\Resources\Sponsors\SponsorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSponsor extends CreateRecord
{
    protected static string $resource = SponsorResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        $data = $this->data;

        // Handle logo - can be array, null, or TemporaryUploadedFile
        $logo = null;
        if (is_array($data['logo'] ?? null)) {
            $logo = $data['logo'][0] ?? null;
        } elseif ($data['logo'] instanceof UploadedFile) {
            $logo = $data['logo'];
        }

        $createData = new CreateSponsorData(
            name: $data['name'],
            website: $data['website'],
            logo: $logo,
        );

        app(CreateSponsorAction::class)->execute($createData);

        // Clean up temp directory
        Storage::disk('public')->deleteDirectory('sponsors-logos-temp');
    }
}
