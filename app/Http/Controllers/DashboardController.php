<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\CashExpense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'productCount' => Product::count(),
            'lowStockCount' => Product::query()->whereColumn('stock', '<=', 'minimum_stock')->count(),
            'openPurchaseCount' => Purchase::query()->whereIn('status', ['open', 'partial'])->count(),
            'openSaleCount' => Sale::query()->whereIn('status', ['open', 'partial'])->count(),
            'receivable' => Sale::query()->selectRaw('COALESCE(SUM(total - paid_amount), 0) as aggregate')->value('aggregate'),
            'monthlyExpense' => CashExpense::query()->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount'),
            'assetCount' => Asset::count(),
        ]);
    }
}
