<?php

namespace App\Http\Controllers;

use App\Actions\CreateDeliveryOrder;
use App\Http\Requests\StoreDeliveryOrderRequest;
use App\Models\DeliveryOrder;
use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DeliveryOrderController extends Controller
{
    public function store(StoreDeliveryOrderRequest $request, Sale $sale, CreateDeliveryOrder $createDeliveryOrder): RedirectResponse
    {
        $deliveryOrder = $createDeliveryOrder->handle($sale, $request->validated());

        return to_route('sales.show', $sale)->with('success', 'Delivery Order berhasil dibuat.');
    }

    public function print(DeliveryOrder $deliveryOrder): View
    {
        $deliveryOrder->load(['sale.customer', 'items.saleItem.product']);

        return view('print.delivery-order', compact('deliveryOrder'));
    }
}
