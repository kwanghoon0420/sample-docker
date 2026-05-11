<x-admin-layout>
    <div class="m-auto w-3/4">
        <form action="" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product ? $product->id : '' }}">

            <div class="mb-4">
                <label class="label" for="name"><span class="label-text">상품명</span></label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ $product ? $product->name : '' }}"
                    class="input input-bordered w-full"
                />
            </div>
            <div class="mb-4">
                <label class="label" for="price"><span class="label-text">가격</span></label>
                <input
                    id="price"
                    type="number"
                    name="price"
                    value="{{ $product ? $product->price : '' }}"
                    class="input input-bordered w-full"
                />
            </div>
            <div class="mb-4">
                <label class="label" for="stock"><span class="label-text">재고 수량</span></label>
                <input
                    id="stock"
                    type="number"
                    name="stock"
                    value="{{ $product ? $product->stock : '' }}"
                    class="input input-bordered w-full"
                />
            </div>
            <div class="mb-4">
                <label class="label" for="description"><span class="label-text">설명</span></label>
                <textarea
                    id="description"
                    name="description"
                    class="textarea textarea-bordered w-full h-96"
                >{{ $product ? $product->description : '' }}</textarea>
            </div>

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div role="alert" class="alert alert-error">
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            @endif

            <div class="grid grid-cols-8 gap-2 items-center">
                @if ($product)
                    <div>
                        <a href="{{ route('admin.product_list') }}" class="btn btn-outline">{{ __('BACK') }}</a>
                    </div>
                @endif
                <div>
                    <a href="{{ route('admin.product_list') }}" class="btn btn-outline">{{ __('LIST') }}</a>
                </div>
                <div class="col-end-9">
                    <button type="submit" class="btn btn-success w-full">저장</button>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
