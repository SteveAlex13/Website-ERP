<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['sku', 'name', 'unit', 'purchase_price', 'selling_price', 'stock', 'minimum_stock'])]
class Product extends Model
{
    use HasFactory;

    protected $attributes = ['unit' => 'pcs', 'stock' => 0, 'minimum_stock' => 0];

    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'stock' => 'decimal:2',
            'minimum_stock' => 'decimal:2',
        ];
    }

    public function isLowStock(): bool
    {
        return (float) $this->stock <= (float) $this->minimum_stock;
    }

    public function inventoryBatches(): HasMany
    {
        return $this->hasMany(InventoryBatch::class);
    }
}
