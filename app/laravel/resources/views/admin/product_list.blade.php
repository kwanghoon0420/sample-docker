<x-admin-layout>
    <x-slot name="title">상품 목록</x-slot>

    <div class="w-[70%] m-auto px-4 py-6">
        {{-- 버튼라인 - 시작 --}}
        <div class="grid grid-cols-10">
            {{-- 글쓰기, 삭제 --}}
            <div class="col-span-4">
                <a class="btn btn-primary" href="{{ route('admin.product_store') }}">상품 등록</a>
            </div>

            {{-- 검색라인 --}}
            <div class="grid col-span-2 col-end-13">
                <form class="grid grid-cols-4">
                    <select name="searchBy" class="select select-bordered rounded-r-none col-span-1">
                        <option>{{ __('all') }}</option>
                        {{-- <option value="title" @selected($searchBy == 'title')>{{ __('title') }}</option>
                        <option value="content"  @selected($searchBy == 'content')>{{ __('content') }}</option> --}}
                    </select>
                    <input type="text" name="search" class="input input-ghost input-bordered rounded-none col-span-2" value="{{ $search ?? '' }}">
                    <button class="rounded-l-none btn btn-primary col-span-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-6 h-6 stroke-current">             
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>             
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        {{-- 버튼라인 - 끝 --}}

        {{-- 테이블 라인 - 시작 --}}
        <div class="overflow-x-auto mt-4">
            <table class="table w-full text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th class="w-1/4">상품명</th>
                        <th>가격</th>
                        <th>재고 수량</th>
                        <th>수정일</th>
                        <th>생성일</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productList as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td><a href="">{{ $product->name }}</a></td>
                            <td>{{ $product->formatted_price }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->updated_at }}</td>
                            <td>{{ $product->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- 테이블 라인 - 끝 --}}

        {{-- 바텀 라인 - 시작 --}}
        <div class="row justify-content-between">
            {{ $productList->withQueryString()->links() }}
        </div>
        {{-- 바텀 라인 - 끝 --}}

    </div>

</x-admin-layout>