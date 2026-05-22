<x-user-layout>
    <div class="max-w-4xl mx-auto py-12 px-4">
        <div class="flex flex-col md:flex-row gap-10">
            
            <!-- 1. 이미지 영역 -->
            <div class="w-full md:w-1/2">
                <div class="aspect-square rounded-2xl overflow-hidden border border-base-200">
                    <img src="{{ $product->image_url ?? 'https://placehold.co/600' }}" 
                         class="w-full h-full object-cover" 
                         alt="{{ $product->name }}" />
                </div>
            </div>

            <!-- 2. 구매 제어 영역 -->
            <div class="w-full md:w-1/2 flex flex-col justify-center" x-data="{ quantity: 1, userPoint: {{ $userPoints }}, price: {{ $product->price }} }">
                
                <!-- 상품명 -->
                <h1 class="text-3xl font-black text-gray-900 mb-6">{{ $product->name }}</h1>

                <!-- 포인트 정보 섹션 -->
                <div class="bg-base-200 p-5 rounded-2xl mb-8 space-y-3">
                    <div class="flex justify-between items-center pt-3 border-t border-base-300">
                        <span class="text-gray-500 font-medium">상품 가격</span>
                        <span class="text-xl font-black text-primary">{{ $product->formatted_price }}</span>
                    </div>
                </div>

                <!-- 수량 선택 및 구매 -->
                <form action="{{ route('order.order_product') }}" method="POST" class="space-y-6" onsubmit="return confirm('구매하시겠습니까?')">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-gray-700">수량 설정</span>
                        <div class="join border border-base-300 bg-white">
                            <button type="button" @click="if(quantity > 1) quantity--" class="btn btn-ghost join-item btn-sm text-lg">-</button>
                            <input type="number" name="quantity" x-model="quantity" readonly
                                   class="join-item w-12 text-center font-bold text-sm border-none focus:outline-none" />
                            <button type="button" @click="quantity++" class="btn btn-ghost join-item btn-sm text-lg">+</button>
                        </div>
                    </div>

                    <!-- 잔여 포인트, 총 결제 예정 금액, 결제 후 잔여 포인트를 3줄로 표시  -->
                    <div class="flex flex-col gap-2">
                        <div class="text-right">
                            <span class="text-sm font-medium text-gray-500">잔여 포인트: </span>
                            <span class="text-2xl font-black" x-text="userPoint.toLocaleString() + ' P'"></span>
                        </div>
                        <div class="text-right">
                            <input type="hidden" name="used_points" x-bind:value="quantity * price">
                            <span class="text-sm font-medium text-gray-500">총 결제 예정 포인트: </span>
                            <span class="text-2xl font-black" x-text="quantity * price + ' P'"></span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-medium text-gray-500">결제 후 잔여 포인트: </span>
                            <span class="text-2xl font-black" x-text="userPoint - (quantity * price) + ' P'"></span>
                        </div>
                    </div>

                    <!-- 구매 버튼 -->
                    @if($product->status === 'a' && $product->stock > 0)
                        <button type="submit" 
                                class="btn btn-primary btn-lg w-full text-lg shadow-xl shadow-primary/20"
                                :disabled="userPoint < (quantity * price)">
                            <span x-show="userPoint >= (quantity * price)">구매하기</span>
                            <span x-show="userPoint < (quantity * price)">포인트가 부족합니다</span>
                        </button>
                    @else
                        <button type="button" class="btn btn-primary btn-lg w-full text-lg shadow-xl shadow-primary/20" disabled>
                            상품 준비중
                        </button>
                    @endif
                </form>
            </div>
        </div>

        <!-- 3. 상품 설명 -->
        <div class="mt-16 border-t border-base-200 pt-10">
            <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                상품 상세 설명
            </h2>
            <div class="prose max-w-none text-gray-600 bg-white p-6 rounded-xl border border-base-100 shadow-sm">
                {!! $product->description !!}
            </div>
        </div>
    </div>
</x-user-layout>