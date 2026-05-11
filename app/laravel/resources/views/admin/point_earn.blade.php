<x-admin-layout>
    <div class="m-auto max-w-md ">
        <form action="" method="POST">
            @csrf
            <div class="mb-4">
                <label class="label" for="name"><span class="label-text">사용자명</span></label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ $user ? $user->name : '' }}"
                    class="input input-bordered w-full"
                    disabled
                />
            </div>
            <div class="mb-4">
                <label class="label" for="email"><span class="label-text">사용자 이메일</span></label>
                <input
                    id="email"
                    type="text"
                    name="email"
                    value="{{ $user ? $user->email : '' }}"
                    class="input input-bordered w-full"
                    disabled
                />
            </div>
            <div class="mb-4">
                <label class="label" for="amount"><span class="label-text">포인트</span></label>
                <input
                    id="amount"
                    type="number"
                    name="amount"
                    value="{{ $user ? $user->amount : '' }}"
                    class="input input-bordered w-full"
                />
            </div>
            <div class="mb-4">
                <label class="label" for="expire_days"><span class="label-text">유효기간</span></label>
                <input
                    id="expire_days"
                    type="number"
                    name="expire_days"
                    value="{{ $user ? $user->expire_days : '' }}"
                    class="input input-bordered w-full"
                />
            </div>

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div role="alert" class="alert alert-error">
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            @endif

            <div class="flex justify-between gap-2">
                <a href="{{ route('admin.user.list') }}" class="btn btn-outline w-full">목록</a>
                <button type="submit" class="btn btn-success w-full">저장</button>
            </div>
        </form>
    </div>
</x-admin-layout>
