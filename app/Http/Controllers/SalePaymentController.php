<?php

namespace App\Http\Controllers;

use App\Actions\CompleteSalePayment;
use App\Http\Requests\PaySaleRequest;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;

class SalePaymentController extends Controller
{
    public function __invoke(PaySaleRequest $request, Sale $sale, CompleteSalePayment $completeSalePayment): RedirectResponse
    {
        $completeSalePayment->handle($sale, $request->validated());

        $docTitle = $sale->document_type === 'pos' ? 'Bill' : 'Invoice '.ucfirst($sale->document_type);
        $message = sprintf('%s %s berhasil diselesaikan dan pembayaran tercatat.', $docTitle, $sale->number);

        if ($request->filled('redirect_to_invoices')) {
            return to_route('sales.index', ['tab' => 'invoice'])->with('success', $message);
        }

        if ($request->filled('redirect_to_orders')) {
            return to_route('sales.index', ['tab' => 'orders'])->with('success', $message);
        }

        return to_route('sales.show', $sale)->with('success', $message);
    }
}
