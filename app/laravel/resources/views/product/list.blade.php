<x-user-layout>
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">전체 상품</h1>
            <p class="text-sm text-gray-500">원하는 상품을 골라보세요.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @forelse($productList as $product)
                <div class="card card-compact bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300 group cursor-pointer">
                    <a href="{{ route('product.view', ['id' => $product->id]) }}">
                        <!-- 상품 이미지 영역 -->
                        <figure class="relative aspect-square overflow-hidden bg-gray-100">
                            <img src="{{ $product->image_url ?? 'https://placehold.co/400' }}"
                                 alt="{{ $product->name }}"
                                 class="group-hover:scale-110 transition-transform duration-500 object-cover w-full h-full" />

                            @if($product->status === 'OUT_OF_STOCK')
                                <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                                    <span class="badge badge-error badge-lg font-bold text-white">품절</span>
                                </div>
                            @endif
                        </figure>

                        <!-- 상품 정보 영역 -->
                        <div class="card-body">
                            <div class="flex justify-between items-start">
                                <h2 class="card-title text-gray-800 text-lg line-clamp-1">{{ $product->name }}</h2>
                            </div>

                            <p class="text-gray-500 text-sm line-clamp-2 h-10">{{ $product->summary }}</p>

                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-2xl font-black text-primary">
                                    {{ $product->formatted_price }}
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-base-200 rounded-box">
                    <div class="text-5xl mb-4">📦</div>
                    <p class="text-xl font-bold text-gray-500">등록된 상품이 없습니다.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-user-layout>
