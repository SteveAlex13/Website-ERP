<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold">Laporan</h2></x-slot>

    <div class="mx-auto grid max-w-7xl gap-6 p-4 sm:p-8">
        <form method="GET" x-data="{ period: '{{ $period }}' }" class="grid gap-4 rounded-2xl border border-blue-900 bg-slate-900 p-5 sm:grid-cols-[1fr_1fr_auto]">
            <label class="text-sm text-blue-200">Periode
                <select name="period" x-model="period" class="mt-2 w-full rounded-xl border-blue-900 bg-slate-950 text-white">
                    <option value="day">Harian</option>
                    <option value="month">Bulanan</option>
                    <option value="year">Tahunan</option>
                </select>
            </label>
            <label x-show="period === 'day'" class="text-sm text-blue-200">Tanggal
                <input type="date" name="date" value="{{ $selectedDate->toDateString() }}" class="mt-2 w-full rounded-xl border-blue-900 bg-slate-950 text-white">
            </label>
            <label x-cloak x-show="period === 'month'" class="text-sm text-blue-200">Bulan
                <input type="month" name="month" value="{{ $selectedDate->format('Y-m') }}" class="mt-2 w-full rounded-xl border-blue-900 bg-slate-950 text-white">
            </label>
            <label x-cloak x-show="period === 'year'" class="text-sm text-blue-200">Tahun
                <select name="year" class="mt-2 w-full rounded-xl border-blue-900 bg-slate-950 text-white">
                    @foreach(range(now()->year + 1, 2000) as $year)
                        <option value="{{ $year }}" @selected($selectedDate->year === $year)>{{ $year }}</option>
                    @endforeach
                </select>
            </label>
            <button class="self-end rounded-xl bg-blue-600 px-6 py-2.5 font-semibold text-white">Tampilkan</button>
        </form>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4"><div class="rounded-2xl bg-rose-500 p-5 text-white"><small>Sales Kotor</small><b class="mt-2 block text-2xl">Rp {{ number_format((float) $grossSales, 0, ',', '.') }}</b></div><div class="rounded-2xl bg-emerald-600 p-5 text-white"><small>Sales Bersih</small><b class="mt-2 block text-2xl">Rp {{ number_format((float) $netSales, 0, ',', '.') }}</b></div><div class="rounded-2xl bg-amber-500 p-5 text-slate-950"><small>Diskon / Koreksi</small><b class="mt-2 block text-2xl">Rp {{ number_format(max(0, (float) $grossSales - (float) $netSales), 0, ',', '.') }}</b></div><div class="rounded-2xl bg-blue-600 p-5 text-white"><small>Laba Kotor (Sales Bersih - HPP)</small><b class="mt-2 block text-2xl">Rp {{ number_format((float) $netSales - (float) $costOfGoods, 0, ',', '.') }}</b></div></div>

        <div class="overflow-hidden rounded-2xl border border-blue-900 bg-slate-900"><div class="border-b border-blue-950 p-5"><h3 class="font-bold text-white">Rincian Penjualan</h3><p class="text-sm text-slate-400">HPP FIFO: Rp {{ number_format((float) $costOfGoods, 0, ',', '.') }}</p></div><div class="overflow-x-auto"><table class="min-w-full text-sm"><thead class="bg-blue-950/70"><tr><th>No</th><th>Invoice</th><th>Kasir</th><th>Customer</th><th>Qty</th><th>Metode</th><th>Sales Bersih</th></tr></thead><tbody>@forelse($sales as $sale)<tr><td>{{ $sales->firstItem() + $loop->index }}</td><td class="text-blue-300">{{ $sale->number }}</td><td>{{ $sale->salesUser?->name ?? '-' }}</td><td>{{ $sale->customer?->name ?? 'Umum' }}</td><td>{{ number_format((float) $sale->items_sum_quantity, 2, ',', '.') }}</td><td>{{ strtoupper(str_replace('_', ' ', $sale->payment_method ?? '-')) }}</td><td>Rp {{ number_format((float) $sale->total, 0, ',', '.') }}</td></tr>@empty<tr><td colspan="7" class="py-12 text-center text-slate-400">Belum ada transaksi pada periode ini.</td></tr>@endforelse</tbody></table></div></div>
        <div>{{ $sales->links() }}</div>
    </div>
</x-app-layout>
