<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-blue-400">ERP Perusahaan Dagang</p>
                <h2 class="text-2xl font-bold text-white">Daftar Pembelian</h2>
                <p class="mt-0.5 text-xs text-blue-300">Quotation, PO, pengiriman, invoice, retur, dan piutang pembelian.</p>
            </div>
            <a href="{{ route('expense-purchases.create', ['document_type' => $activeDocumentType]) }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow-lg transition hover:bg-indigo-500">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                + Tambah Pembelian
            </a>
        </div>
    </x-slot>

    @php
        $tabs = [
            'quotation_po' => ['Quotation / PO', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            'delivery_order' => ['Delivery Order', 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1M5 17a2 2 0 104 0m6 0a2 2 0 104 0'],
            'invoice' => ['Invoice', 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z'],
            'retur' => ['Retur', 'M16 15v-1a4 4 0 00-4-4H4m0 0l3-3m-3 3l3 3m5 4v1a4 4 0 004 4h4'],
            'piutang' => ['Piutang', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 10v-1m9-4a9 9 0 11-18 0 9 9 0 0118 0z'],
        ];
    @endphp

    <div class="mx-auto max-w-7xl space-y-6 p-4 sm:p-8">
        <nav class="flex items-center gap-2 overflow-x-auto rounded-2xl border border-blue-900/60 bg-slate-900/90 p-1.5 text-xs font-bold shadow-xl" aria-label="Dokumen pembelian">
            @foreach($tabs as $key => [$label, $icon])
                <a href="{{ route('expense-purchases.index', array_filter(['document_type' => $key, 'purchase_type' => request('purchase_type')])) }}" class="flex shrink-0 items-center gap-2 rounded-xl px-4 py-2.5 transition {{ $activeDocumentType === $key ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white' }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-blue-900 bg-slate-900/90 p-5 shadow-xl"><div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Quotation / PO</div><div class="mt-2 text-3xl font-extrabold text-white">{{ $documentCounts->get('quotation_po', 0) }}</div><div class="mt-1 flex items-center gap-1.5 text-xs text-blue-300"><span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>Dokumen pembelian</div></div>
            <div class="rounded-2xl border border-blue-900 bg-slate-900/90 p-5 shadow-xl"><div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Delivery Order</div><div class="mt-2 text-3xl font-extrabold text-white">{{ $documentCounts->get('delivery_order', 0) }}</div><div class="mt-1 flex items-center gap-1.5 text-xs text-amber-400"><span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>Dokumen pengiriman</div></div>
            <div class="rounded-2xl border border-blue-900 bg-slate-900/90 p-5 shadow-xl"><div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Invoice</div><div class="mt-2 text-3xl font-extrabold text-white">{{ $documentCounts->get('invoice', 0) }}</div><div class="mt-1 flex items-center gap-1.5 text-xs text-emerald-400"><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>Tagihan supplier</div></div>
            <div class="rounded-2xl border border-blue-900 bg-slate-900/90 p-5 shadow-xl"><div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Piutang Pembelian</div><div class="mt-2 text-3xl font-extrabold text-white">Rp {{ $totalOutstanding >= 1000000 ? number_format($totalOutstanding / 1000000, 1, ',', '.').' jt' : number_format($totalOutstanding, 0, ',', '.') }}</div><div class="mt-1 flex items-center gap-1.5 text-xs text-rose-400"><span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span>{{ $unpaidPurchaseCount }} pembelian belum lunas</div></div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-blue-900 bg-slate-900 shadow-xl">
            <div class="flex flex-col justify-between gap-4 border-b border-blue-950 bg-slate-950/60 p-4 lg:flex-row lg:items-center">
                <div>
                    <h3 class="text-sm font-bold text-white">Daftar {{ $tabs[$activeDocumentType][0] }}</h3>
                    <p class="text-xs text-slate-400">Pilih kategori untuk melihat pembelian Barang, Kebutuhan, atau Asset.</p>
                </div>
                <form method="GET" class="flex flex-col gap-2 sm:flex-row">
                    <input type="hidden" name="document_type" value="{{ $activeDocumentType }}">
                    <select name="purchase_type" class="rounded-xl border-blue-900 bg-slate-950 text-sm text-white">
                        <option value="">Semua Pilihan</option>
                        <option value="barang" @selected(request('purchase_type') === 'barang')>Barang</option>
                        <option value="kebutuhan" @selected(request('purchase_type') === 'kebutuhan')>Kebutuhan</option>
                        <option value="asset" @selected(request('purchase_type') === 'asset')>Asset</option>
                    </select>
                    <input name="search" value="{{ request('search') }}" placeholder="Cari nomor dokumen..." class="rounded-xl border-blue-900 bg-slate-950 text-sm text-white placeholder-slate-500">
                    <button class="rounded-xl border border-blue-700 px-4 py-2 text-xs font-bold text-blue-200 transition hover:bg-blue-950">Cari</button>
                    <a href="{{ route('expense-purchases.create', ['document_type' => $activeDocumentType]) }}" class="rounded-xl bg-indigo-600 px-4 py-2 text-center text-xs font-bold text-white transition hover:bg-indigo-500">+ Tambah Pembelian</a>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-blue-950/70 text-slate-300"><tr><th class="px-4 py-3.5 text-left">No.</th><th class="px-4 py-3.5 text-left">No. Dokumen</th><th class="px-4 py-3.5 text-left">Pilihan</th><th class="px-4 py-3.5 text-left">Nama Pembelian</th><th class="px-4 py-3.5 text-left">Supplier</th><th class="px-4 py-3.5 text-right">Nilai Total</th><th class="px-4 py-3.5 text-right">Sisa Piutang</th><th class="px-4 py-3.5 text-center">Status</th><th class="px-4 py-3.5 text-center">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-blue-950/60 text-slate-200">
                        @forelse($expensePurchases as $item)
                            <tr class="transition hover:bg-blue-950/30">
                                <td class="px-4 py-3.5 text-slate-400">{{ $expensePurchases->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-3.5"><b class="text-white">{{ $item->number }}</b><small class="block text-xs text-blue-300">{{ $item->date->format('d M Y') }}</small></td>
                                <td class="px-4 py-3.5"><span class="rounded-lg bg-indigo-500/20 px-2.5 py-1 text-xs font-semibold text-indigo-300">{{ ucfirst($item->purchase_type) }}</span></td>
                                <td class="px-4 py-3.5 font-medium text-white">{{ $item->description }}</td>
                                <td class="px-4 py-3.5">{{ $item->supplier?->name ?? '-' }}</td>
                                <td class="px-4 py-3.5 text-right font-bold text-white">Rp {{ number_format((float) $item->total, 0, ',', '.') }}</td>
                                <td class="px-4 py-3.5 text-right font-semibold text-amber-300">Rp {{ number_format($item->outstandingBalance(), 0, ',', '.') }}</td>
                                <td class="px-4 py-3.5 text-center"><span class="rounded-full border px-2.5 py-1 text-xs font-semibold {{ $item->payment_status === 'paid' ? 'border-emerald-800/40 bg-emerald-900/60 text-emerald-300' : 'border-amber-800/40 bg-amber-900/60 text-amber-300' }}">{{ $item->payment_status === 'paid' ? 'Lunas' : ($item->payment_status === 'partial' ? 'Sebagian' : 'Belum Bayar') }}</span></td>
                                <td class="px-4 py-3.5 text-center"><a href="{{ route('expense-purchases.edit', $item) }}" class="rounded-lg bg-indigo-600/80 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-indigo-600">Detail</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="py-12 text-center text-slate-400">Belum ada data {{ $tabs[$activeDocumentType][0] }}.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div>{{ $expensePurchases->links() }}</div>
    </div>
</x-app-layout>
