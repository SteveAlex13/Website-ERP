<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $filters = $request->validate([
            'period' => ['nullable', 'in:daily,monthly,yearly'],
            'date' => ['nullable', 'date_format:Y-m-d'],
            'month' => ['nullable', 'date_format:Y-m'],
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
        ]);

        $period = $filters['period'] ?? 'daily';
        [$startDate, $endDate, $bucketExpression, $chartLabels] = $this->periodConfiguration($period, $filters);

        $completedSales = Sale::query()
            ->where('status', 'completed')
            ->whereBetween('date', [$startDate, $endDate]);

        $summary = (clone $completedSales)
            ->selectRaw('COUNT(*) as transaction_count, COALESCE(SUM(total), 0) as total_sales, COALESCE(SUM(paid_amount), 0) as total_payments')
            ->first();

        $productQuantity = SaleItem::query()
            ->whereHas('sale', fn ($query) => $query
                ->where('status', 'completed')
                ->whereBetween('date', [$startDate, $endDate]))
            ->sum('quantity');

        $chartResults = (clone $completedSales)
            ->selectRaw("{$bucketExpression} as bucket, COUNT(*) as transactions, COALESCE(SUM(total), 0) as revenue")
            ->groupBy('bucket')
            ->get()
            ->keyBy(fn (Sale $sale) => (int) $sale->bucket);

        $chartData = collect($chartLabels)->map(function (string $label, int $bucket) use ($chartResults): array {
            $result = $chartResults->get($bucket);

            return [
                'label' => $label,
                'transactions' => (int) ($result?->transactions ?? 0),
                'revenue' => (float) ($result?->revenue ?? 0),
            ];
        });

        return view('dashboard', [
            'period' => $period,
            'selectedDate' => $filters['date'] ?? now()->toDateString(),
            'selectedMonth' => $filters['month'] ?? now()->format('Y-m'),
            'selectedYear' => (int) ($filters['year'] ?? now()->year),
            'periodLabel' => $this->periodLabel($period, $startDate),
            'totalSales' => (float) $summary->total_sales,
            'totalPayments' => (float) $summary->total_payments,
            'transactionCount' => (int) $summary->transaction_count,
            'productQuantity' => (float) $productQuantity,
            'chartData' => $chartData,
            'maxRevenue' => max(1, (float) $chartData->max('revenue')),
            'maxTransactions' => max(1, (int) $chartData->max('transactions')),
            'recentSales' => (clone $completedSales)
                ->with(['customer:id,name', 'bankAccount:id,name'])
                ->latest('created_at')
                ->limit(6)
                ->get(['id', 'number', 'customer_id', 'bank_account_id', 'date', 'total', 'payment_method', 'created_at']),
        ]);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array{CarbonImmutable, CarbonImmutable, string, array<int, string>}
     */
    private function periodConfiguration(string $period, array $filters): array
    {
        if ($period === 'monthly') {
            $date = CarbonImmutable::createFromFormat('!Y-m', $filters['month'] ?? now()->format('Y-m'));

            return [$date->startOfMonth(), $date->endOfMonth(), 'DAY(date)', array_combine(range(1, $date->daysInMonth), range(1, $date->daysInMonth))];
        }

        if ($period === 'yearly') {
            $date = CarbonImmutable::create((int) ($filters['year'] ?? now()->year), 1, 1);

            return [$date->startOfYear(), $date->endOfYear(), 'MONTH(date)', [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']];
        }

        $date = CarbonImmutable::createFromFormat('!Y-m-d', $filters['date'] ?? now()->toDateString());
        $labels = [];

        foreach (range(0, 23) as $hour) {
            $labels[$hour] = str_pad((string) $hour, 2, '0', STR_PAD_LEFT);
        }

        return [$date->startOfDay(), $date->endOfDay(), 'HOUR(created_at)', $labels];
    }

    private function periodLabel(string $period, CarbonImmutable $date): string
    {
        return match ($period) {
            'monthly' => $date->translatedFormat('F Y'),
            'yearly' => $date->format('Y'),
            default => $date->translatedFormat('d F Y'),
        };
    }
}
