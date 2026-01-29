<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PhysicalLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PhysicalLocation>
 */
class PhysicalLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'venue_name' => fake()->company(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'directions_url' => fake()->url(),
        ];
    }
}
