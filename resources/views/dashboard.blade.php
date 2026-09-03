<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-blue-400">ERP Perusahaan Dagang</p>
            <h2 class="text-2xl font-bold text-white">Dashboard</h2>
            <p class="text-sm text-blue-300">Ringkasan performa penjualan dan pembayaran.</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-5 px-4 py-8 sm:px-6 lg:px-8" x-data="{ period: '{{ $period }}' }">
        <form method="GET" class="grid gap-4 rounded-2xl border border-blue-900 bg-slate-900 p-5 shadow-xl lg:grid-cols-[0.8fr_1.5fr_auto]">
            <label class="text-sm font-medium text-blue-200">
                Periode
                <select name="period" x-model="period" class="mt-2 w-full rounded-xl border-blue-900 bg-slate-950 text-white focus:border-blue-500 focus:ring-blue-500">
                    <option value="daily">Harian</option>
                    <option value="monthly">Bulanan</option>
                    <option value="yearly">Tahunan</option>
                </select>
            </label>
            <label x-show="period === 'daily'" class="text-sm font-medium text-blue-200">
                Tanggal
                <input type="date" name="date" value="{{ $selectedDate }}" class="mt-2 w-full rounded-xl border-blue-900 bg-slate-950 text-white focus:border-blue-500 focus:ring-blue-500">
            </label>
            <label x-cloak x-show="period === 'monthly'" class="text-sm font-medium text-blue-200">
                Bulan
                <input type="month" name="month" value="{{ $selectedMonth }}" class="mt-2 w-full rounded-xl border-blue-900 bg-slate-950 text-white focus:border-blue-500 focus:ring-blue-500">
            </label>
            <label x-cloak x-show="period === 'yearly'" class="text-sm font-medium text-blue-200">
                Tahun
                <select name="year" class="mt-2 w-full rounded-xl border-blue-900 bg-slate-950 text-white focus:border-blue-500 focus:ring-blue-500">
                    @foreach(range(now()->year, now()->year - 10) as $year)
                        <option value="{{ $year }}" @selected($selectedYear === $year)>{{ $year }}</option>
                    @endforeach
                </select>
            </label>
            <div class="flex items-end">
                <button class="w-full rounded-xl bg-indigo-600 px-8 py-2.5 font-bold text-white transition hover:bg-indigo-500">Tampilkan</button>
            </div>
        </form>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-lg font-bold text-white">Ringkasan {{ $periodLabel }}</h3>
                <p class="text-sm text-slate-400">Hanya transaksi berstatus selesai yang dihitung.</p>
            </div>
            <a href="{{ route('sales.create') }}" class="rounded-xl border border-blue-800 bg-blue-950/60 px-4 py-2 text-sm font-semibold text-blue-200 transition hover:bg-blue-900">Buka POS Kasir</a>
        </div>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl border border-indigo-500/40 bg-gradient-to-br from-indigo-600 to-violet-700 p-5 text-white shadow-xl shadow-indigo-950/30">
                <p class="text-xs font-bold uppercase tracking-wider text-indigo-100">Total Penjualan</p>
                <p class="mt-3 text-2xl font-bold">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
                <p class="mt-2 text-xs text-indigo-100">Nilai transaksi selesai</p>
            </article>
            <article class="rounded-2xl border border-blue-800 bg-slate-900 p-5 text-white shadow-xl">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-300">Pembayaran Diterima</p>
                <p class="mt-3 text-2xl font-bold">Rp {{ number_format($totalPayments, 0, ',', '.') }}</p>
                <p class="mt-2 text-xs text-emerald-400">Masuk melalui Tunai dan Bank</p>
            </article>
            <article class="rounded-2xl border border-blue-800 bg-slate-900 p-5 text-white shadow-xl">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-300">Total Transaksi</p>
                <p class="mt-3 text-3xl font-bold">{{ number_format($transactionCount, 0, ',', '.') }}</p>
                <p class="mt-2 text-xs text-slate-400">Transaksi berhasil diselesaikan</p>
            </article>
            <article class="rounded-2xl border border-blue-800 bg-slate-900 p-5 text-white shadow-xl">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-300">Produk Terjual</p>
                <p class="mt-3 text-3xl font-bold">{{ number_format($productQuantity, 0, ',', '.') }}</p>
                <p class="mt-2 text-xs text-slate-400">Total kuantitas seluruh item</p>
            </article>
        </section>

        <section class="rounded-2xl border border-blue-900 bg-slate-900 p-5 shadow-xl">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h3 class="font-bold text-white">Grafik Penjualan</h3>
                    <p class="text-sm text-slate-400">Perbandingan omzet dan jumlah transaksi pada {{ $periodLabel }}.</p>
                </div>
                <div class="flex gap-4 text-xs text-slate-300">
                    <span class="flex items-center gap-2"><i class="h-2.5 w-2.5 rounded-full bg-indigo-500"></i>Omzet</span>
                    <span class="flex items-center gap-2"><i class="h-2.5 w-2.5 rounded-full bg-cyan-400"></i>Transaksi</span>
                </div>
            </div>
            <div class="mt-6 overflow-x-auto pb-2">
                <div class="flex h-72 min-w-[720px] items-end gap-2 border-b border-l border-blue-950 px-3 pt-5">
                    @foreach($chartData as $point)
                        <div class="group relative flex h-full min-w-4 flex-1 items-end justify-center gap-0.5">
                            <div class="absolute bottom-full z-10 hidden -translate-y-2 whitespace-nowrap rounded-lg border border-blue-900 bg-slate-950 px-3 py-2 text-xs text-white shadow-xl group-hover:block">
                                <b>{{ $point['label'] }}</b><br>Rp {{ number_format($point['revenue'], 0, ',', '.') }}<br>{{ $point['transactions'] }} transaksi
                            </div>
                            <div class="w-2/3 min-h-[2px] rounded-t bg-gradient-to-t from-indigo-700 to-violet-400 transition group-hover:from-indigo-500" style="height: {{ max(1, ($point['revenue'] / $maxRevenue) * 100) }}%"></div>
                            <div class="w-1/3 min-h-[2px] rounded-t bg-cyan-400/80" style="height: {{ max(1, ($point['transactions'] / $maxTransactions) * 100) }}%"></div>
                            <span class="absolute top-full pt-2 text-[10px] text-slate-500">{{ $point['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-blue-900 bg-slate-900 shadow-xl">
            <div class="flex items-center justify-between gap-4 border-b border-blue-950 px-5 py-4">
                <div><h3 class="font-bold text-white">Transaksi Terbaru</h3><p class="text-xs text-slate-400">Transaksi selesai dalam periode terpilih.</p></div>
                <a href="{{ route('sales.index', ['tab' => 'offline']) }}" class="text-sm font-semibold text-blue-300 hover:text-blue-200">Lihat semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-950/50 text-xs uppercase text-blue-300"><tr><th class="px-5 py-3 text-left">Dokumen</th><th class="px-5 py-3 text-left">Customer</th><th class="px-5 py-3 text-left">Pembayaran</th><th class="px-5 py-3 text-right">Total</th><th class="px-5 py-3 text-center">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-blue-950">
                        @forelse($recentSales as $sale)
                            <tr class="text-slate-300 hover:bg-blue-950/20"><td class="px-5 py-3"><b class="text-white">{{ $sale->number }}</b><small class="block text-blue-300">{{ $sale->date->format('d M Y') }}</small></td><td class="px-5 py-3">{{ $sale->customer?->name ?? 'Pelanggan Umum' }}</td><td class="px-5 py-3">{{ $sale->bankAccount?->name ?? '-' }}</td><td class="px-5 py-3 text-right font-bold text-white">Rp {{ number_format((float) $sale->total, 0, ',', '.') }}</td><td class="px-5 py-3 text-center"><a href="{{ route('sales.show', $sale) }}" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500">Detail</a></td></tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-12 text-center text-slate-400">Belum ada transaksi selesai pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
