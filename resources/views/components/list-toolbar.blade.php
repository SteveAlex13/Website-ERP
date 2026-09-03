@props(['createRoute' => null, 'createLabel' => null, 'placeholder' => 'Cari data...', 'perPageOptions' => [10, 25, 50], 'defaultPerPage' => 10])
<div class='border-b border-blue-950 p-5'>
    <form method='GET' class='flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between'>
        @if(request()->filled('type'))<input type='hidden' name='type' value='{{ request('type') }}'>@endif
        @if(request()->filled('type'))<input type='hidden' name='type' value='{{ request('type') }}'>@endif
        <label class='flex items-center gap-3 text-sm text-blue-200'>Tampilkan
            <select name='per_page' onchange='this.form.submit()' class='rounded-xl border-blue-900 bg-slate-950 text-sm text-white'>
                @foreach($perPageOptions as $size)<option value='{{ $size }}' @selected(request()->integer('per_page', $defaultPerPage) === $size)>{{ $size }}</option>@endforeach
            </select> data
        </label>
        <div class='flex flex-col gap-3 sm:flex-row'>
            <input name='search' type='search' value='{{ request('search') }}' placeholder='{{ $placeholder }}' class='w-full rounded-xl border-blue-900 bg-slate-950 text-sm text-white placeholder:text-slate-500 sm:w-64'>
            <button class='rounded-xl border border-blue-800 px-4 py-2.5 text-sm font-semibold text-blue-200 hover:bg-blue-950'>Cari</button>
            @if($createRoute)<a href='{{ $createRoute }}' class='erp-btn-primary whitespace-nowrap'>+ {{ $createLabel }}</a>@endif
        </div>
    </form>
</div>
