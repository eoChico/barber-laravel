<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("logado como client!") }}
                  <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-96">
        <div class="flex justify-between items-center mb-4">
            <button id="prevMonth" class="text-lg font-semibold text-gray-600">◀</button>
            <div id="currentMonth" class="text-xl font-bold text-gray-700"></div>
            <button id="nextMonth" class="text-lg font-semibold text-gray-600">▶</button>
        </div>
        <div class="grid grid-cols-7 gap-2 text-center">
            <div class="font-bold text-gray-500">Sun</div>
            <div class="font-bold text-gray-500">Mon</div>
            <div class="font-bold text-gray-500">Tue</div>
            <div class="font-bold text-gray-500">Wed</div>
            <div class="font-bold text-gray-500">Thu</div>
            <div class="font-bold text-gray-500">Fri</div>
            <div class="font-bold text-gray-500">Sat</div>
        </div>
        <div id="calendarDays" class="grid grid-cols-7 gap-2 text-center mt-2">
            <!-- Os dias serão gerados pelo JS -->
        </div>
    </div>                </div>
            </div>
        </div>
    </div>
    <script src="../js/calendar.js"></script>
</x-app-layout>
