<!DOCTYPE html>
<html lang='{{ str_replace('_', '-', app()->getLocale()) }}'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='csrf-token' content='{{ csrf_token() }}'>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script>
        (() => {
            const theme = localStorage.getItem('erp-theme') || 'dark';
            document.documentElement.dataset.theme = theme;
            document.documentElement.classList.toggle('dark', theme === 'dark');
        })();
    </script>
    <link rel='preconnect' href='https://fonts.bunny.net'>
    <link href='https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap' rel='stylesheet'>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class='font-sans antialiased'>
<div class='min-h-screen bg-gray-100 dark:bg-gray-900'>
    <div x-data="{ sidebarOpen: localStorage.sidebarOpen !== String(false), theme: document.documentElement.dataset.theme, toggleTheme() { this.theme = this.theme === 'dark' ? 'light' : 'dark'; localStorage.setItem('erp-theme', this.theme); document.documentElement.dataset.theme = this.theme; document.documentElement.classList.toggle('dark', this.theme === 'dark'); } }" x-bind:data-sidebar-open='sidebarOpen' class='app-shell'>
        <button type='button' x-on:click='sidebarOpen = ! sidebarOpen; localStorage.sidebarOpen = sidebarOpen' class='sidebar-toggle fixed left-4 top-4 z-50 grid h-10 w-10 place-items-center rounded-xl bg-blue-600 text-white shadow-lg transition-all' aria-label='Buka atau tutup sidebar'><span class='sidebar-toggle-icon' aria-hidden='true'><span></span><span></span><span></span></span></button>
        @include('layouts.sidebar')
        <div class='app-content transition-all duration-300'>
            @isset($header)
                <header class='bg-white shadow dark:bg-gray-800'>
                    <div class='mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8'>
                        <div class='flex items-center justify-between gap-4'>
                            <div class='min-w-0 flex-1 overflow-hidden'>{{ $header }}</div>
                            <div class='flex shrink-0 items-center gap-3'>
                                <button type='button' x-on:click='toggleTheme()' x-bind:title="theme === 'dark' ? 'Gunakan tema terang' : 'Gunakan tema gelap'" x-bind:aria-label="theme === 'dark' ? 'Gunakan tema terang' : 'Gunakan tema gelap'" class='theme-toggle group relative grid h-10 w-10 place-items-center overflow-hidden rounded-xl border border-blue-900 bg-slate-900 text-blue-100 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-800'>
                                    <svg x-cloak x-show="theme === 'dark'" x-transition:enter="transition duration-300" x-transition:enter-start="-rotate-90 scale-50 opacity-0" x-transition:enter-end="rotate-0 scale-100 opacity-100" class='h-5 w-5 text-amber-300' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='1.8' aria-hidden='true'><circle cx='12' cy='12' r='4'></circle><path stroke-linecap='round' d='M12 2v2M12 20v2M4.93 4.93l1.42 1.42M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.42-1.42M17.66 6.34l1.41-1.41'></path></svg>
                                    <svg x-cloak x-show="theme === 'light'" x-transition:enter="transition duration-300" x-transition:enter-start="rotate-90 scale-50 opacity-0" x-transition:enter-end="rotate-0 scale-100 opacity-100" class='h-5 w-5 text-blue-600' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='1.8' aria-hidden='true'><path stroke-linecap='round' stroke-linejoin='round' d='M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z'></path></svg>
                                </button>
                                @include('layouts.stock-notification')
                                <div
                                    x-data="{ profileOpen: false, keyAvailable: {{ session('quotation_delete_key') ? 'true' : 'false' }} }"
                                    x-init="if (keyAvailable) { setTimeout(() => { keyAvailable = false; $dispatch('close-modal', 'quotation-key-result') }, {{ session('quotation_delete_key_expires_at') ? max(0, (session('quotation_delete_key_expires_at') - now()->timestamp) * 1000) : 0 }}) }"
                                    class='relative'
                                >
                                    <button type='button' x-on:click='profileOpen = ! profileOpen' class='flex max-w-[15rem] items-center gap-3 rounded-xl border border-blue-900 bg-slate-900 px-3 py-2 text-white sm:max-w-[18rem]'>
                                        <span class='grid h-9 w-9 shrink-0 place-items-center rounded-full bg-blue-600 font-bold'>{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span>
                                        <span class='hidden min-w-0 text-left sm:block'><b class='block truncate text-sm'>{{ auth()->user()->name }}</b><small class='block truncate text-slate-400'>{{ ucfirst(auth()->user()->role) }}</small></span>
                                    </button>
                                    <div x-cloak x-show='profileOpen' x-on:click.outside='profileOpen = false' x-transition class='absolute right-0 z-40 mt-2 w-72 overflow-hidden rounded-xl border border-blue-950 bg-slate-900 p-2 text-sm text-white shadow-2xl'>
                                        <a href='{{ route('profile.edit') }}' class='block rounded-lg px-4 py-3 hover:bg-slate-800'>Pengaturan Profil</a>
                                        @if(auth()->user()->role === 'superadmin')
                                            <div class='border-t border-slate-800 py-1'>
                                                <button type='button' x-on:click="profileOpen = false; $dispatch('open-modal', 'generate-quotation-key')" class='w-full rounded-lg px-4 py-3 text-left text-slate-200 transition hover:bg-slate-800'>Generate Key Quotation</button>
                                                <button type='button' x-cloak x-show='keyAvailable' x-on:click="profileOpen = false; $dispatch('open-modal', 'quotation-key-result')" class='w-full rounded-lg px-4 py-3 text-left text-amber-300 transition hover:bg-slate-800'>Tampilkan Key</button>
                                            </div>
                                        @endif
                                        <form method='POST' action='{{ route('logout') }}'>@csrf<button class='w-full rounded-lg px-4 py-3 text-left text-red-300 hover:bg-red-950'>Keluar</button></form>
                                    </div>

                                    @if(auth()->user()->role === 'superadmin')
                                        <x-modal name='generate-quotation-key' maxWidth='md' focusable>
                                            <div class='bg-slate-900 p-6 text-white'>
                                                <h3 class='text-lg font-bold'>Generate Key Quotation?</h3>
                                                <p class='mt-2 text-sm text-slate-400'>Key lama akan langsung tidak berlaku setelah key baru dibuat.</p>
                                                <div class='mt-6 flex justify-end gap-3'>
                                                    <button type='button' x-on:click="$dispatch('close-modal', 'generate-quotation-key')" class='rounded-lg px-4 py-2 text-sm font-semibold text-slate-300 transition hover:bg-slate-800'>Batal</button>
                                                    <form method='POST' action='{{ route('profile.quotation-delete-key.generate') }}'>@csrf<button class='rounded-lg bg-slate-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-600'>Generate Key</button></form>
                                                </div>
                                            </div>
                                        </x-modal>

                                        <x-modal name='quotation-key-result' :show="session()->has('quotation_delete_key')" maxWidth='md' focusable>
                                            <div class='bg-slate-900 p-6 text-white'>
                                                <h3 class='text-lg font-bold'>Key Penghapusan Quotation</h3>
                                                <p class='mt-2 text-sm text-amber-300'>Key tersedia selama 5 menit. Simpan di tempat yang aman.</p>
                                                <input type='text' readonly value='{{ session('quotation_delete_key') }}' class='mt-5 w-full rounded-lg border-slate-700 bg-slate-950 px-4 py-3 font-mono text-sm text-white'>
                                                <div class='mt-6 flex justify-end'><button type='button' x-on:click="$dispatch('close-modal', 'quotation-key-result')" class='rounded-lg bg-slate-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-600'>Tutup</button></div>
                                            </div>
                                        </x-modal>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
            @endisset
            <main>{{ $slot }}</main>
        </div>
    </div>
</div>
<x-toast-notification />
</body>
</html>
