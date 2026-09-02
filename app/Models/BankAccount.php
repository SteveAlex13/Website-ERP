<?php

namespace App\Models;

use Database\Factories\BankAccountFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'account_number', 'opening_balance'])]
class BankAccount extends Model
{
    /** @use HasFactory<BankAccountFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return ['opening_balance' => 'decimal:2'];
    }

    public function cashExpenses(): HasMany
    {
        return $this->hasMany(CashExpense::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class);
    }

    public function balance(): float
    {
        $credits = (float) $this->transactions()->where('type', 'credit')->sum('amount');
        $debits = (float) $this->transactions()->where('type', 'debit')->sum('amount');

        return (float) $this->opening_balance + $credits - $debits - (float) $this->cashExpenses()->sum('amount');
    }
}
