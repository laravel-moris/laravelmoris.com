<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sponsors\Pages;

use App\Actions\Sponsor\UpdateSponsor as UpdateSponsorAction;
use App\Data\Sponsor\UpdateSponsorData;
use App\Filament\Resources\Sponsors\SponsorResource;
use App\Models\Sponsor;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

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

    /**
     * @param  Sponsor  $record
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        $hasUploaded = $data['logo'] instanceof UploadedFile;

        $updateData = new UpdateSponsorData(
            name: $data['name'],
            website: $data['website'],
            logo: $data['logo'],
            deleteLogo: $hasUploaded,
        );
        app(UpdateSponsorAction::class)->execute($record, $updateData);

        return $record;
    }
}
