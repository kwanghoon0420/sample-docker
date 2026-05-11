@php
    $menuItems = [
        ['label' => '상품 관리',   'route' => 'admin.product_list', 'pattern' => 'admin.product_*'],
        ['label' => '주문 관리',   'route' => 'admin.order_list',   'pattern' => 'admin.order_*'],
        ['label' => '사용자 관리', 'route' => 'admin.user.list',    'pattern' => 'admin.user.*'],
        ['label' => '포인트 내역', 'route' => 'admin.point.logs',   'pattern' => 'admin.point.*'],
    ];
@endphp

<aside class="w-full md:w-60 shrink-0">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 px-2">관리 메뉴</h2>
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
