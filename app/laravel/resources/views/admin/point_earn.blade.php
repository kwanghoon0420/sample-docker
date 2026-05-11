<x-admin-layout>
    <div class="m-auto max-w-md ">
        <form action="" method="POST">
            @csrf
            <div><x-laboard.input.basic label1="사용자명" value="{{ $user ? $user->name: '' }}" name="name" disabled></x-laboard.input.basic></div>
            <div><x-laboard.input.basic label1="사용자 이메일" value="{{ $user ? $user->email: '' }}" name="email" disabled></x-laboard.input.basic></div>
            <div><x-laboard.input.basic label1="포인트" name="amount" value="{{ $user ? $user->amount : '' }}" type="number"></x-laboard.input.basic></div>
            <div><x-laboard.input.basic label1="유효기간" name="expire_days" value="{{ $user ? $user->expire_days : '' }}" type="number"></x-laboard.input.basic></div>

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div role="alert" class="alert alert-error">
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            @endif

            <div class="flex justify-between">
                <div><x-laboard.button.basic class="btn-outline w-full" type="button" href="{{ route('admin.user.list') }}">목록</x-laboard.button.green></div>
                <div><x-laboard.button.basic class="btn-success w-full" type="submit">저장</x-laboard.button.green></div>
            </div>
        </form>
    </div>
</x-admin-layout>