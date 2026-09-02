<?php

namespace App\Actions;

use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RefundSale
{
    public function handle(Sale $sale, string $reason): Sale
    {
        return DB::transaction(function () use ($sale, $reason): Sale {
            $sale = Sale::query()->lockForUpdate()->findOrFail($sale->id);

            if ($sale->document_type !== 'pos' || $sale->status !== 'completed') {
                throw ValidationException::withMessages(['sale' => 'Hanya transaksi POS selesai yang dapat direfund.']);
            }

            $sale->load('items.inventoryAllocations.inventoryBatch');
            foreach ($sale->items as $saleItem) {
                foreach ($saleItem->inventoryAllocations as $allocation) {
                    $allocation->inventoryBatch->increment('remaining_quantity', $allocation->quantity);
                }
                $saleItem->product()->lockForUpdate()->firstOrFail()->increment('stock', $saleItem->quantity);
            }

            if ($sale->bank_account_id) {
                $sale->bankTransactions()->create([
                    'bank_account_id' => $sale->bank_account_id,
                    'transaction_at' => now(),
                    'type' => 'debit',
                    'payment_method' => $sale->payment_method,
                    'description' => sprintf('Refund %s: %s', $sale->number, $reason),
                    'amount' => $sale->total,
                ]);
            }

            $sale->update(['status' => 'refunded', 'paid_amount' => 0]);

            return $sale->refresh();
        });
    }
}
