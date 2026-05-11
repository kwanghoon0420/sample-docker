<x-user-layout>
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row gap-6">
            @include('mypage.sidemenu')

            <section class="flex-1 min-w-0">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    {{ $slot }}
                </div>
            </section>
        </div>
    </div>
</x-user-layout>
