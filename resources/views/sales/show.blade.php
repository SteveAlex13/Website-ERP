<x-app-layout>
<x-slot name='header'><div><p class='text-xs font-semibold uppercase tracking-[0.25em] text-blue-400'>Detail Transaksi</p><h2 class='text-xl font-semibold'>{{ $sale->bill_name ?: $sale->number }}</h2></div></x-slot>
<div class='mx-auto max-w-5xl p-4 sm:p-8'>
@if(session('success'))<div class='mb-5 rounded-xl border border-emerald-500/40 bg-emerald-950/60 p-4 text-sm text-emerald-200'>{{ session('success') }}</div>@endif
@if($errors->any())<div class='mb-5 rounded-xl border border-red-500/40 bg-red-950/60 p-4 text-sm text-red-200'>{{ $errors->first() }}</div>@endif
<section class='overflow-hidden rounded-2xl border border-blue-900 bg-slate-900'>
<div class='flex flex-col justify-between gap-4 border-b border-blue-950 p-6 sm:flex-row sm:items-center'><div><p class='text-sm text-blue-300'>{{ $sale->number }}</p><h3 class='text-xl font-bold text-white'>{{ $sale->customer?->name ?? 'Pelanggan Umum' }}</h3><div class='mt-2 flex flex-wrap gap-2'><span class='rounded-full bg-blue-950 px-3 py-1 text-xs text-blue-200'>{{ ucfirst($sale->status) }}</span>@if($sale->payment_method)<span class='rounded-full bg-emerald-950 px-3 py-1 text-xs text-emerald-200'>{{ str_replace('_',' ',ucfirst($sale->payment_method)) }} · {{ $sale->bankAccount?->name }}</span>@endif</div></div><a href='{{ route('sales.invoice.print',$sale) }}' target='_blank' class='erp-btn-secondary'>Cetak Invoice</a></div>
<div class='overflow-x-auto p-5'><table class='min-w-full text-sm'><thead><tr><th>Produk</th><th>Jumlah</th><th>Harga</th><th>Subtotal</th><th>Stok</th></tr></thead><tbody>@foreach($sale->items as $item)<tr><td>{{ $item->product->name }}</td><td>{{ $item->quantity }} {{ $item->product->unit }}</td><td>Rp {{ number_format((float)$item->price,0,',','.') }}</td><td>Rp {{ number_format((float)$item->subtotal,0,',','.') }}</td><td>{{ $item->product->stock }}</td></tr>@endforeach</tbody></table></div>
<div class='flex justify-end border-t border-blue-950 p-5 text-xl font-bold text-white'>Total: Rp {{ number_format((float)$sale->total,0,',','.') }}</div>
</section>
@if($sale->document_type === 'quotation' && $sale->status === 'open')
@can('delete', $sale)
<form method='POST' action='{{ route('sales.destroy', $sale) }}' class='mt-5 rounded-2xl border border-red-500/30 bg-red-950/20 p-5'>@csrf @method('DELETE')
<h3 class='font-bold text-red-300'>Hapus Quotation</h3><p class='mt-1 text-sm text-slate-400'>Masukkan key penghapusan dari Super Admin.</p>
<div class='mt-4 flex flex-col gap-3 sm:flex-row'><input type='password' name='quotation_key' required maxlength='100' autocomplete='off' class='min-w-0 flex-1 rounded-xl border-red-900 bg-slate-950 text-white' placeholder='Key penghapusan'><button class='rounded-xl bg-red-600 px-5 py-3 font-bold text-white'>Hapus Transaksi</button></div>
@error('quotation_key')<p class='mt-2 text-sm text-red-300'>{{ $message }}</p>@enderror
</form>
@endcan
@endif
@if(!in_array($sale->status, ['cancelled', 'refunded'], true) && (float)$sale->paid_amount < (float)$sale->total)
<form method="POST" action="{{ route('sales.payment', $sale) }}" x-data="{ paymentIsCash: false }" class="mt-5 grid gap-4 rounded-2xl border border-emerald-500/40 bg-slate-900 p-6 sm:grid-cols-2 shadow-xl">
@csrf
<div class="sm:col-span-3 flex flex-wrap items-center justify-between gap-2 border-b border-blue-950 pb-3">
    <div>
        <h3 class="text-lg font-bold text-white">
            {{ $sale->document_type === 'pos' ? 'Bayar Open Bill' : 'Selesaikan & Bayar Invoice ' . ucfirst($sale->document_type) }}
        </h3>
        <p class="text-xs text-slate-400">
            Catat penerimaan pembayaran dan selesaikan transaksi ke Kas &amp; Bank.
        </p>
    </div>
    <div class="text-right">
        <span class="text-xs text-slate-400">Sisa Tagihan:</span>
        <div class="text-lg font-bold text-emerald-400">
            Rp {{ number_format(max(0, (float)$sale->total - (float)$sale->paid_amount), 0, ',', '.') }}
        </div>
    </div>
</div>
<label class="text-xs font-semibold text-slate-300">Metode Pembayaran
    <select name="bank_account_id" x-on:change="paymentIsCash = $event.target.selectedOptions[0].dataset.cash === '1'" class="mt-1.5 w-full rounded-xl border border-blue-900 bg-slate-950 px-3 py-2 text-xs text-white">
        @foreach($bankAccounts as $account)
            <option value="{{ $account->id }}" data-cash="{{ $account->name === 'Tunai' ? '1' : '0' }}">{{ $account->name === 'Tunai' ? 'Tunai' : str_replace('Bank ', '', $account->name) }}</option>
        @endforeach
    </select>
</label>
<label x-cloak x-show="paymentIsCash" class="text-xs font-semibold text-slate-300">Nilai Tunai (Rp)
    <input type="number" step="any" min="1" name="paid_amount" value="{{ max(0, (float)$sale->total - (float)$sale->paid_amount) }}" x-bind:disabled="!paymentIsCash" x-bind:required="paymentIsCash" class="mt-1.5 w-full rounded-xl border border-blue-900 bg-slate-950 px-3 py-2 text-xs text-white font-semibold">
</label>
<button class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 font-bold text-white transition hover:bg-emerald-500 sm:col-span-2 shadow-lg">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    {{ $sale->document_type === 'pos' ? 'Bayar dan Selesaikan Transaksi' : 'Selesaikan Invoice & Catat Pembayaran' }}
</button>
</form>
@endif
@if($sale->document_type === 'pos' && $sale->status === 'completed')
<form method='POST' action='{{ route('sales.refund',$sale) }}' onsubmit='return confirm(&quot;Refund seluruh transaksi ini?&quot;)' class='mt-5 grid gap-3 rounded-2xl border border-red-500/30 bg-red-950/20 p-5 sm:grid-cols-2'>@csrf<input name='reason' required maxlength='255' class='min-w-0 rounded-xl' placeholder='Alasan refund'><input type='password' name='quotation_key' required maxlength='100' autocomplete='off' class='min-w-0 rounded-xl' placeholder='Key otorisasi Super Admin'><button class='rounded-xl bg-red-600 px-5 py-3 font-bold text-white sm:col-span-2'>Refund Transaksi</button></form>
@endif
@if($sale->document_type==='order' && !in_array($sale->status,['completed','cancelled']))
<form method='POST' action='{{ route('sales.deliveries.store',$sale) }}' class='mt-5 rounded-2xl border border-blue-900 bg-slate-900 p-5'>@csrf<input name='number' value='DO-{{ now()->format('YmdHis') }}' class='rounded-xl'><input type='date' name='date' value='{{ now()->toDateString() }}' class='rounded-xl'>@foreach($sale->items as $index=>$item)<div class='mt-3'><input type='hidden' name='items[{{ $index }}][sale_item_id]' value='{{ $item->id }}'><input name='items[{{ $index }}][quantity]' value='0' class='rounded-xl' placeholder='Qty {{ $item->product->name }}'></div>@endforeach<button class='mt-3 rounded-xl bg-green-600 px-4 py-2 text-white'>Buat Delivery Order</button></form>
@endif
</div>
</x-app-layout>
