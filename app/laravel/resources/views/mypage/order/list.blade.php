<x-mypage-layout>
    <div>
        <div class="mb-6">
            <h1 class="text-2xl font-bold">주문 내역</h1>
            <p class="text-sm text-gray-500">최근 주문부터 표시됩니다.</p>
        </div>

        {{-- 검색 영역 --}}
        <div class="grid grid-cols-8 mb-4">
            <x-search :searchableFields="['order_id' => '주문번호', 'product_name' => '상품명']" />
        </div>

        <div class="overflow-x-auto bg-base-100 rounded-box shadow">
            <table class="table w-full text-center">
                <thead>
                    <tr>
                        <th>주문번호</th>
                        <th>상품</th>
                        <th class="text-right">수량</th>
                        <th class="text-right">주문금액</th>
                        <th class="text-right">결제금액</th>
                        <th class="text-right">사용 포인트</th>
                        <th>상태</th>
                        <th>주문일</th>
                        <th class="whitespace-nowrap">작업</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php($product = $order->product)
                        <tr class="hover">
                            <td class="font-mono">{{ $order->order_id }}</td>
                            <td>
                                <a
                                    class="flex items-center gap-3"
                                    href="{{ $product ? route('product.view', ['id' => $product->id]) : '#' }}"
                                >
                                    <div class="avatar">
                                        <div class="mask mask-squircle w-12 h-12 bg-base-200">
                                            <img
                                                src="{{ $product?->image_url ?? 'https://placehold.co/96' }}"
                                                alt="{{ $product?->name ?? '상품' }}"
                                                class="object-cover w-full h-full"
                                            />
                                        </div>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-semibold line-clamp-1">
                                            {{ $product?->name ?? '상품 정보 없음' }}
                                        </div>
                                        <div class="text-xs text-gray-500 line-clamp-1">
                                            상품 ID: {{ $product?->id ?? '상품 정보 없음' }}
                                        </div>
                                    </div>
                                </a>
                            </td>
                            <td class="text-right">{{ number_format((int) $order->quantity) }}</td>
                            <td class="text-right">{{ $order->formattedOrderAmount() }}</td>
                            <td class="text-right font-black text-primary">{{ $order->formattedPaiedAmount() }}</td>
                            <td class="text-right">{{ $order->formattedUsedPoints() }}</td>
                            <td>
                                <span class="badge badge-ghost">{{ $order->textStatus() }}</span>
                            </td>
                            <td class="text-sm text-gray-500">{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                            <td>
                                @if (in_array($order->status, ['i', 'p'], true))
                                    <div class="flex flex-wrap gap-1 justify-center items-center">
                                        <form
                                            method="post"
                                            action="{{ route('order.cancel', $order) }}"
                                            class="inline-flex justify-center"
                                            onsubmit="return confirm('주문을 취소할까요?');"
                                        >
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-outline btn-error">취소</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-16 text-center text-gray-500 font-semibold">
                                주문한 내역이 없습니다.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $orders->withQueryString()->links() }}
        </div>
    </div>
</x-mypage-layout>
