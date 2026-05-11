<x-admin-layout>
    <x-slot name="title">사용자 목록</x-slot>

    <div class="w-[70%] m-auto px-4 py-6">
        {{-- 버튼라인 - 시작 --}}
        <div class="grid grid-cols-8">
            {{-- 검색라인 --}}
            <x-search :searchableFields="['email' => '이메일', 'name' => '이름']" />
        </div>
        {{-- 버튼라인 - 끝 --}}

        {{-- 테이블 라인 - 시작 --}}
        <div class="overflow-x-auto mt-4">
            <table class="table w-full text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th class="w-1/4">사용자 이메일</th>
                        <th>이름</th>
                        <th>포인트</th>
                        <th>수정일</th>
                        <th>생성일</th>
                        <th>기능</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td><a href="">{{ $user->email }}</a></td>
                            <td><a href="">{{ $user->name }}</a></td>
                            <td>{{ $user->point ? number_format($user->point->remain_amount, 0) : '0' }}</td>
                            <td>{{ $user->updated_at }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ route('admin.user.earn_point', ['user_id' => $user->id]) }}">포인트 적립</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- 테이블 라인 - 끝 --}}

        {{-- 바텀 라인 - 시작 --}}
        <div class="row justify-content-between">
            {{ $users->withQueryString()->links() }}
        </div>
        {{-- 바텀 라인 - 끝 --}}

    </div>

</x-admin-layout>