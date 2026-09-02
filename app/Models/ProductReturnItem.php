<?php

namespace App\Models;

use Database\Factories\ProductReturnItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_return_id', 'product_id', 'quantity', 'price', 'subtotal'])]
class ProductReturnItem extends Model
{
    /** @use HasFactory<ProductReturnItemFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return ['quantity' => 'decimal:2', 'price' => 'decimal:2', 'subtotal' => 'decimal:2'];
    }

    public function productReturn(): BelongsTo
    {
        return $this->belongsTo(ProductReturn::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
