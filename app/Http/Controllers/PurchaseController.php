<?php

namespace App\Http\Controllers;

use App\Actions\CreateStockIn;
use App\Http\Requests\StorePurchaseRequest;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $perPage = in_array(request()->integer('per_page'), [10, 25, 50], true) ? request()->integer('per_page') : 10;
        $purchases = Purchase::query()->select(['id', 'number', 'supplier_id', 'date', 'status', 'total'])
            ->with('supplier:id,name')
            ->withCount('items')
            ->when(request('search'), fn ($query, $search) => $query->where('number', 'like', '%'.$search.'%'))
            ->latest()->paginate($perPage)->withQueryString();

        $stockInHistory = \App\Models\InventoryBatch::query()
            ->with(['product:id,name,sku,unit', 'supplier:id,name', 'purchaseItem.purchase:id,number'])
            ->latest('received_at')
            ->latest('id')
            ->paginate(10, ['*'], 'history_page')
            ->withQueryString();

        return view('purchases.index', compact('purchases', 'stockInHistory'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('purchases.form', [
            'suppliers' => Contact::suppliers()->orderBy('name')->get(['id', 'name']),
            'products' => Product::query()->orderBy('name')->get(['id', 'sku', 'name', 'purchase_price']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request, CreateStockIn $createStockIn): RedirectResponse
    {
        $purchase = $createStockIn->handle($request->validated());

        return to_route('purchases.index')->with('success', 'Stock In berhasil disimpan dan stok FIFO telah bertambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase): View
    {
        $purchase->load(['supplier:id,name,address', 'items.product:id,sku,name,unit']);

        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase): RedirectResponse
    {
        return to_route('purchases.show', $purchase)->with('error', 'Pembelian transaksi tidak diedit; batalkan lalu buat ulang.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Purchase $purchase): RedirectResponse
    {
        return to_route('purchases.show', $purchase);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase): RedirectResponse
    {
        abort_unless(auth()->user()->role === 'admin' && $purchase->status === 'open', 403);
        $purchase->delete();

        return to_route('purchases.index')->with('success', 'Pembelian dihapus.');
    }
}
