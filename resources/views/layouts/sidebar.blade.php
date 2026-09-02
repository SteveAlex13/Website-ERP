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
                <div x-data="{ open: {{ request()->routeIs('contacts.*') ? 'true' : 'false' }} }" class="rounded-xl border border-slate-800/80 bg-slate-900/50 transition">
                    <button type="button" @click="open = !open" class="flex w-full items-center justify-between px-4 py-3 text-left transition rounded-xl hover:bg-slate-800/70 {{ request()->routeIs('contacts.*') ? 'text-white' : 'text-slate-300' }}">
                        <div>
                            <b class="block leading-tight">Kontak</b>
                            <small class="text-xs text-slate-400">Customer &amp; Supplier</small>
                        </div>
                        <svg class="h-4 w-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180 text-indigo-400': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="flex flex-col gap-1 px-2 pb-2 pt-1 border-t border-slate-800/60">
                        <a href="{{ route('contacts.index', ['type' => 'customer']) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium transition {{ request()->routeIs('contacts.*') && request('type', 'customer') === 'customer' ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('contacts.*') && request('type', 'customer') === 'customer' ? 'bg-white' : 'bg-slate-500' }}"></span>
                            Customer
                        </a>
                        <a href="{{ route('contacts.index', ['type' => 'supplier']) }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium transition {{ request()->routeIs('contacts.*') && request('type') === 'supplier' ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ request()->routeIs('contacts.*') && request('type') === 'supplier' ? 'bg-white' : 'bg-slate-500' }}"></span>
                            Supplier
                        </a>
                    </div>
                </div>
                @continue
            @endif
            <a href='{{ route($routeName) }}' class='rounded-xl px-4 py-3 transition {{ request()->routeIs($routeName) ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}'><b class='block'>{{ $label }}</b><small class='text-slate-400'>{{ $caption }}</small></a>
        @endforeach
    </nav>
    <div class='border-t border-slate-800 p-5 text-xs text-slate-500'>Dagang ERP � Internal System</div>
</aside>
<nav class='flex gap-2 overflow-x-auto bg-slate-900 p-3 text-sm text-white lg:hidden'><a href='{{ route('dashboard') }}' class='rounded bg-indigo-600 px-3 py-2'>Dashboard</a><a href='{{ route('products.index') }}' class='rounded bg-slate-800 px-3 py-2'>Produk</a><a href='{{ route('sales.index') }}' class='rounded bg-slate-800 px-3 py-2'>Penjualan</a><a href='{{ route('profile.edit') }}' class='rounded bg-slate-800 px-3 py-2'>Profil</a></nav>
