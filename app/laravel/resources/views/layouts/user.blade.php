<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? '사용자 페이지지' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

            <!-- Top Navigation -->
            <nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- 로고 -->
                        <div class="flex items-center">
                            <a href="{{ route('product.list') }}" class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                                SAMPLE SHOP
                            </a>
                        </div>

                        <!-- 우측 메뉴 -->
                        <div class="flex items-center space-x-2">
                            @guest
                                <a href="{{ route('login') }}"
                                   class="px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                                    로그인
                                </a>
                                <a href="{{ route('register') }}"
                                   class="px-3 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md">
                                    회원가입
                                </a>
                            @endguest

                            @auth
                                <span class="hidden sm:inline text-sm text-gray-500 dark:text-gray-400">
                                    {{ Auth::user()->name }}님
                                </span>
                                <a href="{{ route('mypage.order.list') }}"
                                   class="px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                                    마이페이지
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                                        로그아웃
                                    </button>
                                </form>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @if(session('alert'))
            <script>
                alert("{{ session('alert') }}");
            </script>
        @endif
    </body>
</html>
