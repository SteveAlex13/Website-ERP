<?php

namespace App\Actions;

use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

class CreateStockIn
{
    public function __construct(private CreatePurchase $createPurchase, private ReceiveGoods $receiveGoods) {}

    public function handle(array $data): Purchase
    {
        return DB::transaction(function () use ($data): Purchase {
            $purchase = $this->createPurchase->handle($data);
            $quantities = $purchase->items()->pluck('quantity', 'id')->all();

            return $this->receiveGoods->handle($purchase, $quantities);
        });
    }
}
