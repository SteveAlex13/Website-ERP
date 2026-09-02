<?php

namespace App\Actions;

use App\Models\Purchase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CreatePurchase
{
    public function handle(array $data): Purchase
    {
        return DB::transaction(function () use ($data): Purchase {
            $items = Arr::pull($data, 'items');
            $data['total'] = collect($items)->sum(fn (array $item): float => $item['quantity'] * $item['price']);
            $purchase = Purchase::create($data);

            $purchase->items()->createMany(collect($items)->map(fn (array $item): array => [
                ...$item,
                'subtotal' => $item['quantity'] * $item['price'],
            ])->all());

            return $purchase;
        });
    }
}
