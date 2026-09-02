<?php

namespace App\Models;

use Database\Factories\SaleItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['sale_id', 'product_id', 'quantity', 'price', 'discount', 'subtotal', 'cost_of_goods_sold'])]
class SaleItem extends Model
{
    /** @use HasFactory<SaleItemFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return ['quantity' => 'decimal:2', 'price' => 'decimal:2', 'discount' => 'decimal:2', 'subtotal' => 'decimal:2', 'cost_of_goods_sold' => 'decimal:2'];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function deliveryOrderItems(): HasMany
    {
        return $this->hasMany(DeliveryOrderItem::class);
    }

    public function inventoryAllocations(): HasMany
    {
        return $this->hasMany(InventoryAllocation::class);
    }

    public function deliveredQuantity(): float
    {
        return (float) $this->deliveryOrderItems()->sum('quantity');
    }
}
