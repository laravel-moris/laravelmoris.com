<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CommunityLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CommunityLink>
 */
class CommunityLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'icon_key' => $this->faker->randomElement([
                'ci-discord',
                'ci-whatsapp',
                'ci-linkedin',
                'ci-github-light',
            ]),
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'url' => $this->faker->url(),
        ];
    }
}
