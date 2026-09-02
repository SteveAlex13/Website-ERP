<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-blue-400">Manajemen Stok & Gudang</p>
                <h2 class="text-xl font-semibold text-white">Stock In &amp; Pembelian</h2>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @if(in_array(auth()->user()->role, ['superadmin', 'admin', 'purchasing', 'warehouse']))
                    <a href="{{ route('products.create') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-indigo-500/50 bg-slate-900 px-4 py-2.5 text-xs font-bold text-indigo-300 shadow transition hover:bg-slate-800 hover:text-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Produk Baru
                    </a>
                    <a href="{{ route('purchases.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow-lg transition hover:bg-emerald-500">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Buat Stock In (Pembelian)
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-8 p-4 sm:p-8">
        
        <!-- SECTION 1: DAFTAR DOKUMEN PEMBELIAN (PURCHASE ORDERS) -->
        <div>
            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-bold text-white">Daftar Pembelian (Stock In)</h3>
                    <p class="text-xs text-blue-300">Dokumen PO dan transaksi pembelian dari supplier</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-blue-900 bg-slate-900 shadow-xl">
                <x-list-toolbar :create-route="route('purchases.create')" create-label="Buat Stock In" placeholder="Cari nomor PO..." />

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-blue-950/70 text-slate-300">
                            <tr>
                                <th class="px-4 py-3 text-left">No</th>
                                <th class="px-4 py-3 text-left">Nomor PO</th>
                                <th class="px-4 py-3 text-left">Supplier</th>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-center">Item</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-right">Total</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-950/60 text-slate-200">
                            @forelse($purchases as $purchase)
                                <tr class="hover:bg-blue-950/30 transition">
                                    <td class="px-4 py-3 text-slate-400">{{ $purchases->firstItem() + $loop->index }}</td>
                                    <td class="px-4 py-3 font-semibold text-blue-300">
                                        <a class="hover:underline" href="{{ route('purchases.show', $purchase) }}">{{ $purchase->number }}</a>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-white">{{ $purchase->supplier?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-300">{{ $purchase->date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-center text-xs text-slate-400">{{ $purchase->items_count }} jenis</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold capitalize {{ $purchase->status === 'completed' ? 'bg-emerald-900/60 text-emerald-300 border border-emerald-800/40' : ($purchase->status === 'partial' ? 'bg-amber-900/60 text-amber-300 border border-amber-800/40' : 'bg-blue-950 text-blue-300 border border-blue-800/40') }}">
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-white">Rp {{ number_format((float) $purchase->total, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('purchases.show', $purchase) }}" class="rounded-lg bg-indigo-600/80 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-600">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-10 text-center text-slate-400">Belum ada data pembelian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $purchases->links() }}
            </div>
        </div>

        <!-- SECTION 2: RIWAYAT BARANG MASUK / MUTASI STOK (CRUD HISTORY) -->
        <div class="pt-4 border-t border-blue-950">
            <div class="mb-4 flex flex-col gap-1">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-600 text-white text-xs font-bold">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <h3 class="text-xl font-bold text-white">Riwayat Barang Masuk (Stock In History)</h3>
                </div>
                <p class="text-xs text-blue-300">Catatan kronologis penerimaan produk ke gudang beserta batch FIFO dan sisa stok batch</p>
            </div>

            <div class="overflow-hidden rounded-2xl border border-blue-900 bg-slate-900 shadow-xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-blue-950/70 text-slate-300">
                            <tr>
                                <th class="px-4 py-3 text-left">No</th>
                                <th class="px-4 py-3 text-left">Tanggal Masuk</th>
                                <th class="px-4 py-3 text-left">Produk</th>
                                <th class="px-4 py-3 text-left">Supplier</th>
                                <th class="px-4 py-3 text-right">Qty Masuk</th>
                                <th class="px-4 py-3 text-right">Sisa Batch</th>
                                <th class="px-4 py-3 text-right">Harga Beli Satuan</th>
                                <th class="px-4 py-3 text-right">Total Biaya</th>
                                <th class="px-4 py-3 text-center">Ref PO</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-950/60 text-slate-200">
                            @forelse($stockInHistory as $batch)
                                <tr class="hover:bg-blue-950/30 transition">
                                    <td class="px-4 py-3 text-slate-400">{{ $stockInHistory->firstItem() + $loop->index }}</td>
                                    <td class="px-4 py-3 text-slate-300">
                                        {{ $batch->received_at ? $batch->received_at->format('d/m/Y') : $batch->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <b class="block text-white">{{ $batch->product?->name ?? '-' }}</b>
                                        <small class="text-xs text-indigo-400">{{ $batch->product?->sku }}</small>
                                    </td>
                                    <td class="px-4 py-3 font-medium text-slate-300">{{ $batch->supplier?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-emerald-400">
                                        +{{ number_format((float) $batch->initial_quantity, 0, ',', '.') }} {{ $batch->product?->unit ?? 'pcs' }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-slate-200">
                                        {{ number_format((float) $batch->remaining_quantity, 0, ',', '.') }} {{ $batch->product?->unit ?? 'pcs' }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-slate-300">
                                        Rp {{ number_format((float) $batch->unit_cost, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-white">
                                        Rp {{ number_format((float) ($batch->initial_quantity * $batch->unit_cost), 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($batch->purchaseItem?->purchase)
                                            <a href="{{ route('purchases.show', $batch->purchaseItem->purchase) }}" class="inline-flex rounded bg-blue-950 px-2 py-0.5 text-xs font-semibold text-blue-300 hover:underline border border-blue-800/40">
                                                {{ $batch->purchaseItem->purchase->number }}
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-500">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-10 text-center text-slate-400">Belum ada riwayat penerimaan barang masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $stockInHistory->links() }}
            </div>
        </div>

    </div>
</x-app-layout>

