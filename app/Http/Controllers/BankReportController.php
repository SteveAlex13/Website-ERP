<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankTransaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BankReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $period = in_array($request->input('period'), ['day', 'month', 'year'], true) ? $request->input('period') : 'day';
        $selectedDate = $request->date('date') ?? now();
        [$startDate, $endDate] = match ($period) {
            'month' => [$selectedDate->copy()->startOfMonth(), $selectedDate->copy()->endOfMonth()],
            'year' => [$selectedDate->copy()->startOfYear(), $selectedDate->copy()->endOfYear()],
            default => [$selectedDate->copy()->startOfDay(), $selectedDate->copy()->endOfDay()],
        };
        $transactions = BankTransaction::query()->with(['bankAccount:id,name', 'sale:id,number'])
            ->whereBetween('transaction_at', [$startDate, $endDate])->latest('transaction_at')->paginate(15)->withQueryString();

        return view('reports.bank', [
            'transactions' => $transactions,
            'accounts' => BankAccount::query()->orderBy('name')->get()->map(fn (BankAccount $account) => ['name' => $account->name, 'balance' => $account->balance()]),
            'period' => $period,
            'selectedDate' => $selectedDate,
            'totalIn' => BankTransaction::query()->whereBetween('transaction_at', [$startDate, $endDate])->where('type', 'credit')->sum('amount'),
            'totalOut' => BankTransaction::query()->whereBetween('transaction_at', [$startDate, $endDate])->where('type', 'debit')->sum('amount'),
        ]);
    }
}
