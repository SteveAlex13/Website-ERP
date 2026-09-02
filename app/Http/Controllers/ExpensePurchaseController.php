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
        $perPage = in_array(request()->integer('per_page'), [10, 25, 50], true) ? request()->integer('per_page') : 10;
        $expensePurchases = ExpensePurchase::query()->with('supplier:id,name')
            ->when(request('search'), fn ($query, $search) => $query->where(fn ($query) => $query->where('number', 'like', '%'.$search.'%')->orWhere('description', 'like', '%'.$search.'%')))
            ->latest()->paginate($perPage)->withQueryString();

        return view('expense-purchases.index', compact('expensePurchases'));
    }

    public function create(): View
    {
        return view('expense-purchases.form', ['expensePurchase' => new ExpensePurchase, 'suppliers' => Contact::suppliers()->orderBy('name')->get(['id', 'name'])]);
    }

    public function store(StoreExpensePurchaseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['payment_status'] = $this->paymentStatus($data);
        ExpensePurchase::create($data);

        return to_route('expense-purchases.index')->with('success', 'Pembelian kebutuhan berhasil dicatat.');
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

        return to_route('expense-purchases.index')->with('success', 'Pembelian kebutuhan diperbarui.');
    }

    private function paymentStatus(array $data): string
    {
        return (float) $data['paid_amount'] >= (float) $data['total'] ? 'paid' : ((float) $data['paid_amount'] > 0 ? 'partial' : 'unpaid');
    }
}
