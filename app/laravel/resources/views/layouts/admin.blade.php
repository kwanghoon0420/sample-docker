<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Admin Panel' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

            {{-- 상단 네비게이션 --}}
            @include('admin.topbar')

            {{-- 페이지 헤딩 (옵션) --}}
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="px-4 sm:px-6 lg:px-8 py-6">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- 본문: 좌측 사이드바 + 우측 컨텐츠 --}}
            <div class="px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row gap-6">
                    @include('admin.sidemenu')

                    <main class="flex-1 min-w-0">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>

        @if(session('alert'))
            <script>
                alert("{{ session('alert') }}");
            </script>
        @endif
    </body>
</html>
