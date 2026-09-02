<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\CashExpense;
use App\Models\ExpensePurchase;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __invoke(Request $request): View
    {
        $startDate = $request->filled('start_date')
            ? $request->date('start_date')
            : now()->startOfMonth();
        $endDate = $request->filled('end_date')
            ? $request->date('end_date')
            : now()->endOfMonth();

        return view('reports.index', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'salesTotal' => Sale::query()->whereBetween('date', [$startDate, $endDate])->sum('total'),
            'purchaseTotal' => Purchase::query()->whereBetween('date', [$startDate, $endDate])->sum('total'),
            'expenseTotal' => CashExpense::query()->whereBetween('date', [$startDate, $endDate])->sum('amount'),
            'needsPurchaseTotal' => ExpensePurchase::query()->whereBetween('date', [$startDate, $endDate])->sum('total'),
            'receivables' => Sale::query()->whereColumn('paid_amount', '<', 'total')->with('customer:id,name')->latest()->get(),
            'payables' => Purchase::query()->whereColumn('paid_amount', '<', 'total')->with('supplier:id,name')->latest()->get(),
            'expensePayables' => ExpensePurchase::query()->whereColumn('paid_amount', '<', 'total')->with('supplier:id,name')->latest()->get(),
            'lowStockProducts' => Product::query()->whereColumn('stock', '<=', 'minimum_stock')->orderBy('stock')->get(),
            'assets' => Asset::query()->orderBy('acquired_at')->get(),
        ]);
    }
}
