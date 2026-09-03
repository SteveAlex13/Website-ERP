<?php

namespace App\Models;

use Database\Factories\ExpensePurchaseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['number', 'supplier_id', 'date', 'purchase_type', 'document_type', 'description', 'total', 'paid_amount', 'payment_status', 'notes'])]
class ExpensePurchase extends Model
{
    /** @use HasFactory<ExpensePurchaseFactory> */
    use HasFactory;

    protected $attributes = [
        'purchase_type' => 'kebutuhan',
        'document_type' => 'invoice',
        'paid_amount' => 0,
        'payment_status' => 'unpaid',
    ];

    protected function casts(): array
    {
        return ['date' => 'date', 'total' => 'decimal:2', 'paid_amount' => 'decimal:2'];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'supplier_id');
    }

    public function outstandingBalance(): float
    {
        return max(0, (float) $this->total - (float) $this->paid_amount);
    }
}
