<x-app-layout>
    <x-slot name='header'>
        <div><p class='text-xs font-semibold uppercase tracking-[0.25em] text-blue-400'>Khusus Superadmin</p><h2 class='text-xl font-semibold'>Manajemen Role</h2></div>
    </x-slot>
    <div class='mx-auto max-w-7xl p-4 sm:p-8'>
        @if(session('success'))<div class='mb-5 rounded-xl border border-emerald-500/40 bg-emerald-950/60 p-4 text-sm text-emerald-200'>{{ session('success') }}</div>@endif
        @if($errors->any())<div class='mb-5 rounded-xl border border-red-500/40 bg-red-950/60 p-4 text-sm text-red-200'>{{ $errors->first() }}</div>@endif
        <div class='flex flex-col justify-between gap-3 sm:flex-row sm:items-end'>
            <div><p class='text-sm text-blue-300'>Atur modul yang dapat diakses setiap pengguna.</p><h3 class='text-2xl font-bold'>Daftar Pengguna</h3></div>
            <div class="flex flex-wrap items-center gap-3">
                <span class='rounded-full border border-blue-900 bg-slate-900 px-4 py-2 text-sm text-blue-200'>{{ $users->total() }} pengguna</span>
                <button type="button" x-on:click="$dispatch('open-modal', 'create-managed-user')" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-bold text-white shadow-lg transition hover:bg-indigo-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah User/Akun
                </button>
            </div>
        </div>

        <x-modal name="create-managed-user" :show="$errors->hasAny(['name', 'email', 'password', 'role'])" maxWidth="lg" focusable>
            <form method="POST" action="{{ route('user-roles.store') }}" class="space-y-5 bg-slate-900 p-6 text-white">
                @csrf
                <div class="border-b border-blue-950 pb-4">
                    <h3 class="text-lg font-bold">Tambah User/Akun</h3>
                    <p class="mt-1 text-xs text-slate-400">Buat akun baru dan tentukan aksesnya ke dalam sistem ERP.</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <label class="text-sm font-semibold text-slate-300 sm:col-span-2">Nama Lengkap
                        <input name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="mt-1.5 w-full rounded-xl border-blue-900 bg-slate-950 text-white" placeholder="Nama pengguna">
                        @error('name')<small class="mt-1 block text-red-300">{{ $message }}</small>@enderror
                    </label>
                    <label class="text-sm font-semibold text-slate-300">Email
                        <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="mt-1.5 w-full rounded-xl border-blue-900 bg-slate-950 text-white" placeholder="nama@perusahaan.com">
                        @error('email')<small class="mt-1 block text-red-300">{{ $message }}</small>@enderror
                    </label>
                    <label class="text-sm font-semibold text-slate-300">Role
                        <select name="role" required class="mt-1.5 w-full rounded-xl border-blue-900 bg-slate-950 text-white">
                            @foreach($roles as $value => $label)<option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>@endforeach
                        </select>
                        @error('role')<small class="mt-1 block text-red-300">{{ $message }}</small>@enderror
                    </label>
                    <label class="text-sm font-semibold text-slate-300">Password
                        <input type="password" name="password" required autocomplete="new-password" class="mt-1.5 w-full rounded-xl border-blue-900 bg-slate-950 text-white" placeholder="Password akun">
                        @error('password')<small class="mt-1 block text-red-300">{{ $message }}</small>@enderror
                    </label>
                    <label class="text-sm font-semibold text-slate-300">Konfirmasi Password
                        <input type="password" name="password_confirmation" required autocomplete="new-password" class="mt-1.5 w-full rounded-xl border-blue-900 bg-slate-950 text-white" placeholder="Ulangi password">
                    </label>
                </div>
                <div class="flex justify-end gap-3 border-t border-blue-950 pt-4">
                    <button type="button" x-on:click="$dispatch('close-modal', 'create-managed-user')" class="rounded-xl border border-slate-700 bg-slate-800 px-4 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-slate-700">Kembali</button>
                    <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-indigo-500">Simpan Akun</button>
                </div>
            </form>
        </x-modal>
        <div class='mt-6 overflow-hidden rounded-2xl border border-blue-900 bg-slate-900'>
            <div class='hidden grid-cols-[minmax(220px,1fr)_minmax(180px,0.7fr)_minmax(260px,0.9fr)] gap-4 border-b border-blue-950 bg-slate-950 px-5 py-3 text-xs font-bold uppercase tracking-wider text-blue-300 md:grid'>
                <span>Pengguna</span><span>Role Saat Ini</span><span>Ubah Akses</span>
            </div>
            <div class='divide-y divide-blue-950'>
                @foreach($users as $managedUser)
                    <div class='grid gap-4 p-5 md:grid-cols-[minmax(220px,1fr)_minmax(180px,0.7fr)_minmax(260px,0.9fr)] md:items-center'>
                        <div class='flex items-center gap-3'><span class='grid h-10 w-10 shrink-0 place-items-center rounded-full bg-blue-600 font-bold text-white'>{{ strtoupper(substr($managedUser->name,0,1)) }}</span><span class='min-w-0'><b class='block truncate text-white'>{{ $managedUser->name }}</b><small class='block truncate text-slate-400'>{{ $managedUser->email }}</small></span></div>
                        <div><span class='inline-flex rounded-full bg-blue-950 px-3 py-1 text-xs font-semibold text-blue-200'>{{ $roles[$managedUser->role] ?? ucfirst($managedUser->role) }}</span>@if($managedUser->is(auth()->user()))<small class='ml-2 text-amber-300'>Akun Anda</small>@endif</div>
                        <form method='POST' action='{{ route('user-roles.update',$managedUser) }}' class='flex gap-2'>@csrf @method('PATCH')
                            <select name='role' class='min-w-0 flex-1 rounded-xl text-sm'>@foreach($roles as $value=>$label)<option value='{{ $value }}' @selected($managedUser->role === $value)>{{ $label }}</option>@endforeach</select>
                            <button class='rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500'>Simpan</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
        <div class='mt-5'>{{ $users->links() }}</div>
    </div>
</x-app-layout>
