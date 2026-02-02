<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class CommunityLink extends Model
{
    use HasFactory, Sushi;

    /**
     * @var array<int, array{icon_key: string, name: string, description: string, url: string}>
     */
    protected array $rows = [
        ['icon_key' => 'youtube', 'name' => 'YouTube', 'description' => 'Watch meetup recordings', 'url' => 'https://www.youtube.com/@laravelphpmoris'],
        ['icon_key' => 'twitter', 'name' => 'X (Twitter)', 'description' => 'Latest updates', 'url' => 'https://x.com/laravelphpmoris'],
        ['icon_key' => 'bluesky', 'name' => 'Blue Sky', 'description' => 'Follow the community', 'url' => 'https://bsky.app/profile/laravelmoris.bsky.social'],
        ['icon_key' => 'facebook', 'name' => 'Facebook', 'description' => 'Join the community', 'url' => 'https://www.facebook.com/laravelphpmoris'],
        ['icon_key' => 'linkedin', 'name' => 'LinkedIn', 'description' => 'Follow updates', 'url' => 'https://www.linkedin.com/company/laravelphpmoris/'],
        ['icon_key' => 'github-light', 'name' => 'GitHub', 'description' => 'Contribute to open source', 'url' => 'https://github.com/laravel-moris/'],
        ['icon_key' => 'whatsapp', 'name' => 'WhatsApp', 'description' => 'Quick discussions', 'url' => 'https://chat.whatsapp.com/HqGA8K8QwoJ7ikhGQ5dE1a?mode=gi_t'],
        ['icon_key' => 'discord', 'name' => 'Discord', 'description' => 'Chat with the community', 'url' => 'https://discord.gg/8vjXugjRfG'],
    ];

    protected array $schema = [
        'icon_key' => 'string',
        'name' => 'string',
        'description' => 'string',
        'url' => 'string',
    ];
}
