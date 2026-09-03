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
        $period = in_array($request->input('period'), ['day', 'month', 'year'], true) ? $request->input('period') : 'day';
        $selectedDate = match ($period) {
            'month' => $request->date('month', 'Y-m') ?? now(),
            'year' => now()->setYear($this->selectedYear($request)),
            default => $request->date('date') ?? now(),
        };
        [$startDate, $endDate] = match ($period) {
            'month' => [$selectedDate->copy()->startOfMonth(), $selectedDate->copy()->endOfMonth()],
            'year' => [$selectedDate->copy()->startOfYear(), $selectedDate->copy()->endOfYear()],
            default => [$selectedDate->copy()->startOfDay(), $selectedDate->copy()->endOfDay()],
        };
        $saleIds = Sale::query()->where('status', 'completed')->whereBetween('date', [$startDate, $endDate])->select('id');
        $grossSales = SaleItem::query()->whereIn('sale_id', $saleIds)->selectRaw('COALESCE(SUM(quantity * price), 0)')->value('aggregate');
        $costOfGoods = SaleItem::query()->whereIn('sale_id', $saleIds)->sum('cost_of_goods_sold');
        $netSales = Sale::query()->where('status', 'completed')->whereBetween('date', [$startDate, $endDate])->sum('total');
        $sales = Sale::query()->with(['customer:id,name', 'salesUser:id,name'])->withSum('items', 'quantity')
            ->where('status', 'completed')->whereBetween('date', [$startDate, $endDate])->latest()->paginate(15)->withQueryString();

        return view('reports.daily-financial', compact('period', 'selectedDate', 'grossSales', 'netSales', 'costOfGoods', 'sales'));
    }

    private function selectedYear(Request $request): int
    {
        $year = $request->integer('year', now()->year);

        return $year >= 2000 && $year <= now()->year + 1 ? $year : now()->year;
    }
}
