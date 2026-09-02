<?php

namespace Database\Factories;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->bothify('AST-###'),
            'name' => fake()->randomElement(['Kendaraan Operasional', 'Komputer Kantor', 'Mesin Gudang']),
            'acquired_at' => fake()->dateTimeBetween('-3 years'),
            'acquisition_cost' => fake()->numberBetween(5000000, 200000000),
            'residual_value' => 1000000,
            'useful_life_months' => fake()->randomElement([36, 48, 60]),
        ];
    }
}
