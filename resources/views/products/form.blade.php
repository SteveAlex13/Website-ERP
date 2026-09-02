<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-blue-400">Master Data Produk</p>
                <h2 class="text-xl font-semibold text-white">{{ $product->exists ? 'Edit Produk' : 'Tambah Produk Baru' }}</h2>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-700 bg-slate-900 px-4 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-800 hover:text-white transition">
                &larr; Kembali ke Daftar Produk
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl p-4 sm:p-8">
        <div class="mb-6">
            <p class="text-sm text-blue-300">Isi informasi data barang, harga, dan jumlah stok persediaan</p>
            <h3 class="text-2xl font-bold text-white">{{ $product->exists ? 'Form Ubah Produk' : 'Form Registrasi Produk Baru' }}</h3>
        </div>

        <form method="POST" action="{{ $product->exists ? route('products.update', $product) : route('products.store') }}" class="grid gap-5 rounded-2xl border border-blue-900 bg-slate-900 p-6 sm:grid-cols-2 shadow-xl">
            @csrf
            @if($product->exists)
                @method('PUT')
            @endif

            <!-- SKU / Kode Produk -->
            <label class="block text-sm text-blue-100">
                <span>SKU / Kode Produk <b class="text-red-400">*</b></span>
                <input name="sku" value="{{ old('sku', $product->sku) }}" placeholder="Contoh: PRD-001" required class="mt-2 w-full rounded-xl border border-blue-900 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none">
                @error('sku')
                    <small class="mt-1 block text-rose-400">{{ $message }}</small>
                @enderror
            </label>

            <!-- Nama Produk -->
            <label class="block text-sm text-blue-100">
                <span>Nama Produk <b class="text-red-400">*</b></span>
                <input name="name" value="{{ old('name', $product->name) }}" placeholder="Contoh: Kopi Arabika 250g" required class="mt-2 w-full rounded-xl border border-blue-900 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none">
                @error('name')
                    <small class="mt-1 block text-rose-400">{{ $message }}</small>
                @enderror
            </label>

            <!-- Satuan Produk -->
            <label class="block text-sm text-blue-100">
                <span>Satuan Unit <b class="text-red-400">*</b></span>
                <input name="unit" value="{{ old('unit', $product->unit ?? 'pcs') }}" placeholder="Contoh: pcs, box, kg, lusin" required class="mt-2 w-full rounded-xl border border-blue-900 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none">
                @error('unit')
                    <small class="mt-1 block text-rose-400">{{ $message }}</small>
                @enderror
            </label>

            <!-- Stok Barang -->
            <label class="block text-sm text-blue-100">
                <span class="flex items-center justify-between">
                    <span>{{ $product->exists ? 'Jumlah Stok Saat Ini' : 'Stok Barang (Stok Awal)' }} <b class="text-red-400">*</b></span>
                    @if(!$product->exists)
                        <small class="text-xs text-indigo-400">Otomatis masuk ke batch FIFO</small>
                    @endif
                </span>
                <input type="number" step="1" min="0" name="stock" value="{{ old('stock', isset($product->stock) ? (float)$product->stock : 0) }}" placeholder="0" class="mt-2 w-full rounded-xl border border-blue-900 bg-slate-950 px-4 py-2.5 text-white font-semibold placeholder-slate-500 focus:border-indigo-500 focus:outline-none">
                @error('stock')
                    <small class="mt-1 block text-rose-400">{{ $message }}</small>
                @enderror
            </label>

            <!-- Harga Beli -->
            <label class="block text-sm text-blue-100">
                <span>Harga Beli Satuan (Rp) <b class="text-red-400">*</b></span>
                <input type="number" min="0" name="purchase_price" value="{{ old('purchase_price', isset($product->purchase_price) ? (int)(float)$product->purchase_price : 0) }}" placeholder="0" required class="mt-2 w-full rounded-xl border border-blue-900 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none">
                @error('purchase_price')
                    <small class="mt-1 block text-rose-400">{{ $message }}</small>
                @enderror
            </label>

            <!-- Harga Jual -->
            <label class="block text-sm text-blue-100">
                <span>Harga Jual Satuan (Rp) <b class="text-red-400">*</b></span>
                <input type="number" min="0" name="selling_price" value="{{ old('selling_price', isset($product->selling_price) ? (int)(float)$product->selling_price : 0) }}" placeholder="0" required class="mt-2 w-full rounded-xl border border-blue-900 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none">
                @error('selling_price')
                    <small class="mt-1 block text-rose-400">{{ $message }}</small>
                @enderror
            </label>

            <!-- Minimum Stok (Batas Peringatan Menipis) -->
            <label class="block text-sm text-blue-100 sm:col-span-2">
                <span>Batas Minimum Stok (Alert Stok Menipis) <b class="text-red-400">*</b></span>
                <input type="number" step="1" min="0" name="minimum_stock" value="{{ old('minimum_stock', isset($product->minimum_stock) ? (int)(float)$product->minimum_stock : 5) }}" placeholder="Contoh: 5" required class="mt-2 w-full rounded-xl border border-blue-900 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none">
                <small class="mt-1 block text-xs text-slate-400">Sistem akan memicu notifikasi peringatan jika sisa stok berada di bawah batas ini.</small>
                @error('minimum_stock')
                    <small class="mt-1 block text-rose-400">{{ $message }}</small>
                @enderror
            </label>

            <!-- Tombol Submit -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-blue-950 sm:col-span-2">
                <a href="{{ route('products.index') }}" class="rounded-xl border border-slate-700 bg-slate-800 px-5 py-2.5 text-sm font-semibold text-slate-300 hover:bg-slate-700 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg hover:bg-indigo-500 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

