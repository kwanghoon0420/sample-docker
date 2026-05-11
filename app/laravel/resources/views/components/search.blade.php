@props(['searchableFields' => []])

<div class="grid col-span-4">
    <form class="grid grid-cols-6">
        <select name="search_by" class="select select-bordered rounded-r-none col-span-1">
            <option>전체</option>
            @foreach ($searchableFields as $field => $label)
                <option value="{{ $field }}" @selected(request()->input('search_by') == $field)>{{ $label }}</option>
            @endforeach
        </select>
        <input type="text" name="search" class="input input-ghost input-bordered rounded-none col-span-4" value="{{ request()->input('search') ?? '' }}">
        <button class="rounded-l-none btn btn-primary col-span-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-6 h-6 stroke-current">             
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>             
            </svg>
        </button>
    </form>
</div>