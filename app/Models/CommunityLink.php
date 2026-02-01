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
        ['icon_key' => 'discord', 'name' => 'Discord', 'description' => 'Chat with the community', 'url' => '#'],
        ['icon_key' => 'whatsapp', 'name' => 'WhatsApp', 'description' => 'Quick discussions', 'url' => '#'],
        ['icon_key' => 'linkedin', 'name' => 'LinkedIn', 'description' => 'Follow updates', 'url' => '#'],
        ['icon_key' => 'github-light', 'name' => 'GitHub', 'description' => 'Contribute to open source', 'url' => '#'],
    ];

    protected array $schema = [
        'icon_key' => 'string',
        'name' => 'string',
        'description' => 'string',
        'url' => 'string',
    ];
}
