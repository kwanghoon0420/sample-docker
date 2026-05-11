<x-admin-layout>
    <x-slot name="title">포인트 내역</x-slot>

    <div class="w-[70%] m-auto px-4 py-6">
        {{-- 버튼라인 - 시작 --}}
        <div class="grid grid-cols-8">
            {{-- 검색라인 --}}
            <x-search :searchableFields="['user_email' => '사용자']" />
        </div>
        {{-- 버튼라인 - 끝 --}}

        {{-- 테이블 라인 - 시작 --}}
        <div class="overflow-x-auto mt-4">
            <table class="table w-full text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th class="w-1/4">사용자 이메일</th>
                        <th>변동 금액</th>
                        <th>변동 타입</th>
                        <th>수정일</th>
                        <th>생성일</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pointChangedLogs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td><a href="">{{ $log->user?->email }}</a></td>
                            <td>{{ $log->formattedChangedAmount() }}</td>
                            <td>{{ $log->typeText() }}</td>
                            <td>{{ $log->updated_at }}</td>
                            <td>{{ $log->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- 테이블 라인 - 끝 --}}

        {{-- 바텀 라인 - 시작 --}}
        <div class="row justify-content-between">
            {{ $pointChangedLogs->withQueryString()->links() }}
        </div>
        {{-- 바텀 라인 - 끝 --}}

    </div>

</x-admin-layout>