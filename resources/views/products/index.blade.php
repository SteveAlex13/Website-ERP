<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold">Stok Barang</h2></x-slot>

    <div class="mx-auto max-w-7xl p-4 sm:p-8">
        <div class="mb-4">
            <p class="text-sm text-blue-300">Harga jual, harga beli, dan lapisan persediaan FIFO</p>
            <h3 class="text-2xl font-bold">Daftar Produk</h3>
        </div>

        <div class="overflow-hidden rounded-2xl border border-blue-900 bg-slate-900 shadow-xl">
            <x-list-toolbar placeholder="Cari SKU atau nama..." :per-page-options="[5, 10, 25]" :default-per-page="5" />

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-blue-950/70 text-slate-300">
                        <tr>
                            <th class="px-4 py-3 text-left">No</th>
                            <th class="px-4 py-3 text-left">SKU</th>
                            <th class="px-4 py-3 text-left">Nama Produk</th>
                            <th class="px-4 py-3 text-left">Satuan</th>
                            <th class="px-4 py-3 text-right">Stok</th>
                            <th class="px-4 py-3 text-right">Harga Beli</th>
                            <th class="px-4 py-3 text-right">Harga Jual</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    @forelse($products as $product)
                        <tbody x-data="{ batchesOpen: false }" class="border-t border-blue-950/60 text-slate-200">
                            <tr class="transition hover:bg-blue-950/30">
                                <td class="px-4 py-3 text-slate-400">{{ $products->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-3 font-semibold text-blue-300">{{ $product->sku }}</td>
                                <td class="px-4 py-3 font-medium text-white">{{ $product->name }}</td>
                                <td class="px-4 py-3 text-slate-300">{{ $product->unit }}</td>
                                <td class="px-4 py-3 text-right font-bold text-white">{{ number_format((float) $product->stock, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-amber-300">Rp {{ number_format((float) $product->purchase_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-emerald-300">Rp {{ number_format((float) $product->selling_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" x-on:click="batchesOpen = ! batchesOpen" class="rounded-lg border border-blue-800 px-3 py-1.5 text-xs font-semibold text-blue-200 transition hover:bg-blue-950">
                                            {{ $product->inventoryBatches->count() }} Batch FIFO
                                        </button>
                                        @if(auth()->user()->role !== 'cashier')
                                            <a href="{{ route('products.edit', $product) }}" class="rounded-lg bg-indigo-600/80 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-indigo-600">Edit</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <tr x-cloak x-show="batchesOpen" x-transition>
                                <td colspan="8" class="bg-slate-950/70 p-4">
                                    <div class="overflow-x-auto rounded-xl border border-blue-950">
                                        <table class="min-w-full text-xs">
                                            <thead class="bg-blue-950/50 text-slate-400"><tr><th class="px-3 py-2 text-left">Urutan FIFO</th><th class="px-3 py-2 text-left">Tanggal Masuk</th><th class="px-3 py-2 text-left">Supplier</th><th class="px-3 py-2 text-right">Sisa Batch</th><th class="px-3 py-2 text-right">Harga Beli</th><th class="px-3 py-2 text-right">Nilai Persediaan</th></tr></thead>
                                            <tbody class="divide-y divide-blue-950/60">
                                                @forelse($product->inventoryBatches as $batch)
                                                    <tr><td class="px-3 py-2">#{{ $loop->iteration }}</td><td class="px-3 py-2">{{ $batch->received_at->format('d/m/Y') }}</td><td class="px-3 py-2">{{ $batch->supplier?->name ?? 'Saldo awal' }}</td><td class="px-3 py-2 text-right">{{ number_format((float) $batch->remaining_quantity, 0, ',', '.') }} {{ $product->unit }}</td><td class="px-3 py-2 text-right">Rp {{ number_format((float) $batch->unit_cost, 0, ',', '.') }}</td><td class="px-3 py-2 text-right font-semibold text-white">Rp {{ number_format((float) $batch->remaining_quantity * (float) $batch->unit_cost, 0, ',', '.') }}</td></tr>
                                                @empty
                                                    <tr><td colspan="6" class="px-3 py-6 text-center text-slate-400">Belum ada batch penerimaan.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    @empty
                        <tbody><tr><td colspan="8" class="py-10 text-center text-slate-400">Belum ada produk.</td></tr></tbody>
                    @endforelse
                </table>
            </div>
        </div>

        <div class="mt-4">{{ $products->links() }}</div>
    </div>
</x-app-layout>
