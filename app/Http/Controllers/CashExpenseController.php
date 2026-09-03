<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCashExpenseRequest;
use App\Models\BankAccount;
use App\Models\BankTransaction;
use App\Models\CashExpense;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class CashExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $period = in_array(request('period'), ['day', 'month', 'year'], true) ? request('period') : 'day';
        $selectedDate = match ($period) {
            'month' => request()->date('month', 'Y-m') ?? now(),
            'year' => now()->setYear($this->selectedYear()),
            default => request()->date('date') ?? now(),
        };
        [$startDate, $endDate] = match ($period) {
            'month' => [$selectedDate->copy()->startOfMonth(), $selectedDate->copy()->endOfMonth()],
            'year' => [$selectedDate->copy()->startOfYear(), $selectedDate->copy()->endOfYear()],
            default => [$selectedDate->copy()->startOfDay(), $selectedDate->copy()->endOfDay()],
        };
        $expenses = CashExpense::query()->with('bankAccount:id,name')->latest('date')->paginate(10)->withQueryString();
        $bankAccounts = BankAccount::query()
            ->withSum(['transactions as credit_sum' => fn ($query) => $query->where('type', 'credit')], 'amount')
            ->withSum(['transactions as debit_sum' => fn ($query) => $query->where('type', 'debit')], 'amount')
            ->withSum('cashExpenses', 'amount')
            ->orderBy('name')
            ->get();
        $transactions = BankTransaction::query()
            ->with(['bankAccount:id,name', 'sale:id,number,bill_name'])
            ->whereBetween('transaction_at', [$startDate, $endDate])
            ->latest('transaction_at')
            ->paginate(15, ['*'], 'transactions_page')
            ->withQueryString();

        return view('cash-expenses.index', [
            'expenses' => $expenses,
            'bankAccounts' => $bankAccounts,
            'transactions' => $transactions,
            'period' => $period,
            'selectedDate' => $selectedDate,
            'totalIn' => BankTransaction::query()->whereBetween('transaction_at', [$startDate, $endDate])->where('type', 'credit')->sum('amount'),
            'totalOut' => BankTransaction::query()->whereBetween('transaction_at', [$startDate, $endDate])->where('type', 'debit')->sum('amount'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('cash-expenses.form', ['expense' => new CashExpense, 'bankAccounts' => BankAccount::query()->orderBy('name')->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCashExpenseRequest $request): RedirectResponse
    {
        $account = BankAccount::findOrFail($request->integer('bank_account_id'));
        if ($account->balance() < $request->float('amount')) {
            throw ValidationException::withMessages(['amount' => 'Saldo akun tidak mencukupi.']);
        }

        CashExpense::create($request->validated());

        return to_route('cash-expenses.index')->with('success', 'Pengeluaran berhasil dicatat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CashExpense $cashExpense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CashExpense $cashExpense): View
    {
        return view('cash-expenses.form', ['expense' => $cashExpense, 'bankAccounts' => BankAccount::query()->orderBy('name')->get()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCashExpenseRequest $request, CashExpense $cashExpense): RedirectResponse
    {
        $cashExpense->update($request->validated());

        return to_route('cash-expenses.index')->with('success', 'Pengeluaran diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashExpense $cashExpense): RedirectResponse
    {
        $cashExpense->delete();

        return to_route('cash-expenses.index')->with('success', 'Pengeluaran dihapus.');
    }

    private function selectedYear(): int
    {
        $year = request()->integer('year', now()->year);

        return $year >= 2000 && $year <= now()->year + 1 ? $year : now()->year;
    }
}
