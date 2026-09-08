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

        <!-- SECTION GRAFIK PENJUALAN: LINE CHART & BAR CHART -->
        <section x-data="salesDashboardChart({{ Js::from($chartData) }}, {{ (float) $maxRevenue }}, {{ (int) $maxTransactions }})" class="rounded-2xl border border-blue-900 bg-slate-900 p-5 shadow-xl">
            <!-- Header Grafik -->
            <div class="flex flex-wrap items-center justify-between gap-4 border-b border-blue-950/70 pb-4">
                <div>
                    <div class="flex items-center gap-2.5">
                        <h3 class="text-lg font-bold text-white">Grafik Penjualan</h3>
                        <span class="rounded-lg bg-indigo-500/20 px-2.5 py-0.5 text-xs font-semibold text-indigo-300 border border-indigo-500/30">
                            {{ $period === 'daily' ? 'Per Jam (Harian)' : ($period === 'monthly' ? 'Per Hari (Bulanan)' : 'Per Bulan (Tahunan)') }}
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-slate-400">Perubahan tren omzet penjualan dan volume transaksi pada {{ $periodLabel }}.</p>
                </div>

                <!-- Kontrol Tipe Grafik (Garis vs Batang) & Filter Metrik -->
                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <!-- Switcher Line vs Bar -->
                    <div class="inline-flex rounded-xl border border-blue-900 bg-slate-950 p-1">
                        <button type="button" @click="setChartType('line')" :class="chartType === 'line' ? 'bg-indigo-600 text-white font-bold shadow' : 'text-slate-400 hover:text-white'" class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 transition">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                            </svg>
                            <span>Garis (Line)</span>
                        </button>
                        <button type="button" @click="setChartType('bar')" :class="chartType === 'bar' ? 'bg-indigo-600 text-white font-bold shadow' : 'text-slate-400 hover:text-white'" class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 transition">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span>Batang (Bar)</span>
                        </button>
                    </div>

                    <!-- Filter Metrik -->
                    <div class="inline-flex rounded-xl border border-blue-900 bg-slate-950 p-1">
                        <button type="button" @click="metric = 'all'" :class="metric === 'all' ? 'bg-slate-800 text-white font-semibold' : 'text-slate-400 hover:text-white'" class="rounded-lg px-2.5 py-1.5 transition">Semua</button>
                        <button type="button" @click="metric = 'revenue'" :class="metric === 'revenue' ? 'bg-slate-800 text-indigo-300 font-semibold' : 'text-slate-400 hover:text-white'" class="flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 transition">
                            <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                            Omzet
                        </button>
                        <button type="button" @click="metric = 'transactions'" :class="metric === 'transactions' ? 'bg-slate-800 text-cyan-300 font-semibold' : 'text-slate-400 hover:text-white'" class="flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 transition">
                            <span class="h-2 w-2 rounded-full bg-cyan-400"></span>
                            Transaksi
                        </button>
                    </div>
                </div>
            </div>

            <!-- Banner Titik Aktif & Perubahan Penjualan -->
            <div class="mt-4 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-blue-950 bg-slate-950/70 px-4 py-2.5 text-xs">
                <div class="flex items-center gap-2">
                    <span class="text-slate-400">Titik Terpilih:</span>
                    <b class="text-white" x-text="activePoint ? ('Waktu ' + activePoint.label) : 'Arahkan kursor pada grafik'"></b>
                </div>

                <template x-if="activePoint">
                    <div class="flex flex-wrap items-center gap-5">
                        <div class="flex items-center gap-2" x-show="metric === 'all' || metric === 'revenue'">
                            <span class="h-2.5 w-2.5 rounded-full bg-indigo-400"></span>
                            <span class="text-slate-400">Omzet:</span>
                            <b class="text-indigo-300 text-sm" x-text="formatFullRupiah(activePoint.revenue)"></b>
                            <template x-if="revenueChange">
                                <span :class="revenueChange.isUp ? 'text-emerald-400' : (revenueChange.isDown ? 'text-rose-400' : 'text-slate-400')" class="font-bold text-[11px]" x-text="(revenueChange.isUp ? '▲ +' : (revenueChange.isDown ? '▼ -' : '— ')) + formatRupiah(revenueChange.diff)"></span>
                            </template>
                        </div>
                        <div class="flex items-center gap-2" x-show="metric === 'all' || metric === 'transactions'">
                            <span class="h-2.5 w-2.5 rounded-full bg-cyan-400"></span>
                            <span class="text-slate-400">Transaksi:</span>
                            <b class="text-cyan-300 text-sm" x-text="activePoint.transactions + ' transaksi'"></b>
                            <template x-if="transactionChange">
                                <span :class="transactionChange.isUp ? 'text-emerald-400' : (transactionChange.isDown ? 'text-rose-400' : 'text-slate-400')" class="font-bold text-[11px]" x-text="(transactionChange.isUp ? '▲ +' : (transactionChange.isDown ? '▼ -' : '— ')) + transactionChange.diff"></span>
                            </template>
                        </div>
                    </div>
                </template>

                <template x-if="!activePoint">
                    <span class="text-slate-500 italic">Sorot titik pada grafik garis atau kolom batang untuk melihat rincian perubahan penjualan</span>
                </template>
            </div>

            <!-- TAMPILAN 1: GRAFIK GARIS (LINE CHART) -->
            <div x-show="chartType === 'line'" class="mt-4 overflow-x-auto pb-2">
                <div class="relative min-w-[720px] h-[300px]">
                    <svg viewBox="0 0 900 260" class="h-full w-full overflow-visible" preserveAspectRatio="none">
                        <defs>
                            <!-- Linear Gradient Omzet -->
                            <linearGradient id="revenueAreaGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#6366f1" stop-opacity="0.4"/>
                                <stop offset="75%" stop-color="#6366f1" stop-opacity="0.08"/>
                                <stop offset="100%" stop-color="#6366f1" stop-opacity="0.0"/>
                            </linearGradient>
                            <!-- Linear Gradient Transaksi -->
                            <linearGradient id="transAreaGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#22d3ee" stop-opacity="0.25"/>
                                <stop offset="85%" stop-color="#22d3ee" stop-opacity="0.04"/>
                                <stop offset="100%" stop-color="#22d3ee" stop-opacity="0.0"/>
                            </linearGradient>
                        </defs>

                        <!-- Garis Grid Horizontal -->
                        <g class="grid-lines" stroke="#1e293b" stroke-dasharray="3,3" stroke-width="1">
                            <line :x1="padLeft" :y1="padTop" :x2="svgWidth - padRight" :y2="padTop" />
                            <line :x1="padLeft" :y1="padTop + plotHeight * 0.25" :x2="svgWidth - padRight" :y2="padTop + plotHeight * 0.25" />
                            <line :x1="padLeft" :y1="padTop + plotHeight * 0.5" :x2="svgWidth - padRight" :y2="padTop + plotHeight * 0.5" />
                            <line :x1="padLeft" :y1="padTop + plotHeight * 0.75" :x2="svgWidth - padRight" :y2="padTop + plotHeight * 0.75" />
                            <line :x1="padLeft" :y1="padTop + plotHeight" :x2="svgWidth - padRight" :y2="padTop + plotHeight" stroke="#334155" stroke-dasharray="none" stroke-width="1.5" />
                        </g>

                        <!-- Label Nilai Y-Axis -->
                        <g class="text-[10px] fill-slate-400 font-medium">
                            <text :x="padLeft - 8" :y="padTop + 4" text-anchor="end" x-text="formatRupiah(maxRevenue)"></text>
                            <text :x="padLeft - 8" :y="padTop + plotHeight * 0.5 + 4" text-anchor="end" x-text="formatRupiah(maxRevenue * 0.5)"></text>
                            <text :x="padLeft - 8" :y="padTop + plotHeight + 4" text-anchor="end">Rp 0</text>
                        </g>

                        <!-- Area & Garis Omzet -->
                        <g x-show="metric === 'all' || metric === 'revenue'">
                            <path :d="revenueAreaPath" fill="url(#revenueAreaGrad)" />
                            <path :d="revenuePath" fill="none" stroke="#6366f1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        </g>

                        <!-- Area & Garis Transaksi -->
                        <g x-show="metric === 'all' || metric === 'transactions'">
                            <path :d="transactionAreaPath" fill="url(#transAreaGrad)" />
                            <path :d="transactionPath" fill="none" stroke="#22d3ee" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                        </g>

                        <!-- Garis Pelacak Kursor Hover -->
                        <template x-if="hoveredIndex !== null">
                            <line :x1="getX(hoveredIndex)" :y1="padTop" :x2="getX(hoveredIndex)" :y2="padTop + plotHeight" stroke="#818cf8" stroke-width="1.5" stroke-dasharray="3,3" />
                        </template>

                        <!-- Titik/Dot pada Garis Omzet -->
                        <template x-for="(pt, idx) in revenuePoints" :key="'rev-dot-' + idx">
                            <g x-show="metric === 'all' || metric === 'revenue'">
                                <circle 
                                    :cx="pt.x" 
                                    :cy="pt.y" 
                                    :r="hoveredIndex === idx ? 6 : 3" 
                                    :class="hoveredIndex === idx ? 'fill-indigo-400 stroke-white stroke-2' : 'fill-indigo-500 stroke-slate-950 stroke-1'"
                                />
                            </g>
                        </template>

                        <!-- Titik/Dot pada Garis Transaksi -->
                        <template x-for="(pt, idx) in transactionPoints" :key="'trans-dot-' + idx">
                            <g x-show="metric === 'all' || metric === 'transactions'">
                                <circle 
                                    :cx="pt.x" 
                                    :cy="pt.y" 
                                    :r="hoveredIndex === idx ? 5 : 2.5" 
                                    :class="hoveredIndex === idx ? 'fill-cyan-300 stroke-white stroke-2' : 'fill-cyan-400 stroke-slate-950 stroke-1'"
                                />
                            </g>
                        </template>

                        <!-- Label X-Axis -->
                        <g class="text-[10px] font-medium">
                            <template x-for="(d, idx) in data" :key="'xlabel-' + idx">
                                <text 
                                    :x="getX(idx)" 
                                    :y="svgHeight - 8" 
                                    text-anchor="middle" 
                                    :class="hoveredIndex === idx ? 'fill-indigo-300 font-bold' : 'fill-slate-400'"
                                    x-text="d.label"
                                ></text>
                            </template>
                        </g>

                        <!-- Bidang Interaktif Hover Tiap Titik -->
                        <template x-for="(d, idx) in data" :key="'hover-rect-' + idx">
                            <rect 
                                :x="getX(idx) - (plotWidth / Math.max(1, data.length * 2))" 
                                :y="0" 
                                :width="plotWidth / Math.max(1, data.length)" 
                                :height="svgHeight" 
                                fill="transparent" 
                                class="cursor-pointer"
                                @mouseenter="hoveredIndex = idx"
                                @mouseleave="hoveredIndex = null"
                            />
                        </template>
                    </svg>
                </div>
            </div>

            <!-- TAMPILAN 2: GRAFIK BATANG (BAR CHART) -->
            <div x-show="chartType === 'bar'" class="mt-4 overflow-x-auto pb-2">
                <div class="flex h-72 min-w-[720px] items-end gap-2 border-b border-l border-blue-950 px-3 pt-5">
                    @foreach($chartData as $index => $point)
                        <div 
                            class="group relative flex h-full min-w-4 flex-1 items-end justify-center gap-0.5 cursor-pointer"
                            @mouseenter="hoveredIndex = {{ $index }}"
                            @mouseleave="hoveredIndex = null"
                        >
                            <div class="absolute bottom-full z-10 hidden -translate-y-2 whitespace-nowrap rounded-lg border border-blue-900 bg-slate-950 px-3 py-2 text-xs text-white shadow-xl group-hover:block">
                                <b>{{ $point['label'] }}</b><br>Rp {{ number_format($point['revenue'], 0, ',', '.') }}<br>{{ $point['transactions'] }} transaksi
                            </div>
                            <div x-show="metric === 'all' || metric === 'revenue'" class="w-2/3 min-h-[2px] rounded-t bg-gradient-to-t from-indigo-700 to-violet-400 transition group-hover:from-indigo-500" style="height: {{ max(1, ($point['revenue'] / $maxRevenue) * 100) }}%"></div>
                            <div x-show="metric === 'all' || metric === 'transactions'" class="w-1/3 min-h-[2px] rounded-t bg-cyan-400/80" style="height: {{ max(1, ($point['transactions'] / $maxTransactions) * 100) }}%"></div>
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

<script>
function salesDashboardChart(rawChartData, maxRev, maxTrans) {
    return {
        chartType: localStorage.getItem('erp_dashboard_chart_type') || 'line',
        metric: 'all',
        hoveredIndex: null,
        data: rawChartData || [],
        maxRevenue: Math.max(1, Number(maxRev) || 1),
        maxTransactions: Math.max(1, Number(maxTrans) || 1),
        svgWidth: 900,
        svgHeight: 260,
        padLeft: 70,
        padRight: 30,
        padTop: 20,
        padBottom: 35,

        setChartType(type) {
            this.chartType = type;
            localStorage.setItem('erp_dashboard_chart_type', type);
        },

        get plotWidth() {
            return this.svgWidth - this.padLeft - this.padRight;
        },

        get plotHeight() {
            return this.svgHeight - this.padTop - this.padBottom;
        },

        getX(index) {
            if (this.data.length <= 1) return this.padLeft + this.plotWidth / 2;
            return this.padLeft + (index / (this.data.length - 1)) * this.plotWidth;
        },

        getRevenueY(val) {
            var v = Number(val) || 0;
            return this.padTop + this.plotHeight - (v / this.maxRevenue) * this.plotHeight;
        },

        getTransactionY(val) {
            var v = Number(val) || 0;
            return this.padTop + this.plotHeight - (v / this.maxTransactions) * this.plotHeight;
        },

        get revenuePoints() {
            return this.data.map((d, i) => ({
                x: this.getX(i),
                y: this.getRevenueY(d.revenue),
                data: d,
                index: i
            }));
        },

        get transactionPoints() {
            return this.data.map((d, i) => ({
                x: this.getX(i),
                y: this.getTransactionY(d.transactions),
                data: d,
                index: i
            }));
        },

        buildSmoothPath(pts) {
            if (!pts || pts.length === 0) return '';
            if (pts.length === 1) return 'M ' + pts[0].x + ' ' + pts[0].y;
            var d = 'M ' + pts[0].x + ' ' + pts[0].y;
            for (var i = 1; i < pts.length; i++) {
                var p0 = pts[i - 1];
                var p1 = pts[i];
                var cp1x = p0.x + (p1.x - p0.x) / 2;
                var cp1y = p0.y;
                var cp2x = p0.x + (p1.x - p0.x) / 2;
                var cp2y = p1.y;
                d += ' C ' + cp1x + ' ' + cp1y + ', ' + cp2x + ' ' + cp2y + ', ' + p1.x + ' ' + p1.y;
            }
            return d;
        },

        buildAreaPath(pts) {
            if (!pts || pts.length === 0) return '';
            var lineD = this.buildSmoothPath(pts);
            var bottomY = this.padTop + this.plotHeight;
            var first = pts[0];
            var last = pts[pts.length - 1];
            return lineD + ' L ' + last.x + ' ' + bottomY + ' L ' + first.x + ' ' + bottomY + ' Z';
        },

        get revenuePath() {
            return this.buildSmoothPath(this.revenuePoints);
        },

        get revenueAreaPath() {
            return this.buildAreaPath(this.revenuePoints);
        },

        get transactionPath() {
            return this.buildSmoothPath(this.transactionPoints);
        },

        get transactionAreaPath() {
            return this.buildAreaPath(this.transactionPoints);
        },

        get activePoint() {
            if (this.hoveredIndex === null || !this.data[this.hoveredIndex]) return null;
            return this.data[this.hoveredIndex];
        },

        get revenueChange() {
            if (this.hoveredIndex === null || this.hoveredIndex <= 0) return null;
            var prev = Number(this.data[this.hoveredIndex - 1].revenue) || 0;
            var curr = Number(this.data[this.hoveredIndex].revenue) || 0;
            var diff = curr - prev;
            return {
                diff: Math.abs(diff),
                isUp: diff > 0,
                isDown: diff < 0,
                isSame: diff === 0
            };
        },

        get transactionChange() {
            if (this.hoveredIndex === null || this.hoveredIndex <= 0) return null;
            var prev = Number(this.data[this.hoveredIndex - 1].transactions) || 0;
            var curr = Number(this.data[this.hoveredIndex].transactions) || 0;
            var diff = curr - prev;
            return {
                diff: Math.abs(diff),
                isUp: diff > 0,
                isDown: diff < 0,
                isSame: diff === 0
            };
        },

        formatRupiah(val) {
            var n = Number(val) || 0;
            if (n >= 1000000000) return (n / 1000000000).toFixed(1).replace(/\.0$/, '') + ' M';
            if (n >= 1000000) return (n / 1000000).toFixed(1).replace(/\.0$/, '') + ' jt';
            if (n >= 1000) return (n / 1000).toFixed(0) + ' rb';
            return new Intl.NumberFormat('id-ID').format(n);
        },

        formatFullRupiah(val) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(val) || 0);
        }
    };
}
</script>
