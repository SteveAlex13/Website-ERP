<?php

namespace App\Actions;

use App\Models\BankAccount;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CompleteSalePayment
{
    public function __construct(private ConsumeInventoryFifo $consumeInventoryFifo) {}

    public function handle(Sale $sale, array $payment): Sale
    {
        return DB::transaction(function () use ($sale, $payment): Sale {
            $sale = Sale::query()->lockForUpdate()->findOrFail($sale->id);

            if ($sale->document_type !== 'pos' || $sale->status !== 'open') {
                throw ValidationException::withMessages(['sale' => 'Bill ini tidak dapat dibayar.']);
            }

            $paymentMethod = $payment['payment_method'];
            $paidAmount = $paymentMethod === 'cash' ? (float) ($payment['paid_amount'] ?? 0) : (float) $sale->total;
            if ($paidAmount < (float) $sale->total) {
                throw ValidationException::withMessages(['paid_amount' => 'Jumlah pembayaran kurang dari total transaksi.']);
            }

            $bankAccount = $paymentMethod === 'cash'
                ? BankAccount::query()->where('name', 'like', 'Kas%')->orderBy('id')->first()
                : BankAccount::query()->where('name', 'not like', 'Kas%')->orderBy('id')->first();
            $bankAccount ??= BankAccount::query()->orderBy('id')->firstOrFail();

            $sale->load('items');
            foreach ($sale->items as $saleItem) {
                $this->consumeInventoryFifo->handle($saleItem, (float) $saleItem->quantity);
            }

            $sale->update([
                'status' => 'completed',
                'paid_amount' => $sale->total,
                'payment_method' => $paymentMethod,
                'bank_account_id' => $bankAccount->id,
            ]);
            $sale->bankTransactions()->create([
                'bank_account_id' => $bankAccount->id,
                'transaction_at' => now(),
                'type' => 'credit',
                'payment_method' => $paymentMethod,
                'description' => sprintf('Pembayaran penjualan %s', $sale->number),
                'amount' => $sale->total,
            ]);

            return $sale->refresh();
        });
    }
}
