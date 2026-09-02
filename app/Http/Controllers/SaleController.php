<?php

namespace App\Http\Controllers;

use App\Actions\CreateSale;
use App\Http\Requests\StoreSaleRequest;
use App\Models\BankAccount;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Sale::class);
        $perPage = in_array(request()->integer('per_page'), [10, 25, 50], true) ? request()->integer('per_page') : 10;
        $sales = Sale::query()->select(['id', 'number', 'customer_id', 'date', 'document_type', 'status', 'total', 'paid_amount'])
            ->with('customer:id,name')
            ->when(request('search'), fn ($query, $search) => $query->where('number', 'like', '%'.$search.'%'))
            ->latest()->paginate($perPage)->withQueryString();

        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Sale::class);

        return view('sales.form', [
            'customers' => Contact::customers()->orderBy('name')->get(['id', 'name']),
            'bankAccounts' => BankAccount::query()->orderBy('name')->get(['id', 'name', 'account_number']),
            'openBills' => Sale::query()->where('document_type', 'pos')->where('status', 'open')->with('customer:id,name')->latest()->limit(10)->get(['id', 'number', 'bill_name', 'customer_id', 'total', 'created_at']),
            'products' => Product::query()
                ->where('stock', '>', 0)
                ->orderBy('name')
                ->get(['id', 'sku', 'name', 'unit', 'selling_price', 'stock']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request, CreateSale $createSale): RedirectResponse
    {
        $sale = $createSale->handle($request->validated());

        return $sale->status === 'open'
            ? to_route('sales.create')->with('success', 'Bill berhasil disimpan dan dapat dibayar nanti.')
            : to_route('sales.show', $sale)->with('success', 'Transaksi penjualan berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale): View
    {
        $this->authorize('view', $sale);
        $sale->load(['customer:id,name,address', 'salesUser:id,name', 'bankAccount:id,name', 'items.product:id,sku,name,unit,stock', 'items.deliveryOrderItems', 'deliveryOrders.items.saleItem.product']);

        return view('sales.show', [
            'sale' => $sale,
            'bankAccounts' => BankAccount::query()->orderBy('name')->get(['id', 'name', 'account_number']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale): RedirectResponse
    {
        $this->authorize('update', $sale);

        return to_route('sales.show', $sale)->with('error', 'Transaksi tidak diedit; batalkan lalu buat ulang.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Sale $sale): RedirectResponse
    {
        return to_route('sales.show', $sale);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale): RedirectResponse
    {
        $this->authorize('delete', $sale);
        $sale->delete();

        return to_route('sales.index')->with('success', 'Penjualan dihapus.');
    }

    public function printInvoice(Sale $sale): View
    {
        $this->authorize('view', $sale);
        $sale->load(['customer', 'salesUser', 'items.product']);

        return view('print.invoice', compact('sale'));
    }
}
