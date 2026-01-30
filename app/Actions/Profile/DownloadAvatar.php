<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class DownloadAvatar
{
    public function execute(?string $url): ?string
    {
        if ($url === null) {
            return null;
        }

        try {
            /**
             * @var Response $response
             */
            $response = Http::timeout(10)->get($url);

            if (! $response->successful()) {
                return null;
            }

            $extension = $this->getExtensionFromUrl($url);
            $filename = sprintf("avatars/%s.{$extension}", Str::random(16));

            Storage::disk('public')->put($filename, $response->body());

            return $filename;
        } catch (ConnectionException $e) {
            return null;
        }
    }

    private function getExtensionFromUrl(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)
            ? $extension
            : 'jpg';
    }
}
