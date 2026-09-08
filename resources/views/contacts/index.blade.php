@php($contactType = request('type', 'customer'))
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">{{ $contactType === 'supplier' ? 'Supplier' : 'Customer' }}</h2>
    </x-slot>

    <div class="mx-auto max-w-7xl p-4 sm:p-8">
        <div class="mb-4">
            <p class="text-sm text-blue-300">{{ $contactType === 'supplier' ? 'Data pemasok perusahaan' : 'Data pelanggan perusahaan' }}</p>
            <h3 class="text-2xl font-bold">Daftar {{ $contactType === 'supplier' ? 'Supplier' : 'Customer' }}</h3>
        </div>

        <div class="overflow-hidden rounded-2xl border border-blue-900 bg-slate-900 shadow-xl">
            <x-list-toolbar 
                create-route="{{ route('contacts.create', ['type' => $contactType]) }}" 
                create-label="Tambah {{ $contactType === 'supplier' ? 'Supplier' : 'Customer' }}" 
                placeholder="Cari nama, email, atau telepon..." 
                :per-page-options="[5, 10, 25]" 
                :default-per-page="5" 
            />

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-blue-950/70 text-slate-300">
                        <tr>
                            <th class="px-4 py-3 text-left">No</th>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Jenis</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Telepon</th>
                            <th class="px-4 py-3 text-left">Alamat</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-950/60 text-slate-200">
                        @forelse($contacts as $contact)
                            <tr class="transition hover:bg-blue-950/30">
                                <td class="px-4 py-3 text-slate-400">{{ $contacts->firstItem() + $loop->index }}</td>
                                <td class="px-4 py-3 font-semibold text-white">{{ $contact->name }}</td>
                                <td class="px-4 py-3">
                                    <span class="erp-badge">{{ ucfirst($contact->type) }}</span>
                                </td>
                                <td class="px-4 py-3 text-slate-300">{{ $contact->email ?: '-' }}</td>
                                <td class="px-4 py-3 text-slate-300">{{ $contact->phone ?: '-' }}</td>
                                <td class="px-4 py-3 text-slate-400 max-w-xs truncate">{{ $contact->address ?: '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('contacts.edit', $contact) }}" class="rounded-lg bg-indigo-600/80 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-indigo-600">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">{{ ucfirst($contactType) }} tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">{{ $contacts->links() }}</div>
    </div>
</x-app-layout>
