<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Container principal centralizado -->
    <div class="flex flex-col items-center min-h-screen bg-gray-850" style="margin-top:50px;">
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-96">
            <!-- Controle de navegação do mês -->
            <div class="flex justify-between items-center mb-4">
                <button id="prevMonth" class="text-lg font-semibold text-white-500">◀</button>
                <div id="currentMonth" class="text-xl font-bold text-white"></div>
                <button id="nextMonth" class="text-lg font-semibold text-gray-600">▶</button>
            </div>

            <!-- Títulos dos dias da semana -->
            <div class="grid grid-cols-7 gap-2 text-center">
                <div class="font-bold text-white">Dom</div>
                <div class="font-bold text-white">Seg</div>
                <div class="font-bold text-white">Ter</div>
                <div class="font-bold text-white">Qua</div>
                <div class="font-bold text-white">Qui</div>
                <div class="font-bold text-white">Sex</div>
                <div class="font-bold text-white">Sab</div>
            </div>

            <!-- Dias do calendário -->
            <div id="calendarDays" class="grid grid-cols-7 gap-2 text-center mt-2">
                <!-- Dias serão gerados dinamicamente -->
            </div>
        </div>

        <!-- Seção de seleção de barbeiro e botão de registrar -->
        <div class="flex flex-wrap justify-center w-96 mt-6">
            <div class="w-full mb-4">
                <select id="barbers" name="barbers"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected value="">Selecione o Barbeiro</option>
                    @foreach ($barbers as $barber)
                    <option value="{{$barber->id}}">{{$barber->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4 w-full">
                <h1 class="font-semibold text-gray-300">Serviços:</h1>
            </div>
            <ul class="grid w-full gap-6 " id="list-services">

            </ul>
            <div class="w-full mb-4 mt-4">
                <select id="times" name="times"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected value="">Selecione o Horário</option>
                </select>
            </div>
            <div class="w-full">
                <input type="submit" id="register" value="Registrar"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
            </div>
        </div>
    </div>
    <div id="generic-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
            <h2 id="modal-title" class="text-lg font-bold mb-4 text-white">Título</h2>
            <p id="modal-message" class="mb-4 text-white font-semibold">Mensagem aqui.</p>
            <button id="close-modal" class="bg-blue-500 text-white px-4 py-2 rounded">Fechar</button>
        </div>
    </div>

    <script src="../js/calendar.js"></script>
</x-app-layout>
