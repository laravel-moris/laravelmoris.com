<?php

declare(strict_types=1);

namespace App\Data\User;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class CreateUserData extends Data
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password = null,
        public ?string $title = null,
        public ?string $bio = null,
        public ?UploadedFile $avatar = null,
    ) {}
}
