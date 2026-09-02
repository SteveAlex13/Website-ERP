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

        return to_route('sales.show', $sale)->with('success', 'Bill berhasil dibayar dan transaksi bank dicatat.');
    }
}
