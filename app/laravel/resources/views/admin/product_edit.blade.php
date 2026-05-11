<x-admin-layout>
    <div class="m-auto w-3/4">
        <form action="" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product ? $product->id : '' }}">

            <div><x-laboard.input.basic label1="상품명" value="{{ $product ? $product->name: '' }}" name="name"></x-laboard.input.basic></div>
            <div><x-laboard.input.basic label1="가격" name="price" value="{{ $product ? $product->price: '' }}" type="number"></x-laboard.input.basic></div>
            <div><x-laboard.input.basic label1="재고 수량" name="stock" value="{{ $product ? $product->stock: '' }}" type="number"></x-laboard.input.basic></div>
            <div><x-laboard.input.textarea label1="설명" name="description">{{ $product ? $product->description: '' }}</x-laboard.input.textarea></div>

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div role="alert" class="alert alert-error">
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            @endif

            <div class="grid grid-cols-8">
                @if($product)
                    <div class=""><x-laboard.button.basic type="button" class="btn-outline" href="{{ route('admin.product_list') }}">{{ __('BACK')}}</x-laboard.button.green></div>
                @endif
                <div class=""><x-laboard.button.basic type="button" class="btn-outline" href="{{ route('admin.product_list') }}">{{ __('LIST')}}</x-laboard.button.green></div>
                <div class="col-end-9"><x-laboard.button.basic class="btn-success w-full">저장</x-laboard.button.green></div>
            </div>
        </form>
    </div>
</x-admin-layout>