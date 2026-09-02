<?php

namespace Database\Factories;

use App\Models\InventoryAllocation;
use App\Models\InventoryBatch;
use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<InventoryAllocation>
 */
class InventoryAllocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sale_item_id' => SaleItem::factory(),
            'inventory_batch_id' => InventoryBatch::factory(),
            'quantity' => 1,
            'unit_cost' => 10000,
            'total_cost' => 10000,
        ];
    }
}
