<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sponsors\Pages;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Actions\Sponsor\UpdateSponsor as UpdateSponsorAction;
use App\Data\Sponsor\UpdateSponsorData;
use App\Filament\Resources\Sponsors\SponsorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSponsor extends EditRecord
{
    protected static string $resource = SponsorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $data = $this->data;
        $record = $this->getRecord();

        // Handle logo - can be array, null, or TemporaryUploadedFile
        $logo = null;
        if (is_array($data['logo'] ?? null)) {
            $logo = $data['logo'][0] ?? null;
        } elseif ($data['logo'] instanceof UploadedFile) {
            $logo = $data['logo'];
        }

        $deleteLogo = isset($data['logo']) && $data['logo'] === null;

        $updateData = new UpdateSponsorData(
            name: $data['name'],
            website: $data['website'],
            logo: $logo,
            deleteLogo: $deleteLogo,
        );

        app(UpdateSponsorAction::class)->execute($record, $updateData);

        // Clean up temp directory
        Storage::disk('public')->deleteDirectory('sponsors-logos-temp');
    }
}
