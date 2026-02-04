<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Pages;

use App\Actions\User\UpdateUser as UpdateUserAction;
use App\Data\User\UpdateUserData;
use App\Filament\Resources\User\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

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
     * @param  User  $record
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $hasUploaded = $data['avatar'] instanceof UploadedFile;

        $updateData = new UpdateUserData(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'] ?? null,
            title: $data['title'] ?? null,
            bio: $data['bio'] ?? null,
            avatar: $hasUploaded ? $data['avatar'] : null,
            deleteAvatar: $hasUploaded,
        );

        return app(UpdateUserAction::class)->execute($record, $updateData);
    }
}
