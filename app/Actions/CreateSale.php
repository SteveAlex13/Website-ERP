<?php

namespace App\Actions;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateSale
{
    public function __construct(private CompleteSalePayment $completeSalePayment) {}

    public function handle(array $data): Sale
    {
        return DB::transaction(function () use ($data): Sale {
            $items = Arr::pull($data, 'items');
            $isPointOfSale = $data['document_type'] === 'pos';
            $submitAction = Arr::pull($data, 'submit_action', 'transaction');
            $payment = Arr::only($data, ['payment_method', 'bank_account_id', 'paid_amount']);

            if ($isPointOfSale) {
                $items = collect($items)->map(function (array $item): array {
                    $product = Product::query()->lockForUpdate()->findOrFail($item['product_id']);
                    $quantity = (float) $item['quantity'];

                    if ($quantity > (float) $product->stock) {
                        throw ValidationException::withMessages([
                            'items' => sprintf('Stok %s tidak mencukupi. Tersedia %s %s.', $product->name, $product->stock, $product->unit),
                        ]);
                    }

                    return [...$item, 'price' => (float) $product->selling_price];
                })->all();
            }

            $subtotal = collect($items)->sum(fn (array $item): float => ($item['quantity'] * $item['price']) - ($item['discount'] ?? 0));
            $discount = $data['discount_type'] === 'percentage'
                ? $subtotal * (($data['discount_value'] ?? 0) / 100)
                : ($data['discount_value'] ?? 0);
            $data['total'] = max(0, $subtotal - $discount);

            if ($isPointOfSale) {
                $data['status'] = 'open';
                $data['paid_amount'] = 0;
                $data['payment_method'] = null;
                $data['bank_account_id'] = null;
            }

            $sale = Sale::create($data);

            $sale->items()->createMany(collect($items)->map(fn (array $item): array => [
                ...$item,
                'discount' => $item['discount'] ?? 0,
                'subtotal' => ($item['quantity'] * $item['price']) - ($item['discount'] ?? 0),
            ])->all());

            if ($isPointOfSale && $submitAction === 'transaction') {
                $sale = $this->completeSalePayment->handle($sale, $payment);
            }

            return $sale;
        });
    }
}
