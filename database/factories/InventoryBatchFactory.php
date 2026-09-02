<?php

namespace Database\Factories;

use App\Models\InventoryBatch;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryBatch>
 */
class InventoryBatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'received_at' => now()->toDateString(),
            'initial_quantity' => 10,
            'remaining_quantity' => 10,
            'unit_cost' => fake()->numberBetween(10000, 100000),
        ];
    }
}
