<?php

namespace App\Http\Controllers;

use App\Actions\ProcessProductReturn;
use App\Http\Requests\StoreProductReturnRequest;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProductReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $perPage = in_array(request()->integer('per_page'), [10, 25, 50], true) ? request()->integer('per_page') : 10;
        $productReturns = ProductReturn::query()->with('items.product:id,name')
            ->when(request('search'), fn ($query, $search) => $query->where('number', 'like', '%'.$search.'%'))
            ->latest()->paginate($perPage)->withQueryString();

        return view('product-returns.index', compact('productReturns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('product-returns.form', [
            'products' => Product::query()->orderBy('name')->get(['id', 'name', 'stock']),
            'sales' => Sale::query()->whereIn('status', ['partial', 'completed'])->latest()->get(['id', 'number']),
            'purchases' => Purchase::query()->whereIn('status', ['partial', 'completed'])->latest()->get(['id', 'number']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductReturnRequest $request, ProcessProductReturn $processProductReturn): RedirectResponse
    {
        $processProductReturn->handle($request->validated());

        return to_route('product-returns.index')->with('success', 'Retur berhasil diproses dan stok diperbarui.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductReturn $productReturn): RedirectResponse
    {
        return to_route('product-returns.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductReturn $productReturn): RedirectResponse
    {
        return to_route('product-returns.index')->with('error', 'Retur yang sudah diproses tidak dapat diedit.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductReturn $productReturn): RedirectResponse
    {
        return to_route('product-returns.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductReturn $productReturn): RedirectResponse
    {
        abort(405);
    }
}
