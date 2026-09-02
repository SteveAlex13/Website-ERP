<x-app-layout>
<x-slot name='header'><div><p class='text-xs font-semibold uppercase tracking-[0.25em] text-blue-400'>Master Data</p><h2 class='text-xl font-semibold'>{{ $contact->exists ? 'Edit Kontak' : 'Tambah Kontak' }}</h2></div></x-slot>
<div class='mx-auto max-w-4xl p-4 sm:p-8'>
@if($errors->any())<div class='mb-5 rounded-xl border border-red-500/40 bg-red-950/60 p-4 text-sm text-red-200'>{{ $errors->first() }}</div>@endif
<form method='POST' action='{{ $contact->exists ? route('contacts.update',$contact) : route('contacts.store') }}' class='overflow-hidden rounded-2xl border border-blue-900 bg-slate-900 shadow-2xl'>@csrf @if($contact->exists)@method('PUT')@endif
<div class='border-b border-blue-950 bg-slate-950/60 p-6'><div class='flex items-center gap-4'><span class='grid h-14 w-14 place-items-center rounded-2xl bg-blue-600 text-xl font-bold text-white'>{{ strtoupper(substr($contact->name ?: 'K',0,1)) }}</span><div><h3 class='text-xl font-bold text-white'>Informasi Kontak</h3><p class='text-sm text-slate-400'>Data customer atau supplier perusahaan.</p></div></div></div>
<div class='grid gap-5 p-6 sm:grid-cols-2'>
<label class='grid gap-2 text-sm font-semibold'>Tipe Kontak<select name='type' class='w-full rounded-xl'><option value='customer' @selected(old('type',$contact->type)==='customer')>Customer</option><option value='supplier' @selected(old('type',$contact->type)==='supplier')>Supplier</option></select><small class='font-normal text-slate-400'>Tentukan hubungan kontak dengan perusahaan.</small></label>
<label class='grid gap-2 text-sm font-semibold'>Nama Lengkap / Perusahaan<input name='name' value='{{ old('name',$contact->name) }}' class='w-full rounded-xl' placeholder='Masukkan nama kontak'></label>
<label class='grid gap-2 text-sm font-semibold'>Nomor Telepon<input name='phone' value='{{ old('phone',$contact->phone) }}' class='w-full rounded-xl' placeholder='08xxxxxxxxxx'></label>
<label class='grid gap-2 text-sm font-semibold'>Alamat Email<input type='email' name='email' value='{{ old('email',$contact->email) }}' class='w-full rounded-xl' placeholder='nama@perusahaan.com'></label>
<label class='grid gap-2 text-sm font-semibold sm:col-span-2'>Alamat Lengkap<textarea name='address' rows='4' class='w-full rounded-xl' placeholder='Jalan, kota, dan kode pos'>{{ old('address',$contact->address) }}</textarea></label>
</div>
<div class='flex justify-end gap-3 border-t border-blue-950 bg-slate-950/40 p-5'><a href='{{ route('contacts.index') }}' class='erp-btn-secondary'>Batal</a><button class='erp-btn-primary'>{{ $contact->exists ? 'Simpan Perubahan' : 'Tambah Kontak' }}</button></div>
</form></div>
</x-app-layout>
