<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-blue-400">Master Data Kontak</p>
                <h2 class="text-xl font-semibold text-white">{{ $contact->exists ? 'Edit ' . ucfirst($contact->type) : 'Tambah ' . ucfirst(old('type', $contact->type ?: request('type', 'customer'))) . ' Baru' }}</h2>
            </div>
            <a href="{{ route('contacts.index', ['type' => old('type', $contact->type ?: request('type', 'customer'))]) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-700 bg-slate-900 px-4 py-2 text-xs font-semibold text-slate-300 hover:bg-slate-800 hover:text-white transition">
                &larr; Kembali ke Daftar {{ ucfirst(old('type', $contact->type ?: request('type', 'customer'))) }}
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl p-4 sm:p-8">
        <div class="mb-6">
            <p class="text-sm text-blue-300">Isi informasi data identitas, kontak, dan alamat perusahaan</p>
            <h3 class="text-2xl font-bold text-white">{{ $contact->exists ? 'Form Ubah ' . ucfirst($contact->type) : 'Form Registrasi ' . ucfirst(old('type', $contact->type ?: request('type', 'customer'))) . ' Baru' }}</h3>
        </div>

        @if($errors->any())
            <div class="mb-5 rounded-xl border border-red-500/40 bg-red-950/60 p-4 text-sm text-red-200">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ $contact->exists ? route('contacts.update', $contact) : route('contacts.store') }}" class="grid gap-5 rounded-2xl border border-blue-900 bg-slate-900 p-6 sm:grid-cols-2 shadow-xl">
            @csrf
            @if($contact->exists)
                @method('PUT')
            @endif

            <!-- Tipe Kontak -->
            <label class="block text-sm text-blue-100">
                <span>Tipe Kontak <b class="text-red-400">*</b></span>
                <select name="type" required class="mt-2 w-full rounded-xl border border-blue-900 bg-slate-950 px-4 py-2.5 text-white focus:border-indigo-500 focus:outline-none">
                    <option value="customer" @selected(old('type', $contact->type ?: request('type', 'customer')) === 'customer')>Customer (Pelanggan)</option>
                    <option value="supplier" @selected(old('type', $contact->type ?: request('type', 'customer')) === 'supplier')>Supplier (Pemasok)</option>
                </select>
                <small class="mt-1 block text-xs text-slate-400">Tentukan jenis hubungan kontak dengan perusahaan.</small>
                @error('type')
                    <small class="mt-1 block text-rose-400">{{ $message }}</small>
                @enderror
            </label>

            <!-- Nama Kontak -->
            <label class="block text-sm text-blue-100">
                <span>Nama Lengkap / Perusahaan <b class="text-red-400">*</b></span>
                <input name="name" value="{{ old('name', $contact->name) }}" placeholder="Contoh: PT Sumber Makmur atau Budi Santoso" required class="mt-2 w-full rounded-xl border border-blue-900 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none">
                @error('name')
                    <small class="mt-1 block text-rose-400">{{ $message }}</small>
                @enderror
            </label>

            <!-- Nomor Telepon -->
            <label class="block text-sm text-blue-100">
                <span>Nomor Telepon / WhatsApp</span>
                <input name="phone" value="{{ old('phone', $contact->phone) }}" placeholder="Contoh: 081234567890" class="mt-2 w-full rounded-xl border border-blue-900 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none">
                @error('phone')
                    <small class="mt-1 block text-rose-400">{{ $message }}</small>
                @enderror
            </label>

            <!-- Alamat Email -->
            <label class="block text-sm text-blue-100">
                <span>Alamat Email</span>
                <input type="email" name="email" value="{{ old('email', $contact->email) }}" placeholder="Contoh: kontak@perusahaan.com" class="mt-2 w-full rounded-xl border border-blue-900 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none">
                @error('email')
                    <small class="mt-1 block text-rose-400">{{ $message }}</small>
                @enderror
            </label>

            <!-- Alamat Lengkap -->
            <label class="block text-sm text-blue-100 sm:col-span-2">
                <span>Alamat Lengkap</span>
                <textarea name="address" rows="3" placeholder="Jalan, nomor kantor/rumah, kota, dan kode pos" class="mt-2 w-full rounded-xl border border-blue-900 bg-slate-950 px-4 py-2.5 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none">{{ old('address', $contact->address) }}</textarea>
                @error('address')
                    <small class="mt-1 block text-rose-400">{{ $message }}</small>
                @enderror
            </label>

            <!-- Tombol Submit -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-blue-950 sm:col-span-2">
                <a href="{{ route('contacts.index', ['type' => old('type', $contact->type ?: request('type', 'customer'))]) }}" class="rounded-xl border border-slate-700 bg-slate-800 px-5 py-2.5 text-sm font-semibold text-slate-300 hover:bg-slate-700 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg hover:bg-indigo-500 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ $contact->exists ? 'Simpan Perubahan' : 'Simpan Kontak' }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
