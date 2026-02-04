<?php

declare(strict_types=1);

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final readonly class DownloadAvatar
{
    public function execute(User $user, ?string $url): ?string
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

            // Create a temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'avatar_');
            file_put_contents($tempFile, $response->body());

            // Add to media library
            $user->addMedia($tempFile)
                ->toMediaCollection('avatar');

            // Clean up temp file
            @unlink($tempFile);

            return $user->getFirstMediaUrl('avatar');
        } catch (ConnectionException $e) {
            report($e);

            return null;
        }
    }
}
