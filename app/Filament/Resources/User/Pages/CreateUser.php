<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Pages;

use App\Actions\User\CreateUser as CreateUserAction;
use App\Data\User\CreateUserData;
use App\Filament\Resources\User\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $hasUploaded = $data['avatar'] instanceof UploadedFile;

        $createData = new CreateUserData(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'] ?? null,
            title: $data['title'] ?? null,
            bio: $data['bio'] ?? null,
            avatar: $hasUploaded ? $data['avatar'] : null,
        );

        return app(CreateUserAction::class)->execute($createData);
    }
}
