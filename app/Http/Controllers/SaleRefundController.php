<?php

namespace App\Http\Controllers;

use App\Actions\RefundSale;
use App\Http\Requests\RefundSaleRequest;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;

class SaleRefundController extends Controller
{
    public function __invoke(RefundSaleRequest $request, Sale $sale, RefundSale $refundSale): RedirectResponse
    {
        $refundSale->handle($sale, $request->string('reason')->toString());

        return to_route('sales.show', $sale)->with('success', 'Transaksi berhasil direfund dan stok dikembalikan.');
    }
}
