<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            ERP Perusahaan Dagang
        </h2>
    </x-slot>

    <div class='dashboard-theme mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8'>
        <form method='GET' class='mb-6 grid gap-4 rounded-2xl border border-blue-950 bg-slate-900 p-5 sm:grid-cols-3'>
            <label class='text-sm text-blue-200'>Periode<select class='mt-2 w-full rounded-xl border-blue-950 bg-slate-800 text-cyan-400'><option>Harian</option><option>Mingguan</option><option>Bulanan</option></select></label>
            <label class='text-sm text-blue-200'>Tanggal<input type='date' value='{{ now()->toDateString() }}' class='mt-2 w-full rounded-xl border-blue-950 bg-slate-800 text-cyan-400'></label>
            <div class='flex items-end'><button class='w-full rounded-xl bg-blue-600 px-4 py-2.5 font-semibold text-white hover:bg-blue-500'>Tampilkan</button></div>
        </form>
        <div class='dashboard-metrics grid gap-4 sm:grid-cols-2 xl:grid-cols-3'>
            <a href='{{ route('products.index') }}' class='rounded-xl bg-white p-5 shadow'><span class='text-gray-500'>Produk</span><b class='mt-2 block text-3xl'>{{ $productCount }}</b></a>
            <a href='{{ route('contacts.index') }}' class='rounded-xl bg-white p-5 shadow'><span class='text-gray-500'>Customer & Supplier</span><b class='mt-2 block text-lg'>Buka master</b></a>
            <a href='{{ route('purchases.index') }}' class='rounded-xl bg-white p-5 shadow'><span class='text-gray-500'>Pembelian terbuka</span><b class='mt-2 block text-3xl'>{{ $openPurchaseCount }}</b></a>
            <a href='{{ route('sales.index') }}' class='rounded-xl bg-white p-5 shadow'><span class='text-gray-500'>Penjualan terbuka</span><b class='mt-2 block text-3xl'>{{ $openSaleCount }}</b></a>
            <a href='{{ route('cash-expenses.index') }}' class='rounded-xl bg-white p-5 shadow'><span class='text-gray-500'>Pengeluaran bulan ini</span><b class='mt-2 block'>Rp {{ number_format((float)$monthlyExpense,0,',','.') }}</b></a>
            <a href='{{ route('assets.index') }}' class='rounded-xl bg-white p-5 shadow'><span class='text-gray-500'>Aset</span><b class='mt-2 block text-3xl'>{{ $assetCount }}</b></a>
            <a href='{{ route('reports.index') }}' class='rounded-xl bg-indigo-600 p-5 text-white shadow'><span>Laporan ERP</span><b class='mt-2 block'>Buka laporan</b></a>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
