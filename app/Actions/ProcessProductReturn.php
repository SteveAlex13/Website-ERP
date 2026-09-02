<?php

namespace App\Actions;

use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProcessProductReturn
{
    public function handle(array $data): ProductReturn
    {
        return DB::transaction(function () use ($data): ProductReturn {
            $items = Arr::pull($data, 'items');
            $data['total'] = collect($items)->sum(fn (array $item): float => $item['quantity'] * $item['price']);
            $data['status'] = 'completed';
            $productReturn = ProductReturn::create($data);

            foreach ($items as $item) {
                $product = Product::query()->lockForUpdate()->findOrFail($item['product_id']);
                $subtotal = $item['quantity'] * $item['price'];

                if ($data['type'] === 'purchase' && (float) $product->stock < $item['quantity']) {
                    throw ValidationException::withMessages(['items' => 'Stok tidak cukup untuk retur pembelian.']);
                }

                $productReturn->items()->create([...$item, 'subtotal' => $subtotal]);
                $data['type'] === 'sale'
                    ? $product->increment('stock', $item['quantity'])
                    : $product->decrement('stock', $item['quantity']);
            }

            if ($data['type'] === 'sale') {
                $sale = Sale::query()->lockForUpdate()->findOrFail($data['reference_id']);
                $sale->update(['total' => max(0, (float) $sale->total - (float) $data['total'])]);
            } else {
                $purchase = Purchase::query()->lockForUpdate()->findOrFail($data['reference_id']);
                $purchase->update(['total' => max(0, (float) $purchase->total - (float) $data['total'])]);
            }

            return $productReturn;
        });
    }
}
