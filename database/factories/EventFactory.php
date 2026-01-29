<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\EventLocation;
use App\Models\Event;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startsAt = CarbonImmutable::instance(fake()->dateTimeBetween('+1 day', '+2 months'));

        return [
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'starts_at' => $startsAt,
            'ends_at' => $startsAt->addHours(4),
            'type' => fake()->randomElement([EventLocation::Physical, EventLocation::Online]),
        ];
    }
}
