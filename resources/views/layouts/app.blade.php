<!DOCTYPE html>
<html lang='{{ str_replace('_', '-', app()->getLocale()) }}'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='csrf-token' content='{{ csrf_token() }}'>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel='preconnect' href='https://fonts.bunny.net'>
    <link href='https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap' rel='stylesheet'>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class='font-sans antialiased'>
<div class='min-h-screen bg-gray-100 dark:bg-gray-900'>
    <div x-data='{ sidebarOpen: localStorage.sidebarOpen !== String(false) }' x-bind:data-sidebar-open='sidebarOpen' class='app-shell'>
        <button type='button' x-on:click='sidebarOpen = ! sidebarOpen; localStorage.sidebarOpen = sidebarOpen' class='sidebar-toggle fixed left-4 top-4 z-50 grid h-10 w-10 place-items-center rounded-xl bg-blue-600 text-white shadow-lg transition-all' aria-label='Buka atau tutup sidebar'><span class='sidebar-toggle-icon' aria-hidden='true'><span></span><span></span><span></span></span></button>
        @include('layouts.sidebar')
        <div class='app-content transition-all duration-300'>
            @isset($header)
                <header class='bg-white shadow dark:bg-gray-800'>
                    <div class='mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8'>
                        <div class='flex items-center justify-between gap-4'>
                            <div class='min-w-0 flex-1 overflow-hidden'>{{ $header }}</div>
                            <div class='flex shrink-0 items-center gap-3'>
                                @include('layouts.stock-notification')
                                <div x-data='{ profileOpen: false }' class='relative'>
                                    <button type='button' x-on:click='profileOpen = ! profileOpen' class='flex max-w-[15rem] items-center gap-3 rounded-xl border border-blue-900 bg-slate-900 px-3 py-2 text-white sm:max-w-[18rem]'>
                                        <span class='grid h-9 w-9 shrink-0 place-items-center rounded-full bg-blue-600 font-bold'>{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span>
                                        <span class='hidden min-w-0 text-left sm:block'><b class='block truncate text-sm'>{{ auth()->user()->name }}</b><small class='block truncate text-slate-400'>{{ ucfirst(auth()->user()->role) }}</small></span>
                                    </button>
                                    <div x-cloak x-show='profileOpen' x-on:click.outside='profileOpen = false' x-transition class='absolute right-0 z-50 mt-2 w-56 overflow-hidden rounded-xl border border-blue-950 bg-slate-900 p-2 text-sm text-white shadow-2xl'>
                                        <a href='{{ route('profile.edit') }}' class='block rounded-lg px-4 py-3 hover:bg-slate-800'>Pengaturan Profil</a>
                                        <form method='POST' action='{{ route('logout') }}'>@csrf<button class='w-full rounded-lg px-4 py-3 text-left text-red-300 hover:bg-red-950'>Keluar</button></form>
                                    </div>
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
