<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\FishingSpot;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories.Factory<\App\Models\CatchLog>
 */
class CatchLogFactory extends Factory
{
    public function definition(): array
    {
        $species = ['Bass', 'Trout', 'Salmon', 'Mackerel', 'Snapper', 'Carp'];

        return [
            'user_id' => User::factory(),
            'fishing_spot_id' => FishingSpot::factory(),
            'species' => $this->faker->randomElement($species),
            'weight_kg' => $this->faker->randomFloat(2, 0.5, 12),
            'length_cm' => $this->faker->randomFloat(1, 20, 120),
            'depth_m' => $this->faker->randomFloat(1, 1, 30),
            'visibility' => $this->faker->randomElement(['public', 'friends', 'private']),
            'notes' => $this->faker->sentence(12),
        ];
    }
}
