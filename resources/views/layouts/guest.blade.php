<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Dagang ERP') }}</title>
        <script>
            (() => {
                const theme = localStorage.getItem('erp-theme') || 'dark';
                document.documentElement.classList.toggle('dark', theme === 'dark');
            })();
        </script>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <main class="relative min-h-screen overflow-hidden bg-slate-50 text-slate-900 dark:bg-[#050a18] dark:text-white">
            <div class="pointer-events-none absolute -left-32 -top-32 h-96 w-96 rounded-full bg-blue-500/20 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-40 -right-24 h-[30rem] w-[30rem] rounded-full bg-indigo-600/20 blur-3xl"></div>

            <div class="relative mx-auto grid min-h-screen max-w-7xl items-center gap-12 px-5 py-10 lg:grid-cols-[1.05fr_0.95fr] lg:px-10">
                <section class="hidden lg:block">
                    <div class="inline-flex items-center gap-3 rounded-2xl border border-blue-200 bg-white/70 px-4 py-3 shadow-sm backdrop-blur dark:border-blue-900 dark:bg-slate-900/60">
                        <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 font-extrabold text-white shadow-lg shadow-blue-900/20">ERP</span>
                        <div><strong class="block text-lg">Dagang ERP</strong><span class="text-xs text-blue-600 dark:text-blue-300">Sistem Operasional Perusahaan</span></div>
                    </div>
                    <p class="mt-10 text-xs font-bold uppercase tracking-[0.35em] text-blue-600 dark:text-blue-400">Kelola bisnis dalam satu sistem</p>
                    <h1 class="mt-4 max-w-xl text-5xl font-extrabold leading-tight tracking-tight">Operasional dagang lebih rapi, cepat, dan terkontrol.</h1>
                    <p class="mt-5 max-w-lg text-lg leading-8 text-slate-600 dark:text-slate-400">Pantau penjualan, stok FIFO, pembelian, piutang, serta kas dan bank dari satu dashboard terpadu.</p>
                    <div class="mt-9 grid max-w-xl grid-cols-3 gap-3">
                        @foreach ([['Penjualan', 'Online & offline'], ['Persediaan', 'Stok FIFO'], ['Keuangan', 'Kas & bank']] as [$title, $description])
                            <div class="rounded-2xl border border-blue-200 bg-white/60 p-4 backdrop-blur dark:border-blue-950 dark:bg-slate-900/50">
                                <strong class="block text-sm">{{ $title }}</strong><span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">{{ $description }}</span>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="mx-auto w-full max-w-md">
                    <div class="mb-6 flex items-center gap-3 lg:hidden">
                        <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 font-extrabold text-white">ERP</span>
                        <div><strong class="block text-lg">Dagang ERP</strong><span class="text-xs text-blue-600 dark:text-blue-300">Sistem Operasional</span></div>
                    </div>
                    <div class="overflow-hidden rounded-3xl border border-blue-200 bg-white/90 p-6 shadow-2xl shadow-blue-950/10 backdrop-blur sm:p-8 dark:border-blue-900 dark:bg-slate-900/90 dark:shadow-black/30">
                        {{ $slot }}
                    </div>
                    <p class="mt-5 text-center text-xs text-slate-500 dark:text-slate-500">Dagang ERP · Internal System</p>
                </section>
            </div>
        </main>
    </body>
</html>
