<x-admin-layout>
    <x-slot name="title">주문 목록</x-slot>

    <div class="w-[70%] m-auto px-4 py-6">
        {{-- 버튼라인 - 시작 --}}
        <div class="grid grid-cols-8">
            {{-- 검색라인 --}}
            <x-search :searchableFields="['user_email' => '사용자', 'order_id' => '주문번호', 'product_name' => '상품명']" />
        </div>
        {{-- 버튼라인 - 끝 --}}

        {{-- 테이블 라인 - 시작 --}}
        <div class="overflow-x-auto mt-4">
            <table class="table w-full text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>주문번호</th>
                        <th>사용자</th>
                        <th>상품</th>
                        <th>수량</th>
                        <th>주문금액</th>
                        <th>결제금액</th>
                        <th>사용 포인트</th>
                        <th>상태</th>
                        <th>주문일</th>
                        <th class="whitespace-nowrap">작업</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td><a href="">{{ $order->order_id }}</a></td>
                            <td><a href="">{{ $order->user?->email }}</a></td>
                            <td>{{ $order->product?->name ?? '상품 정보 없음' }}</td>
                            <td>{{ number_format((int) $order->quantity) }}</td>
                            <td>{{ $order->formattedOrderAmount() }}</td>
                            <td>{{ $order->formattedPaiedAmount() }}</td>
                            <td>{{ $order->formattedUsedPoints() }}</td>
                            <td>{{ $order->textStatus() }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>
                                @if (in_array($order->status, ['i', 'p'], true))
                                    <div class="flex flex-wrap gap-1 justify-center items-center">
                                        @if ($order->status === 'p')
                                            <form
                                                method="post"
                                                action="{{ route('admin.orders.confirm', $order) }}"
                                                class="inline-flex justify-center"
                                                onsubmit="return confirm('주문을 확정할까요?');"
                                            >
                                                @csrf
                                                <button type="submit" class="btn btn-xs btn-primary">확정</button>
                                            </form>
                                        @endif
                                        <form
                                            method="post"
                                            action="{{ route('admin.orders.cancel', $order) }}"
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
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- 테이블 라인 - 끝 --}}

        {{-- 바텀 라인 - 시작 --}}
        <div class="row justify-content-between">
            {{ $orders->withQueryString()->links() }}
        </div>
        {{-- 바텀 라인 - 끝 --}}

    </div>

</x-admin-layout>
