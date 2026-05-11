@php
    $menuItems = [
        ['label' => '주문 내역',   'route' => 'mypage.order.list', 'pattern' => 'mypage.order.*'],
        ['label' => '포인트 내역', 'route' => 'mypage.point.list', 'pattern' => 'mypage.point.*'],
    ];
@endphp

<aside class="w-full md:w-60 shrink-0">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 px-2">마이페이지</h2>
        <nav>
            <ul class="space-y-1">
                @foreach ($menuItems as $item)
                    @php($active = request()->routeIs($item['pattern']))
                    <li>
                        <a href="{{ route($item['route']) }}"
                           class="block px-3 py-2 rounded-md text-sm font-medium transition
                                  {{ $active
                                      ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300'
                                      : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</aside>
