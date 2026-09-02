<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DailyFinancialReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $selectedDate = $request->date('date') ?? now();
        $saleIds = Sale::query()->where('status', 'completed')->whereDate('date', $selectedDate)->select('id');
        $grossSales = SaleItem::query()->whereIn('sale_id', $saleIds)->selectRaw('COALESCE(SUM(quantity * price), 0)')->value('aggregate');
        $costOfGoods = SaleItem::query()->whereIn('sale_id', $saleIds)->sum('cost_of_goods_sold');
        $netSales = Sale::query()->where('status', 'completed')->whereDate('date', $selectedDate)->sum('total');
        $sales = Sale::query()->with(['customer:id,name', 'salesUser:id,name'])->withSum('items', 'quantity')
            ->where('status', 'completed')->whereDate('date', $selectedDate)->latest()->paginate(15)->withQueryString();

        return view('reports.daily-financial', compact('selectedDate', 'grossSales', 'netSales', 'costOfGoods', 'sales'));
    }
}
