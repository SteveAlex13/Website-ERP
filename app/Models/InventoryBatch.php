<?php

namespace App\Models;

use Database\Factories\InventoryBatchFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['product_id', 'purchase_item_id', 'supplier_id', 'received_at', 'initial_quantity', 'remaining_quantity', 'unit_cost'])]
class InventoryBatch extends Model
{
    /** @use HasFactory<InventoryBatchFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'received_at' => 'date',
            'initial_quantity' => 'decimal:2',
            'remaining_quantity' => 'decimal:2',
            'unit_cost' => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseItem(): BelongsTo
    {
        return $this->belongsTo(PurchaseItem::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'supplier_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(InventoryAllocation::class);
    }
}
