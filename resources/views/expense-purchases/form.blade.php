<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">{{ $expensePurchase->exists ? 'Edit Pembelian' : 'Pembelian Baru' }}</h2>
    </x-slot>

    <div class="mx-auto max-w-4xl p-4 sm:p-8">
        <div class="mb-6">
            <p class="text-sm text-blue-300">Pembelian di luar produk jadi; sisa belum dibayar tercatat sebagai piutang pembelian.</p>
            <h3 class="text-2xl font-bold">{{ $expensePurchase->exists ? 'Perbarui Pembelian' : 'Catat Pembelian' }}</h3>
        </div>

        <form method="POST" action="{{ $expensePurchase->exists ? route('expense-purchases.update', $expensePurchase) : route('expense-purchases.store') }}" class="grid gap-5 rounded-2xl border border-blue-900 bg-slate-900 p-6 sm:grid-cols-2">
            @csrf
            @if($expensePurchase->exists)
                @method('PUT')
            @endif

            @php($fieldClass = 'mt-2 w-full rounded-xl border-blue-900 bg-slate-950 text-white')

            <label class="text-sm text-blue-100">Nomor
                <input name="number" value="{{ old('number', $expensePurchase->number ?? 'PB-'.now()->format('YmdHis')) }}" class="{{ $fieldClass }}">
                @error('number')<small class="text-rose-400">{{ $message }}</small>@enderror
            </label>
            <label class="text-sm text-blue-100">Tanggal
                <input type="date" name="date" value="{{ old('date', $expensePurchase->date?->toDateString() ?? now()->toDateString()) }}" class="{{ $fieldClass }}">
            </label>
            <label class="text-sm text-blue-100">Pilihan Pembelian
                <select name="purchase_type" class="{{ $fieldClass }}">
                    <option value="barang" @selected(old('purchase_type', $expensePurchase->purchase_type) === 'barang')>Barang</option>
                    <option value="kebutuhan" @selected(old('purchase_type', $expensePurchase->purchase_type ?? 'kebutuhan') === 'kebutuhan')>Kebutuhan</option>
                    <option value="asset" @selected(old('purchase_type', $expensePurchase->purchase_type) === 'asset')>Asset</option>
                </select>
                @error('purchase_type')<small class="text-rose-400">{{ $message }}</small>@enderror
            </label>
            <label class="text-sm text-blue-100">Dokumen Pembelian
                <select name="document_type" class="{{ $fieldClass }}">
                    <option value="quotation_po" @selected(old('document_type', $expensePurchase->document_type) === 'quotation_po')>Quotation / PO</option>
                    <option value="delivery_order" @selected(old('document_type', $expensePurchase->document_type) === 'delivery_order')>Delivery Order</option>
                    <option value="invoice" @selected(old('document_type', $expensePurchase->document_type ?? 'invoice') === 'invoice')>Invoice</option>
                    <option value="retur" @selected(old('document_type', $expensePurchase->document_type) === 'retur')>Retur</option>
                    <option value="piutang" @selected(old('document_type', $expensePurchase->document_type) === 'piutang')>Piutang</option>
                </select>
                @error('document_type')<small class="text-rose-400">{{ $message }}</small>@enderror
            </label>
            <label class="text-sm text-blue-100">Supplier (opsional)
                <select name="supplier_id" class="{{ $fieldClass }}">
                    <option value="">Tanpa supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" @selected((string) old('supplier_id', $expensePurchase->supplier_id) === (string) $supplier->id)>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm text-blue-100">Nama Barang/Kebutuhan/Asset
                <input name="description" value="{{ old('description', $expensePurchase->description) }}" placeholder="Contoh: Laptop operasional" class="{{ $fieldClass }}">
            </label>
            <label class="text-sm text-blue-100">Total Pembelian
                <input type="number" min="1" name="total" value="{{ old('total', $expensePurchase->total) }}" class="{{ $fieldClass }}">
            </label>
            <label class="text-sm text-blue-100">Sudah Dibayar
                <input type="number" min="0" name="paid_amount" value="{{ old('paid_amount', $expensePurchase->paid_amount ?? 0) }}" class="{{ $fieldClass }}">
                <small class="mt-1 block text-slate-400">Jika belum lunas, sisanya otomatis masuk piutang pembelian.</small>
            </label>
            <label class="text-sm text-blue-100 sm:col-span-2">Catatan
                <textarea name="notes" rows="3" class="{{ $fieldClass }}">{{ old('notes', $expensePurchase->notes) }}</textarea>
            </label>
            <div class="flex justify-end gap-3 sm:col-span-2">
                <a href="{{ route('expense-purchases.index') }}" class="erp-btn-secondary">Batal</a>
                <button class="erp-btn-primary">Simpan Pembelian</button>
            </div>
        </form>
    </div>
</x-app-layout>
