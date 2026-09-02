<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest;
use App\Models\Asset;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $perPage = in_array(request()->integer('per_page'), [10, 25, 50], true) ? request()->integer('per_page') : 10;
        $assets = Asset::query()
            ->when(request('search'), fn ($query, $search) => $query->where('name', 'like', '%'.$search.'%'))
            ->latest()->paginate($perPage)->withQueryString();

        return view('assets.index', compact('assets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('assets.form', ['asset' => new Asset]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssetRequest $request): RedirectResponse
    {
        Asset::create($request->validated());

        return to_route('assets.index')->with('success', 'Aset berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Asset $asset)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asset $asset): View
    {
        return view('assets.form', compact('asset'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreAssetRequest $request, Asset $asset): RedirectResponse
    {
        $asset->update($request->validated());

        return to_route('assets.index')->with('success', 'Aset diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset): RedirectResponse
    {
        $asset->delete();

        return to_route('assets.index')->with('success', 'Aset dihapus.');
    }
}
