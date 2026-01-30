<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final readonly class DownloadAvatar
{
    public function __construct(private StoreAvatar $storeAvatar) {}

    public function execute(?string $url): ?string
    {
        if (blank($url)) {
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

            return $this->storeAvatar->execute($response->body(), $extension);
        } catch (ConnectionException $e) {
            report($e);

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
