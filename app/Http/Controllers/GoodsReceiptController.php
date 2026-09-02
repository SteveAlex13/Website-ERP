<?php

namespace App\Http\Controllers;

use App\Actions\ReceiveGoods;
use App\Models\Purchase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GoodsReceiptController extends Controller
{
    public function __invoke(Request $request, Purchase $purchase, ReceiveGoods $receiveGoods): RedirectResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        $quantities = collect($validated['items'])->filter(fn ($quantity): bool => (float) $quantity > 0)->all();
        $receiveGoods->handle($purchase, $quantities);

        return to_route('purchases.show', $purchase)->with('success', 'Penerimaan barang dikonfirmasi.');
    }
}
