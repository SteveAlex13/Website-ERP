<x-app-layout>
    <x-slot name='header'>
        <div><p class='text-xs font-semibold uppercase tracking-[0.25em] text-blue-400'>Khusus Superadmin</p><h2 class='text-xl font-semibold'>Manajemen Role</h2></div>
    </x-slot>
    <div class='mx-auto max-w-7xl p-4 sm:p-8'>
        @if(session('success'))<div class='mb-5 rounded-xl border border-emerald-500/40 bg-emerald-950/60 p-4 text-sm text-emerald-200'>{{ session('success') }}</div>@endif
        @if($errors->any())<div class='mb-5 rounded-xl border border-red-500/40 bg-red-950/60 p-4 text-sm text-red-200'>{{ $errors->first() }}</div>@endif
        <div class='flex flex-col justify-between gap-3 sm:flex-row sm:items-end'>
            <div><p class='text-sm text-blue-300'>Atur modul yang dapat diakses setiap pengguna.</p><h3 class='text-2xl font-bold'>Daftar Pengguna</h3></div>
            <span class='rounded-full border border-blue-900 bg-slate-900 px-4 py-2 text-sm text-blue-200'>{{ $users->total() }} pengguna</span>
        </div>
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
