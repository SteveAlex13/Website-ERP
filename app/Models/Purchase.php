<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['number', 'supplier_id', 'date', 'status', 'shipping_cost', 'shipping_status', 'total', 'paid_amount'])]
class Purchase extends Model
{
    use HasFactory;

    protected $attributes = ['status' => 'open', 'shipping_status' => 'unpaid'];

    protected function casts(): array
    {
        return ['date' => 'date', 'shipping_cost' => 'decimal:2', 'total' => 'decimal:2', 'paid_amount' => 'decimal:2'];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'supplier_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
