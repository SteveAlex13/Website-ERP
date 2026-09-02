<x-app-layout>
    <x-slot name='header'><h2 class='text-xl font-semibold'>Stok Barang</h2></x-slot>
    <div class='mx-auto max-w-7xl p-4 sm:p-8'>
        <div class='flex flex-col justify-between gap-4 sm:flex-row sm:items-center'>
            <div><p class='text-sm text-blue-300'>Harga jual, harga beli, dan lapisan persediaan FIFO</p><h3 class='text-2xl font-bold'>Daftar Produk</h3></div>
        </div>
        @if(auth()->user()->role !== 'cashier')<x-list-toolbar create-route='{{ route('products.create') }}' create-label='Tambah Produk' placeholder='Cari SKU atau nama...'/>
        @else<x-list-toolbar placeholder='Cari SKU atau nama...'/>
        @endif
        <div class='mt-4 grid gap-4'>
            @forelse($products as $product)
                <article x-data='{ batchesOpen: false }' class='overflow-hidden rounded-2xl border border-blue-900 bg-slate-900'>
                    <div class='grid gap-4 p-5 lg:grid-cols-[minmax(220px,1fr)_repeat(3,minmax(130px,0.5fr))_auto] lg:items-center'>
                        <div><b class='text-base text-white'>{{ $product->name }}</b><p class='text-sm text-blue-300'>{{ $product->sku }} · {{ $product->unit }}</p></div>
                        <div><small class='block text-slate-400'>Stok tersedia</small><strong class='text-lg text-white'>{{ number_format((float)$product->stock,2,',','.') }} {{ $product->unit }}</strong></div>
                        <div><small class='block text-slate-400'>Harga beli terbaru</small><strong class='text-amber-300'>Rp {{ number_format((float)$product->purchase_price,0,',','.') }}</strong></div>
                        <div><small class='block text-slate-400'>Harga jual</small><strong class='text-emerald-300'>Rp {{ number_format((float)$product->selling_price,0,',','.') }}</strong></div>
                        <div class='flex flex-wrap gap-2'>
                            <button type='button' x-on:click='batchesOpen = ! batchesOpen' class='rounded-xl border border-blue-800 px-3 py-2 text-xs font-semibold text-blue-200 hover:bg-blue-950'>{{ $product->inventoryBatches->count() }} Batch FIFO</button>
                            @if(auth()->user()->role !== 'cashier')<a href='{{ route('products.edit',$product) }}' class='rounded-xl bg-indigo-600 px-3 py-2 text-xs font-semibold text-white'>Edit</a>@endif
                        </div>
                    </div>
                    <div x-cloak x-show='batchesOpen' x-transition class='border-t border-blue-950 bg-slate-950/70'>
                        <div class='overflow-x-auto p-4'>
                            <table class='min-w-full text-sm'>
                                <thead><tr><th>Urutan FIFO</th><th>Tanggal Masuk</th><th>Supplier</th><th>Sisa Batch</th><th>Harga Beli</th><th>Nilai Persediaan</th></tr></thead>
                                <tbody>
                                    @forelse($product->inventoryBatches as $batch)
                                        <tr><td>#{{ $loop->iteration }}</td><td>{{ $batch->received_at->format('d/m/Y') }}</td><td>{{ $batch->supplier?->name ?? 'Saldo awal' }}</td><td>{{ number_format((float)$batch->remaining_quantity,2,',','.') }} {{ $product->unit }}</td><td>Rp {{ number_format((float)$batch->unit_cost,0,',','.') }}</td><td>Rp {{ number_format((float)$batch->remaining_quantity * (float)$batch->unit_cost,0,',','.') }}</td></tr>
                                    @empty
                                        <tr><td colspan='6' class='text-center text-slate-400'>Belum ada batch penerimaan. Stok lama otomatis menjadi saldo awal saat transaksi FIFO pertama.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </article>
            @empty
                <div class='erp-panel p-10 text-center text-slate-400'>Belum ada produk.</div>
            @endforelse
        </div>
        <div class='mt-5'>{{ $products->links() }}</div>
    </div>
</x-app-layout>
