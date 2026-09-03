<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpensePurchaseRequest;
use App\Models\Contact;
use App\Models\ExpensePurchase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ExpensePurchaseController extends Controller
{
    public function index(): View
    {
        $documentTypes = ['quotation_po', 'delivery_order', 'invoice', 'retur', 'piutang'];
        $activeDocumentType = in_array(request('document_type'), $documentTypes, true) ? request('document_type') : 'quotation_po';
        $perPage = in_array(request()->integer('per_page'), [10, 25, 50], true) ? request()->integer('per_page') : 10;
        $purchaseTypeQuery = ExpensePurchase::query()
            ->when(request('purchase_type'), fn ($query, $purchaseType) => $query->where('purchase_type', $purchaseType));
        $expensePurchases = (clone $purchaseTypeQuery)->with('supplier:id,name')
            ->when(request('search'), fn ($query, $search) => $query->where(fn ($query) => $query->where('number', 'like', '%'.$search.'%')->orWhere('description', 'like', '%'.$search.'%')))
            ->where('document_type', $activeDocumentType)
            ->latest()->paginate($perPage)->withQueryString();
        $documentCounts = (clone $purchaseTypeQuery)
            ->selectRaw('document_type, COUNT(*) as aggregate')
            ->groupBy('document_type')
            ->pluck('aggregate', 'document_type');
        $outstandingPurchaseQuery = (clone $purchaseTypeQuery)->whereColumn('paid_amount', '<', 'total');
        $totalOutstanding = (float) (clone $outstandingPurchaseQuery)->sum('total') - (float) (clone $outstandingPurchaseQuery)->sum('paid_amount');
        $unpaidPurchaseCount = (clone $outstandingPurchaseQuery)->count();

        return view('expense-purchases.index', compact('expensePurchases', 'documentCounts', 'activeDocumentType', 'totalOutstanding', 'unpaidPurchaseCount'));
    }

    public function create(): View
    {
        $expensePurchase = new ExpensePurchase;

        if (in_array(request('document_type'), ['quotation_po', 'delivery_order', 'invoice', 'retur', 'piutang'], true)) {
            $expensePurchase->document_type = request('document_type');
        }

        return view('expense-purchases.form', ['expensePurchase' => $expensePurchase, 'suppliers' => Contact::suppliers()->orderBy('name')->get(['id', 'name'])]);
    }

    public function store(StoreExpensePurchaseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['payment_status'] = $this->paymentStatus($data);
        ExpensePurchase::create($data);

        return to_route('expense-purchases.index')->with('success', 'Pembelian berhasil dicatat.');
    }

    public function edit(ExpensePurchase $expensePurchase): View
    {
        return view('expense-purchases.form', ['expensePurchase' => $expensePurchase, 'suppliers' => Contact::suppliers()->orderBy('name')->get(['id', 'name'])]);
    }

    public function update(StoreExpensePurchaseRequest $request, ExpensePurchase $expensePurchase): RedirectResponse
    {
        $data = $request->validated();
        $data['payment_status'] = $this->paymentStatus($data);
        $expensePurchase->update($data);

        return to_route('expense-purchases.index')->with('success', 'Pembelian berhasil diperbarui.');
    }

    private function paymentStatus(array $data): string
    {
        return (float) $data['paid_amount'] >= (float) $data['total'] ? 'paid' : ((float) $data['paid_amount'] > 0 ? 'partial' : 'unpaid');
    }
}
