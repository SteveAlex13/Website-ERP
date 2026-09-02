<?php

namespace App\Actions;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReceiveGoods
{
    public function handle(Purchase $purchase, array $quantities): Purchase
    {
        return DB::transaction(function () use ($purchase, $quantities): Purchase {
            $purchase->load('items');

            foreach ($quantities as $itemId => $quantity) {
                $item = PurchaseItem::query()->lockForUpdate()->findOrFail($itemId);

                if ($item->purchase_id !== $purchase->id || $quantity <= 0 || $item->received_quantity + $quantity > $item->quantity) {
                    throw ValidationException::withMessages(['items' => 'Jumlah penerimaan tidak valid atau melebihi pembelian.']);
                }

                $product = Product::query()->lockForUpdate()->findOrFail($item->product_id);
                $item->increment('received_quantity', $quantity);
                $product->inventoryBatches()->create([
                    'purchase_item_id' => $item->id,
                    'supplier_id' => $purchase->supplier_id,
                    'received_at' => now()->toDateString(),
                    'initial_quantity' => $quantity,
                    'remaining_quantity' => $quantity,
                    'unit_cost' => $item->price,
                ]);
                $product->update(['purchase_price' => $item->price]);
                $product->increment('stock', $quantity);
            }

            $purchase->refresh()->load('items');
            $complete = $purchase->items->every(fn (PurchaseItem $item): bool => (float) $item->received_quantity >= (float) $item->quantity);
            $hasReceipt = $purchase->items->contains(fn (PurchaseItem $item): bool => (float) $item->received_quantity > 0);
            $purchase->update(['status' => $complete ? 'completed' : ($hasReceipt ? 'partial' : 'open')]);

            return $purchase;
        });
    }
}
