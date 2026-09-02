<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $perPage = in_array(request()->integer('per_page'), [10, 25, 50], true) ? request()->integer('per_page') : 10;
        $products = Product::query()
            ->with(['inventoryBatches' => fn ($query) => $query
                ->select(['id', 'product_id', 'supplier_id', 'received_at', 'remaining_quantity', 'unit_cost'])
                ->with('supplier:id,name')
                ->where('remaining_quantity', '>', 0)
                ->orderBy('received_at')
                ->orderBy('id')])
            ->when(request('search'), fn ($query, $search) => $query->where(fn ($query) => $query->where('sku', 'like', '%'.$search.'%')->orWhere('name', 'like', '%'.$search.'%')))
            ->latest()->paginate($perPage)->withQueryString();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.form', ['product' => new Product]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return to_route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        return view('products.form', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return to_route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return to_route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
