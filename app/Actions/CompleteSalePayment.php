<?php

namespace App\Actions;

use App\Models\BankAccount;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CompleteSalePayment
{
    public function __construct(private ConsumeInventoryFifo $consumeInventoryFifo) {}

    public function handle(Sale $sale, array $payment): Sale
    {
        return DB::transaction(function () use ($sale, $payment): Sale {
            $sale = Sale::query()->lockForUpdate()->findOrFail($sale->id);

            if (in_array($sale->status, ['cancelled', 'refunded'], true) || (float) $sale->paid_amount >= (float) $sale->total) {
                throw ValidationException::withMessages(['sale' => 'Invoice transaksi ini sudah lunas atau selesai.']);
            }

            $bankAccount = BankAccount::query()->findOrFail($payment['bank_account_id']);
            $isCashAccount = Str::startsWith($bankAccount->name, ['Kas', 'Tunai', 'Cash']);
            $paymentMethod = $payment['payment_method'] ?? ($isCashAccount ? 'cash' : 'bank_transfer');
            $remaining = max(0, (float) $sale->total - (float) $sale->paid_amount);

            if ($sale->document_type === 'pos') {
                $paidAmount = $paymentMethod === 'cash' ? (float) ($payment['paid_amount'] ?? 0) : (float) $sale->total;
                if ($paidAmount < (float) $sale->total) {
                    throw ValidationException::withMessages(['paid_amount' => 'Jumlah pembayaran kurang dari total transaksi.']);
                }
                $appliedPayment = (float) $sale->total;
            } else {
                $paidAmount = $isCashAccount ? (float) ($payment['paid_amount'] ?? 0) : $remaining;

                if ($paidAmount <= 0) {
                    throw ValidationException::withMessages(['paid_amount' => 'Nominal pembayaran harus lebih dari 0.']);
                }

                if ($paidAmount < $remaining) {
                    throw ValidationException::withMessages(['paid_amount' => 'Nominal pelunasan harus sama dengan atau lebih besar dari sisa tagihan.']);
                }

                $appliedPayment = $remaining;
            }

            $sale->load('items.deliveryOrderItems');
            foreach ($sale->items as $saleItem) {
                $alreadyDelivered = (float) $saleItem->deliveryOrderItems->sum('quantity');
                $unconsumed = (float) $saleItem->quantity - $alreadyDelivered;

                if ($unconsumed > 0.0001) {
                    $this->consumeInventoryFifo->handle($saleItem, $unconsumed);
                }
            }

            $newPaidTotal = min((float) $sale->total, (float) $sale->paid_amount + $appliedPayment);

            $sale->update([
                'status' => $sale->document_type === 'order' ? $sale->status : 'completed',
                'paid_amount' => $newPaidTotal,
                'payment_method' => $paymentMethod,
                'bank_account_id' => $bankAccount->id,
            ]);

            $sale->bankTransactions()->create([
                'bank_account_id' => $bankAccount->id,
                'transaction_at' => now(),
                'type' => 'credit',
                'payment_method' => $paymentMethod,
                'description' => sprintf('Pembayaran invoice %s (%s)', $sale->number, ucfirst($sale->document_type)),
                'amount' => $appliedPayment,
            ]);

            return $sale->refresh();
        });
    }
}
