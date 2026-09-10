<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Dagang ERP menghubungkan penjualan, pembelian, stok, kas dan bank dalam satu sistem operasional.">
    <title>Dagang ERP — Sistem Operasional Perusahaan</title>
    <script>
        (() => {
            const theme = localStorage.getItem('erp-theme') || 'dark';
            document.documentElement.dataset.theme = theme;
            document.documentElement.classList.toggle('dark', theme === 'dark');
        })();
    </script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ theme: document.documentElement.dataset.theme, toggleTheme() { this.theme = this.theme === 'dark' ? 'light' : 'dark'; localStorage.setItem('erp-theme', this.theme); document.documentElement.dataset.theme = this.theme; document.documentElement.classList.toggle('dark', this.theme === 'dark'); } }" class="landing-page bg-[#060b19] font-sans text-white antialiased">
    <header class="sticky top-0 z-50 border-b border-blue-950/80 bg-[#060b19]/90 backdrop-blur-xl">
        <nav class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-5 py-4 lg:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 font-extrabold shadow-lg shadow-blue-950">ERP</span>
                <span><strong class="block text-lg leading-none">Dagang ERP</strong><small class="mt-1 block text-blue-300">Sistem Operasional</small></span>
            </a>
            <div class="hidden items-center gap-7 text-sm font-semibold text-slate-300 md:flex">
                <a href="#fitur" class="transition hover:text-blue-300">Fitur</a>
                <a href="#modul" class="transition hover:text-blue-300">Modul</a>
                <a href="#alur" class="transition hover:text-blue-300">Cara Kerja</a>
                <a href="#manfaat" class="transition hover:text-blue-300">Manfaat</a>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" x-on:click="toggleTheme()" class="grid h-11 w-11 place-items-center rounded-xl border border-blue-700 bg-blue-950/50 text-blue-200 transition hover:bg-blue-900" aria-label="Ganti tema">
                    <svg x-cloak x-show="theme === 'dark'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364-.707-.707M6.343 6.343l-.707-.707m12.728 0-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg x-show="theme === 'light'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                </button>
                @auth
                    <a href="{{ route('dashboard') }}" class="landing-primary-button rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg transition hover:bg-indigo-500">Buka Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="landing-login-button rounded-xl border border-blue-700 bg-blue-950/50 px-5 py-2.5 text-sm font-bold text-blue-100 transition hover:bg-blue-900">Masuk</a>
                @endauth
            </div>
        </nav>
    </header>

    <main>
        <section class="relative overflow-hidden">
            <div class="pointer-events-none absolute -left-40 top-10 h-[30rem] w-[30rem] rounded-full bg-blue-600/20 blur-3xl"></div>
            <div class="pointer-events-none absolute -right-40 bottom-0 h-[30rem] w-[30rem] rounded-full bg-indigo-600/20 blur-3xl"></div>
            <div class="relative mx-auto grid max-w-7xl items-center gap-14 px-5 py-20 lg:grid-cols-2 lg:px-8 lg:py-28">
                <div>
                    <span class="inline-flex rounded-full border border-blue-700/60 bg-blue-950/60 px-4 py-2 text-xs font-bold uppercase tracking-[0.24em] text-blue-300">ERP untuk perusahaan dagang</span>
                    <h1 class="mt-7 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl lg:text-6xl">Kelola seluruh operasional bisnis dalam <span class="bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">satu sistem.</span></h1>
                    <p class="mt-6 max-w-xl text-lg leading-8 text-slate-400">Dagang ERP menghubungkan penjualan online dan offline, pembelian, stok FIFO, pengiriman, kas bank, dan laporan agar seluruh tim bekerja dengan data yang sama.</p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        @auth
                            <a href="{{ route('dashboard') }}" class="landing-primary-button rounded-xl bg-indigo-600 px-6 py-3.5 font-bold text-white shadow-xl shadow-indigo-950 transition hover:-translate-y-0.5 hover:bg-indigo-500">Masuk ke Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="landing-primary-button rounded-xl bg-indigo-600 px-6 py-3.5 font-bold text-white shadow-xl shadow-indigo-950 transition hover:-translate-y-0.5 hover:bg-indigo-500">Mulai Sekarang</a>
                        @endauth
                        <a href="#fitur" class="rounded-xl border border-slate-700 bg-slate-900/70 px-6 py-3.5 font-bold text-slate-200 transition hover:border-blue-600 hover:bg-slate-800">Lihat Semua Fitur</a>
                    </div>
                    <div class="mt-8 flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-400">
                        @foreach (['Role-based access', 'Stok FIFO otomatis', 'Laporan siap pantau'] as $item)
                            <span class="inline-flex items-center gap-2"><i class="h-2 w-2 rounded-full bg-emerald-400"></i>{{ $item }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-3xl border border-blue-800/70 bg-slate-900/90 p-5 shadow-2xl shadow-blue-950/60">
                    <div class="flex items-center justify-between border-b border-blue-950 pb-4"><div class="flex items-center gap-3"><span class="grid h-9 w-9 place-items-center rounded-lg bg-indigo-600 font-bold text-white">D</span><strong>Business Overview</strong></div><span class="rounded-full bg-emerald-950 px-3 py-1 text-xs font-bold text-emerald-300">Live overview</span></div>
                    <div class="mt-5 grid gap-3 sm:grid-cols-3">
                        @foreach ([['Penjualan', 'Terintegrasi', 'Quotation hingga invoice'], ['Persediaan', 'Terpantau', 'Mutasi stok otomatis'], ['Keuangan', 'Terkontrol', 'Tunai dan rekening bank']] as [$label, $value, $text])
                            <article class="rounded-2xl border border-blue-950 bg-slate-950/70 p-4"><small class="text-blue-300">{{ $label }}</small><strong class="mt-2 block text-lg">{{ $value }}</strong><span class="mt-1 block text-xs text-slate-500">{{ $text }}</span></article>
                        @endforeach
                    </div>
                    <div class="mt-4 rounded-2xl border border-blue-950 bg-slate-950/70 p-5">
                        <div class="flex items-center justify-between"><strong class="text-sm">Aktivitas transaksi</strong><small class="text-slate-500">Periode berjalan</small></div>
                        <div class="mt-8 flex h-40 items-end gap-3 border-b border-blue-950 px-3">
                            @foreach (['h-[42%]', 'h-[63%]', 'h-[52%]', 'h-[78%]', 'h-[67%]', 'h-[91%]', 'h-[76%]'] as $heightClass)
                                <i class="{{ $heightClass }} flex-1 rounded-t-lg bg-gradient-to-t from-blue-700 to-indigo-400"></i>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-4 grid gap-2 text-xs text-slate-300 sm:grid-cols-3"><span>● PO diterima</span><span class="text-emerald-300">● DO dikirim</span><span class="text-amber-300">● Invoice diproses</span></div>
                </div>
            </div>
        </section>

        <section class="border-y border-blue-950 bg-slate-900/70">
            <div class="mx-auto grid max-w-7xl grid-cols-2 divide-x divide-blue-950 px-5 md:grid-cols-4 lg:px-8">
                @foreach ([['8+', 'Modul bisnis terhubung'], ['4', 'Akun kas dan bank'], ['7', 'Role pengguna'], ['1', 'Sumber data perusahaan']] as [$number, $label])
                    <div class="px-4 py-7 text-center"><strong class="block text-3xl text-blue-400">{{ $number }}</strong><span class="mt-1 block text-xs text-slate-400">{{ $label }}</span></div>
                @endforeach
            </div>
        </section>

        <section id="fitur" class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
            <div class="max-w-2xl"><span class="text-xs font-bold uppercase tracking-[0.3em] text-blue-400">Fitur Utama</span><h2 class="mt-4 text-3xl font-extrabold sm:text-4xl">Dirancang mengikuti alur perusahaan dagang.</h2><p class="mt-4 leading-7 text-slate-400">Setiap transaksi saling terhubung sehingga data cukup dicatat satu kali dan dapat dipantau oleh bagian yang berkepentingan.</p></div>
            <div class="mt-10 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach ([['Penjualan', 'POS offline, quotation online, Delivery Order, invoice, retur, dan piutang.'], ['Pembelian', 'Daftar pembelian barang, kebutuhan, dan aset yang lebih terstruktur.'], ['Produk & Stok', 'Pantau stok masuk, stok keluar, persediaan minimum, dan alur FIFO.'], ['Kas & Bank', 'Pembayaran tunai, BCA, BNI, dan Mandiri tercatat ke akun yang sesuai.'], ['Laporan', 'Ringkasan harian, bulanan, atau tahunan untuk membantu keputusan bisnis.'], ['Manajemen Role', 'Akses pengguna sesuai tugas dengan kontrol khusus Superadmin.']] as [$title, $text])
                    <article class="group rounded-2xl border border-blue-950 bg-slate-900/70 p-6 transition hover:-translate-y-1 hover:border-blue-700 hover:bg-slate-900"><span class="grid h-11 w-11 place-items-center rounded-xl bg-blue-950 text-blue-300 transition group-hover:bg-indigo-600 group-hover:text-white">◆</span><h3 class="mt-5 text-lg font-bold">{{ $title }}</h3><p class="mt-2 text-sm leading-6 text-slate-400">{{ $text }}</p></article>
                @endforeach
            </div>
        </section>

        <section id="modul" class="border-y border-blue-950 bg-slate-900/60">
            <div class="mx-auto grid max-w-7xl gap-12 px-5 py-24 lg:grid-cols-2 lg:px-8">
                <div><span class="text-xs font-bold uppercase tracking-[0.3em] text-blue-400">Satu Workspace</span><h2 class="mt-4 text-3xl font-extrabold sm:text-4xl">Semua departemen bekerja dengan data yang sama.</h2><p class="mt-5 leading-7 text-slate-400">Kasir, purchasing, warehouse, sales, finance, admin, dan Superadmin memiliki akses sesuai tanggung jawab tanpa memisahkan sumber data.</p></div>
                <div class="grid gap-3">
                    @foreach ([['01', 'Kasir', 'Penjualan offline dan pembayaran'], ['02', 'Purchasing', 'Pembelian dan penerimaan supplier'], ['03', 'Warehouse', 'Stok FIFO dan pengiriman'], ['04', 'Finance', 'Kas, bank, piutang, dan laporan']] as [$number, $title, $text])
                        <article class="flex items-center gap-5 rounded-2xl border border-blue-950 bg-slate-950/70 p-5"><span class="text-2xl font-extrabold text-indigo-400">{{ $number }}</span><div><strong>{{ $title }}</strong><p class="mt-1 text-sm text-slate-500">{{ $text }}</p></div></article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="alur" class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
            <div class="text-center"><span class="text-xs font-bold uppercase tracking-[0.3em] text-blue-400">Alur Transaksi</span><h2 class="mt-4 text-3xl font-extrabold">Dari pesanan sampai laporan, mudah dilacak.</h2></div>
            <div class="mt-10 grid gap-4 md:grid-cols-3">
                @foreach ([['1', 'Buat transaksi', 'Catat penjualan atau pembelian dari form yang sesuai.'], ['2', 'Proses barang', 'Stok dan pengiriman diperbarui mengikuti proses transaksi.'], ['3', 'Pantau keuangan', 'Pembayaran masuk ke kas atau bank dan tampil pada laporan.']] as [$number, $title, $text])
                    <article class="rounded-2xl border border-blue-950 bg-slate-900/70 p-6"><span class="grid h-10 w-10 place-items-center rounded-full bg-indigo-600 font-bold text-white">{{ $number }}</span><h3 class="mt-5 font-bold">{{ $title }}</h3><p class="mt-2 text-sm leading-6 text-slate-400">{{ $text }}</p></article>
                @endforeach
            </div>
        </section>

        <section id="manfaat" class="mx-auto max-w-7xl px-5 pb-24 lg:px-8">
            <div class="landing-cta rounded-3xl border border-indigo-500/40 bg-gradient-to-br from-blue-700 to-indigo-700 px-6 py-12 text-center text-white shadow-2xl shadow-indigo-950/40 sm:px-12"><h2 class="text-3xl font-extrabold">Siap mengelola operasional lebih terstruktur?</h2><p class="mx-auto mt-4 max-w-2xl text-blue-100">Masuk menggunakan akun perusahaan. Pembuatan dan pengaturan akun dikelola secara aman oleh Superadmin.</p><a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="mt-7 inline-flex rounded-xl bg-white px-6 py-3 font-bold text-indigo-700 transition hover:-translate-y-0.5 hover:bg-blue-50">{{ auth()->check() ? 'Buka Dashboard' : 'Masuk ke Dagang ERP' }}</a></div>
        </section>
    </main>

    <footer class="border-t border-blue-950 px-5 py-8"><div class="mx-auto flex max-w-7xl flex-col justify-between gap-3 text-sm text-slate-500 sm:flex-row"><span>© {{ now()->year }} Dagang ERP</span><span>Internal System · ERP Perusahaan Dagang</span></div></footer>
</body>
</html>
