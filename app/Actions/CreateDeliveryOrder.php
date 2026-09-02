<?php

namespace App\Actions;

use App\Models\DeliveryOrder;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateDeliveryOrder
{
    public function __construct(private ConsumeInventoryFifo $consumeInventoryFifo) {}

    public function handle(Sale $sale, array $data): DeliveryOrder
    {
        return DB::transaction(function () use ($sale, $data): DeliveryOrder {
            if ($sale->document_type !== 'order' || in_array($sale->status, ['cancelled', 'completed'], true)) {
                throw ValidationException::withMessages(['sale' => 'Dokumen ini tidak dapat dikirim.']);
            }

            $items = Arr::pull($data, 'items');
            $deliveryOrder = $sale->deliveryOrders()->create($data);

            foreach ($items as $submittedItem) {
                $saleItem = SaleItem::query()->lockForUpdate()->findOrFail($submittedItem['sale_item_id']);
                $quantity = (float) $submittedItem['quantity'];
                $remaining = (float) $saleItem->quantity - $saleItem->deliveredQuantity();

                if ($saleItem->sale_id !== $sale->id || $quantity <= 0 || $quantity > $remaining) {
                    throw ValidationException::withMessages(['items' => 'Jumlah kirim melebihi sisa pesanan atau stok tersedia.']);
                }

                $deliveryOrder->items()->create(['sale_item_id' => $saleItem->id, 'quantity' => $quantity]);
                $this->consumeInventoryFifo->handle($saleItem, $quantity);
            }

            $sale->load('items.deliveryOrderItems');
            $complete = $sale->items->every(fn (SaleItem $item): bool => $item->deliveryOrderItems->sum('quantity') >= (float) $item->quantity);
            $sale->update(['status' => $complete ? 'completed' : 'partial']);

            return $deliveryOrder;
        });
    }
}
