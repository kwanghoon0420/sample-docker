<x-mypage-layout>
    <div>
        <div class="mb-6 flex flex-col md:flex-row md:items-end md:justify-between gap-2">
            <div>
                <h1 class="text-2xl font-bold">포인트 내역</h1>
                <p class="text-sm text-gray-500">충전·적립·사용·환불·만기 내역이 표시됩니다.</p>
            </div>
            <div class="text-right">
                <span class="text-sm font-medium text-gray-500">잔여 포인트</span>
                <span class="ms-1 text-2xl font-black text-primary">
                    {{ number_format((int) $remainPoints) }} P
                </span>
            </div>
        </div>

        {{-- 검색 영역 --}}
        <div class="grid grid-cols-8 mb-4">
            <x-search :searchableFields="['reference_id' => '관련 번호', 'type' => '변동 타입']" />
        </div>

        <div class="overflow-x-auto bg-base-100 rounded-box shadow">
            <table class="table w-full text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>변동 타입</th>
                        <th class="text-right">변동 금액</th>
                        <th>관련 번호</th>
                        <th>변동일</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        @php
                            $isMinus = in_array($log->type, ['u', 'x'], true);
                            $sign = $isMinus ? '-' : '+';
                            $amountClass = $isMinus ? 'text-red-500' : 'text-emerald-600';
                        @endphp
                        <tr class="hover">
                            <td class="font-mono">{{ $log->id }}</td>
                            <td>
                                <span class="badge badge-ghost">{{ $log->typeText() }}</span>
                            </td>
                            <td class="text-right font-black {{ $amountClass }}">
                                {{ $sign }}{{ $log->formattedChangedAmount() }} P
                            </td>
                            <td class="font-mono text-sm text-gray-600">
                                {{ $log->reference_id }}
                            </td>
                            <td class="text-sm text-gray-500">
                                {{ $log->created_at?->format('Y-m-d H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center text-gray-500 font-semibold">
                                포인트 내역이 없습니다.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>
</x-mypage-layout>
