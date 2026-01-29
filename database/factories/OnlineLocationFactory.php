<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\OnlineLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OnlineLocation>
 */
class OnlineLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'platform' => fake()->randomElement(['youtube', 'zoom']),
            'url' => fake()->url(),
        ];
    }
}
