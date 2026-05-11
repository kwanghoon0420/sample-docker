<x-admin-layout>
    <div class="w-full max-w-lg mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">포인트 적립</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                선택한 사용자에게 적립할 포인트와 유효기간(일)을 입력하세요.
            </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <form action="{{ route('admin.user.earn_point') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}" />

                <div>
                    <label class="label py-0" for="name"><span class="label-text font-medium">사용자명</span></label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ $user->name }}"
                        class="input input-bordered w-full bg-base-200/80 dark:bg-base-300/50"
                        readonly
                        tabindex="-1"
                    />
                </div>

                <div>
                    <label class="label py-0" for="email"><span class="label-text font-medium">사용자 이메일</span></label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ $user->email }}"
                        class="input input-bordered w-full bg-base-200/80 dark:bg-base-300/50"
                        readonly
                        tabindex="-1"
                    />
                </div>

                <div>
                    <label class="label py-0" for="amount"><span class="label-text font-medium">적립 포인트</span></label>
                    <input
                        id="amount"
                        type="number"
                        name="amount"
                        value="{{ old('amount') }}"
                        min="0"
                        step="1"
                        placeholder="예: 10000"
                        class="input input-bordered w-full"
                        required
                    />
                </div>

                <div>
                    <label class="label py-0" for="expire_days"><span class="label-text font-medium">유효기간</span></label>
                    <input
                        id="expire_days"
                        type="number"
                        name="expire_days"
                        value="{{ old('expire_days') }}"
                        min="0"
                        step="1"
                        placeholder="일 단위 (예: 365)"
                        class="input input-bordered w-full"
                        required
                    />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">적립일 기준 며칠 후까지 사용 가능한지 입력합니다.</p>
                </div>

                @if ($errors->any())
                    <div class="space-y-2">
                        @foreach ($errors->all() as $error)
                            <div role="alert" class="alert alert-error text-sm py-2">
                                <span>{{ $error }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <a href="{{ route('admin.user.list') }}" class="btn btn-outline btn-md">목록</a>
                    <button type="submit" class="btn btn-success btn-md">저장</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
