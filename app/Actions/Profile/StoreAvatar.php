<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreAvatar
{
    const PATH = 'avatars/%s.%s';

    public function execute(?string $content, ?string $extension): ?string
    {
        if (! $this->allowExtension($extension)) {
            return null;
        }

        if (blank($content)) {
            return null;
        }

        $filename = sprintf(self::PATH, Str::random(), $extension);

        $disk = Storage::disk('public');

        if (! $disk->put($filename, $content)) {
            return null;
        }

        return $filename;

    }

    private function allowExtension(?string $extension): bool
    {
        if (blank($extension)) {
            return false;
        }

        return in_array($extension, ['jpg', 'jpeg', 'png'], true);
    }
}
