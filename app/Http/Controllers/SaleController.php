<?php

namespace App\Http\Controllers;

use App\Actions\CreateSale;
use App\Http\Requests\DeleteQuotationRequest;
use App\Http\Requests\StoreSaleRequest;
use App\Models\BankAccount;
use App\Models\Contact;
use App\Models\DeliveryOrder;
use App\Models\Product;
use App\Models\ProductReturn;
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

        $sales = Sale::query()
            ->with(['customer:id,name', 'items.deliveryOrderItems'])
            ->whereIn('document_type', ['quotation', 'order'])
            ->when(request('search'), fn ($query, $search) => $query->where('number', 'like', '%'.$search.'%'))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $offlineSales = Sale::query()
            ->with(['customer:id,name', 'salesUser:id,name'])
            ->where('document_type', 'pos')
            ->when(request('search'), fn ($query, $search) => $query->where('number', 'like', '%'.$search.'%'))
            ->latest()
            ->paginate($perPage, ['*'], 'offline_page')
            ->withQueryString();

        $deliveryOrders = DeliveryOrder::query()
            ->with(['sale.customer:id,name', 'items.saleItem.product:id,name,unit'])
            ->latest('date')
            ->latest('id')
            ->paginate($perPage, ['*'], 'do_page')
            ->withQueryString();

        $invoices = Sale::query()
            ->with(['customer:id,name', 'bankAccount:id,name'])
            ->whereIn('document_type', ['quotation', 'order'])
            ->latest('date')
            ->paginate($perPage, ['*'], 'inv_page')
            ->withQueryString();

        $salesReturns = ProductReturn::query()
            ->where('type', 'sale')
            ->with(['items.product:id,name,unit'])
            ->latest('date')
            ->paginate($perPage, ['*'], 'ret_page')
            ->withQueryString();

        $receivables = Sale::query()
            ->whereColumn('paid_amount', '<', 'total')
            ->with('customer:id,name')
            ->latest('date')
            ->paginate($perPage, ['*'], 'rec_page')
            ->withQueryString();

        $activePoCount = Sale::query()->where('document_type', 'order')->whereIn('status', ['open', 'partial'])->count();
        $partialDeliveryCount = Sale::query()->where('status', 'partial')->count();
        $totalReceivable = (float) (Sale::query()->selectRaw('COALESCE(SUM(total - paid_amount), 0) as aggregate')->value('aggregate') ?? 0);
        $unpaidInvoiceCount = Sale::query()->whereColumn('paid_amount', '<', 'total')->count();
        $totalSalesMonth = (float) Sale::query()->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])->sum('total');
        $commissionEstimate = $totalSalesMonth * 0.02; // 2% estimasi komisi periode

        return view('sales.index', compact(
            'sales',
            'offlineSales',
            'deliveryOrders',
            'invoices',
            'salesReturns',
            'receivables',
            'activePoCount',
            'partialDeliveryCount',
            'totalReceivable',
            'unpaidInvoiceCount',
            'totalSalesMonth',
            'commissionEstimate'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Sale::class);
        $isOnline = request('mode') === 'online';

        return view('sales.form', [
            'isOnline' => $isOnline,
            'customers' => Contact::customers()->orderBy('name')->get(['id', 'name']),
            'bankAccounts' => BankAccount::query()
                ->whereIn('name', ['Tunai', 'Bank BCA', 'Bank BNI', 'Bank Mandiri'])
                ->orderBy('name')
                ->get(['id', 'name', 'account_number']),
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

        if ($sale->document_type === 'pos') {
            $message = $sale->status === 'open'
                ? 'Bill berhasil disimpan dan dapat dibayar nanti.'
                : 'Transaksi penjualan offline berhasil disimpan.';

            return to_route('sales.create')->with('success', $message);
        }

        return to_route('sales.show', $sale)->with('success', 'Transaksi penjualan online berhasil disimpan.');
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
    public function destroy(DeleteQuotationRequest $request, Sale $sale): RedirectResponse
    {
        $request->validated();
        $sale->delete();

        return to_route('sales.index')->with('success', 'Quotation berhasil dihapus.');
    }

    public function printInvoice(Sale $sale): View
    {
        $this->authorize('view', $sale);
        $sale->load(['customer', 'salesUser', 'items.product']);

        return view('print.invoice', compact('sale'));
    }
}
