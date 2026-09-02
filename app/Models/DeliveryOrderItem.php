<?php

namespace App\Models;

use Database\Factories\DeliveryOrderItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['delivery_order_id', 'sale_item_id', 'quantity'])]
class DeliveryOrderItem extends Model
{
    /** @use HasFactory<DeliveryOrderItemFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return ['quantity' => 'decimal:2'];
    }

    public function deliveryOrder(): BelongsTo
    {
        return $this->belongsTo(DeliveryOrder::class);
    }

    public function saleItem(): BelongsTo
    {
        return $this->belongsTo(SaleItem::class);
    }
}
