<nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.product_list') }}"
                   class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                    ADMIN PANEL
                </a>
                <span class="hidden md:inline text-xs text-gray-400 ms-2">관리자 페이지</span>
            </div>

            <div class="flex items-center space-x-2">
                @auth
                    <span class="hidden sm:inline text-sm text-gray-500 dark:text-gray-400">
                        {{ Auth::user()->name }}님
                    </span>
                    <a href="{{ route('product.list') }}"
                       class="px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                        사용자 페이지
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                            로그아웃
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="px-3 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md">
                        로그인
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
