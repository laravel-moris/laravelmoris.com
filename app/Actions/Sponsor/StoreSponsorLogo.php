<?php

declare(strict_types=1);

namespace App\Actions\Sponsor;

use App\Support\WebP;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class StoreSponsorLogo
{
    public function execute(?string $content, ?string $extension): ?string
    {
        if (! $this->allowExtension($extension)) {
            return null;
        }

        if (blank($content)) {
            return null;
        }

        $uuid = (string) Str::uuid();
        $disk = Storage::disk('public');

        $originalPath = sprintf('sponsors-logos/%s/original.%s', $uuid, $extension);

        if (! $disk->put($originalPath, $content)) {
            return null;
        }

        if ($extension === 'svg') {
            return $originalPath;
        }

        try {
            $webpContent = WebP::make($content)
                ->maxWidth(400)
                ->toBinary();
        } catch (RuntimeException) {
            return null;
        }

        $webpPath = sprintf('sponsors-logos/%s/logo.webp', $uuid);

        if (! $disk->put($webpPath, $webpContent)) {
            return null;
        }

        return $webpPath;
    }

    private function allowExtension(?string $extension): bool
    {
        if (blank($extension)) {
            return false;
        }

        return in_array($extension, ['jpg', 'jpeg', 'png', 'svg'], true);
    }
}
