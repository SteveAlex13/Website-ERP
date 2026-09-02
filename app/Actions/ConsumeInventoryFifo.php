<?php

namespace App\Actions;

use App\Models\InventoryBatch;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ConsumeInventoryFifo
{
    public function handle(SaleItem $saleItem, float $quantity): float
    {
        return DB::transaction(function () use ($saleItem, $quantity): float {
            $product = Product::query()->lockForUpdate()->findOrFail($saleItem->product_id);

            if ($quantity <= 0 || $quantity > (float) $product->stock) {
                throw ValidationException::withMessages([
                    'items' => sprintf('Stok %s tidak mencukupi. Tersedia %s %s.', $product->name, $product->stock, $product->unit),
                ]);
            }

            $trackedStock = (float) InventoryBatch::query()->whereBelongsTo($product)->sum('remaining_quantity');
            $openingQuantity = (float) $product->stock - $trackedStock;

            if ($openingQuantity > 0.0001) {
                $product->inventoryBatches()->create([
                    'received_at' => ($product->created_at ?? now())->toDateString(),
                    'initial_quantity' => $openingQuantity,
                    'remaining_quantity' => $openingQuantity,
                    'unit_cost' => $product->purchase_price,
                ]);
            }

            $remaining = $quantity;
            $costOfGoodsSold = 0.0;
            $batches = InventoryBatch::query()
                ->whereBelongsTo($product)
                ->where('remaining_quantity', '>', 0)
                ->orderBy('received_at')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            foreach ($batches as $batch) {
                if ($remaining <= 0.0001) {
                    break;
                }

                $allocatedQuantity = min($remaining, (float) $batch->remaining_quantity);
                $allocatedCost = $allocatedQuantity * (float) $batch->unit_cost;
                $saleItem->inventoryAllocations()->create([
                    'inventory_batch_id' => $batch->id,
                    'quantity' => $allocatedQuantity,
                    'unit_cost' => $batch->unit_cost,
                    'total_cost' => $allocatedCost,
                ]);
                $batch->decrement('remaining_quantity', $allocatedQuantity);
                $remaining -= $allocatedQuantity;
                $costOfGoodsSold += $allocatedCost;
            }

            if ($remaining > 0.0001) {
                throw ValidationException::withMessages(['items' => 'Lapisan persediaan tidak sesuai dengan stok produk.']);
            }

            $product->decrement('stock', $quantity);
            $saleItem->increment('cost_of_goods_sold', $costOfGoodsSold);

            return $costOfGoodsSold;
        });
    }
}
