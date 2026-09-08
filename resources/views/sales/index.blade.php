<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-blue-400">ERP Perusahaan Dagang</p>
                <h2 class="text-2xl font-bold text-white">Penjualan</h2>
                <p class="text-xs text-blue-300 mt-0.5">Quotation, PO, diskon, komisi, dan piutang customer.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('sales.create', ['mode' => 'online']) }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow-lg transition hover:bg-indigo-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    + Buat Quotation / PO
                </a>
            </div>
        </div>
    </x-slot>

    <div x-data="{ tab: '{{ request('tab', 'orders') }}' }" class="mx-auto max-w-7xl space-y-6 p-4 sm:p-8">
        
        <!-- 5 TABS NAVIGATION -->
        <div class="flex items-center gap-2 overflow-x-auto rounded-2xl border border-blue-900/60 bg-slate-900/90 p-1.5 shadow-xl text-xs font-bold">
            <button type="button" @click="tab = 'orders'" :class="tab === 'orders' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60'" class="flex items-center gap-2 rounded-xl px-4 py-2.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Quotation / PO
            </button>
            <button type="button" @click="tab = 'offline'" :class="tab === 'offline' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60'" class="flex shrink-0 items-center gap-2 rounded-xl px-4 py-2.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M5 6h14l1 4v8a2 2 0 01-2 2H6a2 2 0 01-2-2v-8l1-4zm4 8h6"/></svg>
                Penjualan Offline
            </button>
            <button type="button" @click="tab = 'delivery'" :class="tab === 'delivery' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60'" class="flex items-center gap-2 rounded-xl px-4 py-2.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                Delivery Order
            </button>
            <button type="button" @click="tab = 'invoice'" :class="tab === 'invoice' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60'" class="flex items-center gap-2 rounded-xl px-4 py-2.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                Invoice
            </button>
            <button type="button" @click="tab = 'retur'" :class="tab === 'retur' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60'" class="flex items-center gap-2 rounded-xl px-4 py-2.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H4m0 0l3-3m-3 3l3 3m5 4v1a4 4 0 004 4h8m0 0l-3-3m3 3l-3 3"/></svg>
                Retur
            </button>
            <button type="button" @click="tab = 'piutang'" :class="tab === 'piutang' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800/60'" class="flex items-center gap-2 rounded-xl px-4 py-2.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Piutang
            </button>
        </div>

        <!-- 4 KPI SUMMARY METRIC CARDS -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            
            <!-- Card 1: PO Aktif -->
            <div class="rounded-2xl border border-blue-900 bg-slate-900/90 p-5 shadow-xl transition hover:-translate-y-0.5">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">PO Aktif</div>
                <div class="mt-2 text-3xl font-extrabold text-white">{{ $activePoCount }}</div>
                <div class="mt-1 flex items-center gap-1.5 text-xs text-blue-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                    Order berjalan
                </div>
            </div>

            <!-- Card 2: Pengiriman Parsial -->
            <div class="rounded-2xl border border-blue-900 bg-slate-900/90 p-5 shadow-xl transition hover:-translate-y-0.5">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Pengiriman Parsial</div>
                <div class="mt-2 text-3xl font-extrabold text-white">{{ $partialDeliveryCount }}</div>
                <div class="mt-1 flex items-center gap-1.5 text-xs text-amber-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                    Menunggu pengiriman
                </div>
            </div>

            <!-- Card 3: Piutang Dagang -->
            <div class="rounded-2xl border border-blue-900 bg-slate-900/90 p-5 shadow-xl transition hover:-translate-y-0.5">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Piutang Dagang</div>
                <div class="mt-2 text-3xl font-extrabold text-white">
                    Rp {{ $totalReceivable >= 1000000 ? number_format($totalReceivable / 1000000, 1, ',', '.') . ' jt' : number_format($totalReceivable, 0, ',', '.') }}
                </div>
                <div class="mt-1 flex items-center gap-1.5 text-xs text-rose-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span>
                    {{ $unpaidInvoiceCount }} invoice belum lunas
                </div>
            </div>

            <!-- Card 4: Komisi Sales -->
            <div class="rounded-2xl border border-blue-900 bg-slate-900/90 p-5 shadow-xl transition hover:-translate-y-0.5">
                <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Komisi Sales</div>
                <div class="mt-2 text-3xl font-extrabold text-white">
                    Rp {{ $commissionEstimate >= 1000000 ? number_format($commissionEstimate / 1000000, 1, ',', '.') . ' jt' : number_format($commissionEstimate, 0, ',', '.') }}
                </div>
                <div class="mt-1 flex items-center gap-1.5 text-xs text-emerald-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    Periode {{ now()->translatedFormat('F') }}
                </div>
            </div>

        </div>

        <!-- ================= TAB 1: QUOTATION / PO ================= -->
        <div x-data="{ deleteAction: '', deleteNumber: '' }" x-show="tab === 'orders'" x-transition class="space-y-4">
            <div class="overflow-hidden rounded-2xl border border-blue-900 bg-slate-900 shadow-xl">
                <x-list-toolbar :create-route="route('sales.create', ['mode' => 'online'])" create-label="Buat Quotation / PO" placeholder="Cari nomor dokumen..." />
                
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-blue-950/70 text-slate-300">
                            <tr>
                                <th class="px-4 py-3.5 text-left">No.</th>
                                <th class="px-4 py-3.5 text-left">No. Dokumen</th>
                                <th class="px-4 py-3.5 text-left">Customer</th>
                                <th class="px-4 py-3.5 text-right">Nilai Total</th>
                                <th class="px-4 py-3.5 text-center">Terkirim</th>
                                <th class="px-4 py-3.5 text-center">Status</th>
                                <th class="min-w-[11rem] px-4 py-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-950/60 text-slate-200">
                            @forelse($sales as $sale)
                                <tr class="hover:bg-blue-950/30 transition">
                                    <td class="px-4 py-3.5 text-slate-400">{{ $sales->firstItem() + $loop->index }}</td>
                                    <td class="px-4 py-3.5">
                                        <b class="text-white">{{ $sale->number }}</b>
                                        <small class="block text-xs text-blue-300">{{ $sale->date->format('d M Y') }} · {{ $sale->document_type === 'quotation' ? 'Quotation' : 'PO' }}</small>
                                    </td>
                                    <td class="px-4 py-3.5 font-medium text-white">{{ $sale->customer?->name ?? 'Pelanggan Umum' }}</td>
                                    <td class="px-4 py-3.5 text-right font-bold text-white">Rp {{ number_format((float) $sale->total, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-flex rounded-md bg-slate-950 px-2.5 py-1 text-xs font-bold {{ $sale->deliveryPercentage() == 100 ? 'text-emerald-400' : ($sale->deliveryPercentage() > 0 ? 'text-amber-400' : 'text-slate-400') }}">
                                            {{ $sale->document_type === 'pos' ? '100%' : $sale->deliveryPercentage() . '%' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize {{ $sale->status === 'completed' ? 'bg-emerald-900/60 text-emerald-300 border border-emerald-800/40' : ($sale->status === 'partial' ? 'bg-blue-900/60 text-blue-300 border border-blue-800/40' : 'bg-slate-800 text-slate-300 border border-slate-700/50') }}">
                                            {{ ucfirst($sale->status) }}
                                        </span>
                                        <small class="mt-1 block text-[10px] font-bold {{ (float) $sale->paid_amount >= (float) $sale->total ? 'text-emerald-400' : 'text-amber-400' }}">{{ (float) $sale->paid_amount >= (float) $sale->total ? 'Lunas' : 'Belum Lunas' }}</small>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('sales.show', $sale) }}" class="rounded-lg bg-indigo-600/80 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-600 transition">
                                                Detail
                                            </a>
                                            <a href="{{ route('sales.invoice.print', $sale) }}" target="_blank" class="rounded-lg border border-slate-700 bg-slate-800 px-3 py-1.5 text-xs font-semibold text-slate-300 hover:bg-slate-700 transition">
                                                Cetak
                                            </a>
                                            @can('delete', $sale)
                                                <button type="button" data-action="{{ route('sales.destroy', $sale) }}" data-number="{{ $sale->number }}" x-on:click="deleteAction = $event.currentTarget.dataset.action; deleteNumber = $event.currentTarget.dataset.number; $dispatch('open-modal', 'delete-quotation')" class="rounded-lg bg-red-600/80 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-600">Hapus</button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400">Belum ada data Quotation / Sales Order.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <x-modal name="delete-quotation" maxWidth="md" focusable>
                <form method="POST" x-bind:action="deleteAction" class="bg-slate-900 p-6 text-white">
                    @csrf
                    @method('DELETE')
                    <h3 class="text-lg font-bold text-red-300">Hapus Quotation</h3>
                    <p class="mt-2 text-sm text-slate-400">Masukkan key Super Admin untuk menghapus <b class="text-white" x-text="deleteNumber"></b>.</p>
                    <input type="password" name="quotation_key" required maxlength="100" autocomplete="off" class="mt-5 w-full rounded-xl border-red-900 bg-slate-950 text-white" placeholder="Key penghapusan quotation">
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" x-on:click="$dispatch('close-modal', 'delete-quotation')" class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-300 transition hover:bg-slate-800">Batal</button>
                        <button class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-500">Hapus Quotation</button>
                    </div>
                </form>
            </x-modal>
            <div>{{ $sales->links() }}</div>
        </div>

        <div x-data="{ refundAction: '', refundNumber: '' }" x-show="tab === 'offline'" x-transition class="space-y-4">
            <div class="overflow-hidden rounded-2xl border border-blue-900 bg-slate-900 shadow-xl">
                <div class="border-b border-blue-950 bg-slate-950/60 p-4">
                    <h4 class="text-sm font-bold text-white">Daftar Penjualan Offline</h4>
                    <p class="text-xs text-slate-400">Transaksi langsung dari POS Kasir</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-blue-950/70 text-slate-300">
                            <tr><th class="px-4 py-3.5 text-left">No.</th><th class="px-4 py-3.5 text-left">No. Transaksi</th><th class="px-4 py-3.5 text-left">Customer</th><th class="px-4 py-3.5 text-left">Kasir</th><th class="px-4 py-3.5 text-right">Nilai Total</th><th class="px-4 py-3.5 text-center">Status</th><th class="px-4 py-3.5 text-center">Aksi</th></tr>
                        </thead>
                        <tbody class="divide-y divide-blue-950/60 text-slate-200">
                            @forelse($offlineSales as $sale)
                                <tr class="transition hover:bg-blue-950/30">
                                    <td class="px-4 py-3.5 text-slate-400">{{ $offlineSales->firstItem() + $loop->index }}</td>
                                    <td class="px-4 py-3.5"><b class="text-white">{{ $sale->number }}</b><small class="block text-xs text-blue-300">{{ $sale->date->format('d M Y') }}</small></td>
                                    <td class="px-4 py-3.5 font-medium text-white">{{ $sale->customer?->name ?? 'Pelanggan Umum' }}</td>
                                    <td class="px-4 py-3.5 text-slate-300">{{ $sale->salesUser?->name ?? '-' }}</td>
                                    <td class="px-4 py-3.5 text-right font-bold text-white">Rp {{ number_format((float) $sale->total, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3.5 text-center"><span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold capitalize {{ $sale->status === 'completed' ? 'border-emerald-800/40 bg-emerald-900/60 text-emerald-300' : 'border-slate-700/50 bg-slate-800 text-slate-300' }}">{{ ucfirst($sale->status) }}</span></td>
                                    <td class="px-4 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('sales.show', $sale) }}" class="rounded-lg bg-indigo-600/80 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-indigo-600">Detail</a>
                                            <a href="{{ route('sales.invoice.print', $sale) }}" target="_blank" class="rounded-lg border border-slate-700 bg-slate-800 px-3 py-1.5 text-xs font-semibold text-slate-300 transition hover:bg-slate-700">Cetak</a>
                                            @if($sale->status === 'completed' && in_array(auth()->user()->role, ['superadmin', 'admin', 'cashier', 'finance'], true))
                                                <button type="button" data-action="{{ route('sales.refund', $sale) }}" data-number="{{ $sale->number }}" x-on:click="refundAction = $event.currentTarget.dataset.action; refundNumber = $event.currentTarget.dataset.number; $dispatch('open-modal', 'refund-offline-sale')" class="rounded-lg bg-red-600/80 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-600">Refund</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="py-12 text-center text-slate-400">Belum ada transaksi penjualan offline.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <x-modal name="refund-offline-sale" maxWidth="md" focusable>
                <form method="POST" x-bind:action="refundAction" class="bg-slate-900 p-6 text-white">
                    @csrf
                    <h3 class="text-lg font-bold text-red-300">Refund Penjualan Offline</h3>
                    <p class="mt-2 text-sm text-slate-400">Refund <b class="text-white" x-text="refundNumber"></b> memerlukan key Super Admin dan akan mengembalikan stok FIFO serta membalik mutasi pembayaran Kas/Bank.</p>
                    <input type="text" name="reason" required maxlength="255" class="mt-5 w-full rounded-xl border-red-900 bg-slate-950 text-white" placeholder="Alasan refund">
                    <input type="password" name="quotation_key" required maxlength="100" autocomplete="off" class="mt-3 w-full rounded-xl border-red-900 bg-slate-950 text-white" placeholder="Key otorisasi Super Admin">
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" x-on:click="$dispatch('close-modal', 'refund-offline-sale')" class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-300 transition hover:bg-slate-800">Batal</button>
                        <button class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-500">Refund Transaksi</button>
                    </div>
                </form>
            </x-modal>
            <div>{{ $offlineSales->appends(['tab' => 'offline'])->links() }}</div>
        </div>

        <!-- ================= TAB 2: DELIVERY ORDER ================= -->
        <div x-show="tab === 'delivery'" x-transition class="space-y-4">
            <div class="overflow-hidden rounded-2xl border border-blue-900 bg-slate-900 shadow-xl">
                <div class="flex items-center justify-between border-b border-blue-950 bg-slate-950/60 p-4">
                    <div>
                        <h4 class="text-sm font-bold text-white">PO Menunggu Pengiriman</h4>
                        <p class="text-xs text-slate-400">PO yang baru dibuat otomatis masuk ke daftar ini untuk dibuatkan Delivery Order nanti.</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-blue-950/70 text-slate-300"><tr><th class="px-4 py-3.5 text-left">No.</th><th class="px-4 py-3.5 text-left">Nomor PO</th><th class="px-4 py-3.5 text-left">Customer</th><th class="px-4 py-3.5 text-left">Tanggal Pesan</th><th class="px-4 py-3.5 text-center">Pengiriman</th><th class="px-4 py-3.5 text-center">Pembayaran</th><th class="px-4 py-3.5 text-center">Aksi</th></tr></thead>
                        <tbody class="divide-y divide-blue-950/60 text-slate-200">
                            @forelse($pendingDeliverySales as $pendingSale)
                                <tr class="transition hover:bg-blue-950/30">
                                    <td class="px-4 py-3.5 text-slate-400">{{ $pendingDeliverySales->firstItem() + $loop->index }}</td>
                                    <td class="px-4 py-3.5 font-bold text-blue-300"><a href="{{ route('sales.show', $pendingSale) }}" class="hover:underline">{{ $pendingSale->number }}</a></td>
                                    <td class="px-4 py-3.5 font-medium text-white">{{ $pendingSale->customer?->name ?? 'Pelanggan Umum' }}</td>
                                    <td class="px-4 py-3.5 text-slate-300">{{ $pendingSale->date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3.5 text-center"><span class="rounded-full border border-blue-800/50 bg-blue-950 px-2.5 py-1 text-xs font-semibold text-blue-300">{{ $pendingSale->deliveryPercentage() }}% terkirim</span></td>
                                    <td class="px-4 py-3.5 text-center"><span class="text-xs font-bold {{ (float) $pendingSale->paid_amount >= (float) $pendingSale->total ? 'text-emerald-400' : 'text-amber-400' }}">{{ (float) $pendingSale->paid_amount >= (float) $pendingSale->total ? 'Lunas' : 'Belum Lunas' }}</span></td>
                                    <td class="px-4 py-3.5 text-center">
                                        @if(in_array(auth()->user()->role, ['superadmin', 'admin', 'warehouse'], true))
                                            <a href="{{ route('sales.show', $pendingSale) }}" class="rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-indigo-500">Buat Delivery Order</a>
                                        @else
                                            <a href="{{ route('sales.show', $pendingSale) }}" class="rounded-lg border border-slate-700 bg-slate-800 px-3 py-1.5 text-xs font-semibold text-slate-300">Detail</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="py-10 text-center text-slate-400">Tidak ada PO yang menunggu pengiriman.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div>{{ $pendingDeliverySales->appends(['tab' => 'delivery'])->links() }}</div>

            <div class="overflow-hidden rounded-2xl border border-blue-900 bg-slate-900 shadow-xl">
                <div class="border-b border-blue-950 p-4 bg-slate-950/60 flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-white">Daftar Surat Jalan (Delivery Order)</h4>
                        <p class="text-xs text-slate-400">Pengiriman barang parsial dan lengkap ke customer</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-blue-950/70 text-slate-300">
                            <tr>
                                <th class="px-4 py-3.5 text-left">No.</th>
                                <th class="px-4 py-3.5 text-left">No. DO</th>
                                <th class="px-4 py-3.5 text-left">Ref PO</th>
                                <th class="px-4 py-3.5 text-left">Customer</th>
                                <th class="px-4 py-3.5 text-left">Tanggal Kirim</th>
                                <th class="px-4 py-3.5 text-center">Jumlah Item</th>
                                <th class="px-4 py-3.5 text-center">Status</th>
                                <th class="px-4 py-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-950/60 text-slate-200">
                            @forelse($deliveryOrders as $do)
                                <tr class="hover:bg-blue-950/30 transition">
                                    <td class="px-4 py-3.5 text-slate-400">{{ $deliveryOrders->firstItem() + $loop->index }}</td>
                                    <td class="px-4 py-3.5 font-bold text-white">{{ $do->number }}</td>
                                    <td class="px-4 py-3.5 font-semibold text-blue-300">
                                        <a href="{{ route('sales.show', $do->sale) }}" class="hover:underline">{{ $do->sale?->number }}</a>
                                    </td>
                                    <td class="px-4 py-3.5 font-medium text-white">{{ $do->sale?->customer?->name ?? '-' }}</td>
                                    <td class="px-4 py-3.5 text-slate-300">{{ $do->date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3.5 text-center text-xs text-slate-300">{{ $do->items->count() }} item</td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-flex rounded-full bg-emerald-900/60 border border-emerald-800/40 px-2.5 py-0.5 text-xs font-semibold text-emerald-300 capitalize">
                                            {{ $do->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <a href="{{ route('delivery_orders.print', $do) }}" target="_blank" class="rounded-lg bg-indigo-600/80 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-600 transition">
                                            Print DO
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-slate-400">Belum ada pengiriman Delivery Order.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div>{{ $deliveryOrders->links() }}</div>
        </div>

        <!-- ================= TAB 3: INVOICE ================= -->
        <div x-data="{ paymentSaleId: null, paymentNumber: '', paymentTotal: 0, paymentPaid: 0, paymentRemaining: 0, paymentAction: '', paymentIsCash: false }" x-show="tab === 'invoice'" x-transition class="space-y-4">
            <div class="overflow-hidden rounded-2xl border border-blue-900 bg-slate-900 shadow-xl">
                <div class="border-b border-blue-950 p-4 bg-slate-950/60 flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-white">Daftar Tagihan &amp; Invoice</h4>
                        <p class="text-xs text-slate-400">Faktur penagihan penjualan kepada customer (Quotation &amp; PO dapat diselesaikan langsung di sini)</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-blue-950/70 text-slate-300">
                            <tr>
                                <th class="px-4 py-3.5 text-left">No.</th>
                                <th class="px-4 py-3.5 text-left">No. Invoice</th>
                                <th class="px-4 py-3.5 text-left">Customer</th>
                                <th class="px-4 py-3.5 text-left">Tanggal</th>
                                <th class="px-4 py-3.5 text-right">Total Tagihan</th>
                                <th class="px-4 py-3.5 text-right">Sudah Dibayar</th>
                                <th class="px-4 py-3.5 text-center">Status Bayar</th>
                                <th class="px-4 py-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-950/60 text-slate-200">
                            @forelse($invoices as $inv)
                                <tr class="hover:bg-blue-950/30 transition">
                                    <td class="px-4 py-3.5 text-slate-400">{{ $invoices->firstItem() + $loop->index }}</td>
                                    <td class="px-4 py-3.5 font-bold text-white">
                                        <a href="{{ route('sales.show', $inv) }}" class="hover:underline text-blue-300">{{ $inv->number }}</a>
                                    </td>
                                    <td class="px-4 py-3.5 font-medium text-white">{{ $inv->customer?->name ?? 'Pelanggan Umum' }}</td>
                                    <td class="px-4 py-3.5 text-slate-300">{{ $inv->date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3.5 text-right font-bold text-white">Rp {{ number_format((float) $inv->total, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3.5 text-right font-semibold text-emerald-400">Rp {{ number_format((float) $inv->paid_amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold capitalize {{ (float)$inv->paid_amount >= (float)$inv->total ? 'bg-emerald-900/60 text-emerald-300 border border-emerald-800/40' : ((float)$inv->paid_amount > 0 ? 'bg-amber-900/60 text-amber-300 border border-amber-800/40' : 'bg-rose-900/60 text-rose-300 border border-rose-800/40') }}">
                                            {{ (float)$inv->paid_amount >= (float)$inv->total ? 'Lunas' : ((float)$inv->paid_amount > 0 ? 'Sebagian' : 'Belum Bayar') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            @if((float)$inv->paid_amount < (float)$inv->total && !in_array($inv->status, ['cancelled', 'refunded'], true))
                                                <button 
                                                    type="button" 
                                                    @click="paymentSaleId = {{ $inv->id }}; paymentNumber = '{{ $inv->number }}'; paymentTotal = {{ (float)$inv->total }}; paymentPaid = {{ (float)$inv->paid_amount }}; paymentRemaining = {{ max(0, (float)$inv->total - (float)$inv->paid_amount) }}; paymentAction = '{{ route('sales.payment', $inv) }}'; paymentIsCash = false; $dispatch('open-modal', 'pay-invoice-modal')" 
                                                    class="inline-flex items-center gap-1 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-emerald-500 transition shadow"
                                                >
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    Selesaikan
                                                </button>
                                            @endif
                                            <a href="{{ route('sales.show', $inv) }}" class="rounded-lg border border-slate-700 bg-slate-800 px-3 py-1.5 text-xs font-semibold text-slate-300 hover:bg-slate-700 transition">
                                                Detail
                                            </a>
                                            <a href="{{ route('sales.invoice.print', $inv) }}" target="_blank" class="rounded-lg bg-indigo-600/80 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-600 transition">
                                                Cetak
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-slate-400">Belum ada invoice penjualan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MODAL SELESAIKAN INVOICE -->
            <x-modal name="pay-invoice-modal" maxWidth="md" focusable>
                <form method="POST" :action="paymentAction" class="bg-slate-900 p-6 text-white space-y-4">
                    @csrf
                    <input type="hidden" name="redirect_to_invoices" value="1">
                    
                    <div class="border-b border-blue-950 pb-3">
                        <h3 class="text-lg font-bold text-white">Selesaikan Invoice Penjualan</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Catat pembayaran dan selesaikan transaksi <b class="text-blue-300" x-text="paymentNumber"></b></p>
                    </div>

                    <div class="rounded-xl border border-blue-900/60 bg-slate-950/60 p-3 text-xs space-y-1.5">
                        <div class="flex justify-between text-slate-400">
                            <span>Total Tagihan:</span>
                            <span class="font-semibold text-white" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(paymentTotal)"></span>
                        </div>
                        <div class="flex justify-between text-slate-400">
                            <span>Sudah Dibayar:</span>
                            <span class="font-semibold text-emerald-400" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(paymentPaid)"></span>
                        </div>
                        <div class="flex justify-between border-t border-blue-950/80 pt-1.5 text-sm font-bold">
                            <span class="text-slate-300">Sisa Tagihan:</span>
                            <span class="text-emerald-400" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(paymentRemaining)"></span>
                        </div>
                    </div>

                    <label class="block text-xs font-semibold text-slate-300">
                        <span>Metode Pembayaran</span>
                        <select name="bank_account_id" x-on:change="paymentIsCash = $event.target.selectedOptions[0].dataset.cash === '1'" class="mt-1.5 w-full rounded-xl border border-blue-900 bg-slate-950 px-3 py-2 text-xs text-white focus:border-indigo-500">
                            @foreach($bankAccounts as $account)
                                <option value="{{ $account->id }}" data-cash="{{ $account->name === 'Tunai' ? '1' : '0' }}">{{ $account->name === 'Tunai' ? 'Tunai' : str_replace('Bank ', '', $account->name) }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label x-cloak x-show="paymentIsCash" class="block text-xs font-semibold text-slate-300">
                        <span>Nilai Tunai (Rp)</span>
                        <input type="number" step="any" min="1" name="paid_amount" :value="paymentRemaining" x-bind:disabled="!paymentIsCash" x-bind:required="paymentIsCash" class="mt-1.5 w-full rounded-xl border border-blue-900 bg-slate-950 px-3 py-2 text-xs text-white font-semibold focus:border-indigo-500">
                    </label>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-blue-950">
                        <button type="button" x-on:click="$dispatch('close-modal', 'pay-invoice-modal')" class="rounded-xl border border-slate-700 bg-slate-800 px-4 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-700 transition">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-5 py-2 text-xs font-bold text-white shadow-lg hover:bg-emerald-500 transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Selesaikan Invoice
                        </button>
                    </div>
                </form>
            </x-modal>

            <div>{{ $invoices->links() }}</div>
        </div>

        <!-- ================= TAB 4: RETUR ================= -->
        <div x-show="tab === 'retur'" x-transition class="space-y-4">
            <div class="overflow-hidden rounded-2xl border border-blue-900 bg-slate-900 shadow-xl">
                <div class="border-b border-blue-950 p-4 bg-slate-950/60 flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-white">Daftar Retur Penjualan</h4>
                        <p class="text-xs text-slate-400">Pengembalian barang dari customer yang memotong piutang &amp; menambah stok kembali</p>
                    </div>
                    @if(in_array(auth()->user()->role, ['superadmin', 'admin', 'finance', 'warehouse']))
                        <a href="{{ route('product-returns.create') }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-500 transition">
                            + Tambah Retur
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-blue-950/70 text-slate-300">
                            <tr>
                                <th class="px-4 py-3.5 text-left">No.</th>
                                <th class="px-4 py-3.5 text-left">No. Retur</th>
                                <th class="px-4 py-3.5 text-left">Tanggal</th>
                                <th class="px-4 py-3.5 text-center">Jumlah Item</th>
                                <th class="px-4 py-3.5 text-right">Nilai Retur</th>
                                <th class="px-4 py-3.5 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-950/60 text-slate-200">
                            @forelse($salesReturns as $ret)
                                <tr class="hover:bg-blue-950/30 transition">
                                    <td class="px-4 py-3.5 text-slate-400">{{ $salesReturns->firstItem() + $loop->index }}</td>
                                    <td class="px-4 py-3.5 font-bold text-white">{{ $ret->number }}</td>
                                    <td class="px-4 py-3.5 text-slate-300">{{ $ret->date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3.5 text-center text-xs text-slate-300">{{ $ret->items->count() }} produk</td>
                                    <td class="px-4 py-3.5 text-right font-bold text-rose-400">- Rp {{ number_format((float) $ret->total, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-flex rounded-full bg-emerald-900/60 border border-emerald-800/40 px-2.5 py-0.5 text-xs font-semibold text-emerald-300 capitalize">
                                            {{ $ret->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400">Belum ada transaksi retur penjualan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div>{{ $salesReturns->links() }}</div>
        </div>

        <!-- ================= TAB 5: PIUTANG ================= -->
        <div x-show="tab === 'piutang'" x-transition class="space-y-4">
            <div class="overflow-hidden rounded-2xl border border-blue-900 bg-slate-900 shadow-xl">
                <div class="border-b border-blue-950 p-4 bg-slate-950/60 flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-white">Buku Piutang Dagang (Customer Receivables)</h4>
                        <p class="text-xs text-slate-400">Daftar seluruh tagihan customer yang belum lunas</p>
                    </div>
                    <form method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="tab" value="piutang">
                        <label for="receivable_type" class="text-xs font-semibold text-slate-300">Sumber</label>
                        <select id="receivable_type" name="receivable_type" onchange="this.form.submit()" class="rounded-xl border border-blue-900 bg-slate-950 px-3 py-2 text-xs text-white">
                            <option value="all" @selected($receivableType === 'all')>Semua Transaksi</option>
                            <option value="online" @selected($receivableType === 'online')>Online</option>
                            <option value="offline" @selected($receivableType === 'offline')>Offline / Open Bill</option>
                        </select>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-blue-950/70 text-slate-300">
                            <tr>
                                <th class="px-4 py-3.5 text-left">No.</th>
                                <th class="px-4 py-3.5 text-left">No. Dokumen</th>
                                <th class="px-4 py-3.5 text-left">Jenis Transaksi</th>
                                <th class="px-4 py-3.5 text-left">Customer</th>
                                <th class="px-4 py-3.5 text-right">Total Invoice</th>
                                <th class="px-4 py-3.5 text-right">Sudah Dibayar</th>
                                <th class="px-4 py-3.5 text-right">Sisa Piutang</th>
                                <th class="px-4 py-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-950/60 text-slate-200">
                            @forelse($receivables as $rec)
                                <tr class="hover:bg-blue-950/30 transition">
                                    <td class="px-4 py-3.5 text-slate-400">{{ $receivables->firstItem() + $loop->index }}</td>
                                    <td class="px-4 py-3.5 font-bold text-white">
                                        <a href="{{ route('sales.show', $rec) }}" class="text-blue-300 hover:underline">{{ $rec->number }}</a>
                                        @if($rec->document_type === 'pos')
                                            <small class="mt-1 block text-xs font-medium text-amber-400">Bill: {{ $rec->bill_name ?: 'Tanpa nama bill' }}</small>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5"><span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold {{ $rec->document_type === 'pos' ? 'border-amber-700/50 bg-amber-950/50 text-amber-300' : 'border-blue-700/50 bg-blue-950/60 text-blue-300' }}">{{ $rec->document_type === 'pos' ? 'Offline / Open Bill' : 'Online' }}</span></td>
                                    <td class="px-4 py-3.5 font-medium text-white">{{ $rec->customer?->name ?? 'Pelanggan Umum' }}</td>
                                    <td class="px-4 py-3.5 text-right text-slate-300">Rp {{ number_format((float) $rec->total, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3.5 text-right text-emerald-400">Rp {{ number_format((float) $rec->paid_amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3.5 text-right font-extrabold text-rose-400">Rp {{ number_format($rec->outstandingBalance(), 0, ',', '.') }}</td>
                                    <td class="min-w-[11rem] px-4 py-3.5 text-center">
                                        <a href="{{ route('sales.show', $rec) }}" class="inline-flex min-w-[9.5rem] items-center justify-center gap-2 whitespace-nowrap rounded-xl border border-emerald-400/30 bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-lg shadow-emerald-950/20 transition hover:-translate-y-0.5 hover:bg-emerald-500 hover:shadow-emerald-900/30">
                                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2m4-3h-8m0 0l3-3m-3 3l3 3"/></svg>
                                            Bayar Tagihan
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-slate-400">Semua piutang telah lunas! Tidak ada tagihan tertunggak.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div>{{ $receivables->links() }}</div>
        </div>

    </div>
</x-app-layout>
