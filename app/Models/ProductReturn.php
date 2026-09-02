<?php

namespace App\Models;

use Database\Factories\ProductReturnFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['number', 'type', 'reference_type', 'reference_id', 'date', 'status', 'total'])]
class ProductReturn extends Model
{
    /** @use HasFactory<ProductReturnFactory> */
    use HasFactory;

    protected $attributes = ['status' => 'draft'];

    protected function casts(): array
    {
        return ['date' => 'date', 'total' => 'decimal:2'];
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProductReturnItem::class);
    }
}
