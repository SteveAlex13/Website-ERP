<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => fake()->unique()->bothify('PRD-###??'),
            'name' => fake()->words(3, true),
            'unit' => fake()->randomElement(['pcs', 'box', 'pack']),
            'purchase_price' => fake()->numberBetween(10000, 500000),
            'selling_price' => fake()->numberBetween(550000, 1000000),
            'stock' => fake()->numberBetween(0, 100),
            'minimum_stock' => fake()->numberBetween(3, 15),
        ];
    }
}
