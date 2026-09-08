<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['number', 'bill_name', 'document_type', 'customer_id', 'sales_user_id', 'date', 'status', 'discount_type', 'discount_value', 'total', 'paid_amount', 'payment_method', 'bank_account_id'])]
class Sale extends Model
{
    use HasFactory;

    protected $attributes = ['document_type' => 'order', 'status' => 'open'];

    protected function casts(): array
    {
        return ['date' => 'date', 'discount_value' => 'decimal:2', 'total' => 'decimal:2', 'paid_amount' => 'decimal:2'];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'customer_id');
    }

    public function salesUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function deliveryOrders(): HasMany
    {
        return $this->hasMany(DeliveryOrder::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function bankTransactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class);
    }

    public function outstandingBalance(): float
    {
        return max(0, (float) $this->total - (float) $this->paid_amount);
    }

    public function deliveryPercentage(): int
    {
        $totalQty = (float) $this->items->sum('quantity');
        if ($totalQty <= 0) {
            return $this->status === 'completed' ? 100 : 0;
        }

        $deliveredQty = (float) $this->items->sum(fn ($item) => (float) $item->deliveredQuantity());

        return min(100, (int) round(($deliveredQty / $totalQty) * 100));
    }
}
