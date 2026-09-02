<aside x-show='sidebarOpen' x-transition class='fixed inset-y-0 left-0 z-40 hidden w-64 flex-col border-r border-blue-950 bg-slate-950 text-white shadow-xl lg:flex'>
    <a href='{{ route('dashboard') }}' class='flex h-20 items-center gap-3 border-b border-slate-800 px-6'><span class='grid h-10 w-10 place-items-center rounded-xl bg-indigo-500 font-bold'>ERP</span><span><b class='block'>Dagang ERP</b><small class='text-slate-400'>Sistem Operasional</small></span></a>
    <nav class='flex flex-1 flex-col gap-1 overflow-y-auto p-4 text-sm'>
        @php
            $role = auth()->user()->role;
            $links = [['dashboard','Dashboard','Ringkasan'],['products.index','Produk','Lihat Stok Barang']];
            if ($role === 'superadmin') {
                $links[] = ['user-roles.index','Manajemen Role','Akses Pengguna'];
            }
            if (in_array($role,['superadmin','admin','purchasing','sales','finance'])) {
                $links[] = ['contacts-menu','Kontak','Customer & Supplier'];
            }
            $links[] = ['sales.index','POS Kasir','Penjualan Harian'];
            if (in_array($role,['superadmin','admin','purchasing','warehouse'])) {
                $links[] = ['purchases.index','Stock In','Tambah Stok Produk'];
            }
            if (in_array($role,['superadmin','admin','purchasing','finance'])) {
                $links[] = ['expense-purchases.index','Pembelian Kebutuhan','Utang & Kebutuhan Operasional'];
            }
            if (in_array($role,['superadmin','admin','finance'])) {
                $links[] = ['cash-expenses.index','Kas & Bank','Pengeluaran'];
                $links[] = ['assets.index','Pembelian Aset','Asset Management'];
                $links[] = ['reports.daily-financial','Laporan Keuangan Harian','Sales Kotor & Bersih'];
                $links[] = ['reports.bank','Laporan Bank','Mutasi & Saldo Akun'];
                $links[] = ['reports.index','Laporan ERP','Analisis Periode'];
            }
            if (in_array($role,['superadmin','admin','finance','warehouse'])) {
                $links[] = ['product-returns.index','Retur','Retur Jual & Beli'];
            }
        @endphp
        @foreach($links as [$routeName,$label,$caption])
            @if($routeName === 'contacts-menu')
                <div class='rounded-xl border border-slate-800 bg-slate-900/60 p-2'><div class='px-2 py-2'><b class='block text-white'>Kontak</b><small class='text-slate-400'>Pilih jenis kontak</small></div><div class='grid gap-1'><a href='{{ route('contacts.index', ['type' => 'customer']) }}' class='rounded-lg px-3 py-2 {{ request('type') === 'customer' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}'>Customer</a><a href='{{ route('contacts.index', ['type' => 'supplier']) }}' class='rounded-lg px-3 py-2 {{ request('type') === 'supplier' ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}'>Supplier</a></div></div>
                @continue
            @endif
            <a href='{{ route($routeName) }}' class='rounded-xl px-4 py-3 transition {{ request()->routeIs($routeName) ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}'><b class='block'>{{ $label }}</b><small class='text-slate-400'>{{ $caption }}</small></a>
        @endforeach
    </nav>
    <div class='border-t border-slate-800 p-5 text-xs text-slate-500'>Dagang ERP � Internal System</div>
</aside>
<nav class='flex gap-2 overflow-x-auto bg-slate-900 p-3 text-sm text-white lg:hidden'><a href='{{ route('dashboard') }}' class='rounded bg-indigo-600 px-3 py-2'>Dashboard</a><a href='{{ route('products.index') }}' class='rounded bg-slate-800 px-3 py-2'>Produk</a><a href='{{ route('sales.index') }}' class='rounded bg-slate-800 px-3 py-2'>Penjualan</a><a href='{{ route('profile.edit') }}' class='rounded bg-slate-800 px-3 py-2'>Profil</a></nav>
