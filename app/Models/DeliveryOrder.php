<?php

namespace App\Models;

use Database\Factories\DeliveryOrderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['sale_id', 'number', 'date', 'status'])]
class DeliveryOrder extends Model
{
    /** @use HasFactory<DeliveryOrderFactory> */
    use HasFactory;

    protected $attributes = ['status' => 'confirmed'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(DeliveryOrderItem::class);
    }
}
