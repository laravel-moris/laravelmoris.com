<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PaperStatus;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paper>
 */
class PaperFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'event_id' => Event::factory(),
            'title' => fake()->sentence(5),
            'description' => fake()->optional()->paragraph(),
            'status' => PaperStatus::Draft,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'status' => PaperStatus::Approved,
        ]);
    }
}
