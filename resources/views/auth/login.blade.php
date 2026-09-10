<x-guest-layout>
    <div class="mb-7">
        <span class="inline-flex rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-blue-700 dark:border-blue-800 dark:bg-blue-950/60 dark:text-blue-300">Akses Internal</span>
        <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950 dark:text-white">Selamat datang kembali</h2>
        <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">Masuk menggunakan akun yang diberikan oleh Superadmin.</p>
    </div>

    <x-auth-session-status class="mb-5 rounded-xl border border-emerald-300 bg-emerald-50 p-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <div>
            <x-input-label for="email" value="Email" class="text-slate-700 dark:text-slate-300" />
            <div class="relative mt-2">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-3.6 7.2"/></svg>
                <x-text-input id="email" class="block w-full rounded-xl border-slate-300 bg-slate-50 py-3 pl-11 text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-900 dark:bg-slate-950 dark:text-white" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@perusahaan.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Password" class="text-slate-700 dark:text-slate-300" />
            <div class="relative mt-2">
                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <x-text-input id="password" class="block w-full rounded-xl border-slate-300 bg-slate-50 py-3 pl-11 text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-900 dark:bg-slate-950 dark:text-white" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-blue-900 dark:bg-slate-950" name="remember">
                Ingat saya
            </label>
            @if (Route::has('password.request'))
                <a class="rounded text-sm font-semibold text-blue-600 transition hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:text-blue-300" href="{{ route('password.request') }}">Lupa password?</a>
            @endif
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-indigo-900/20 transition hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
            Masuk ke Dashboard
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </button>
    </form>
</x-guest-layout>
