<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Barbeiros') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-10 lg:px-8">
            <div class="mt-100 w-full relative overflow-x-auto shadow-md sm: rounded-md">
                <table class="mt-100 w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-md">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-semibold text-lg ">
                                Nome
                            </th>
                            <th scope="col" class="px-6 py-3 font-semibold text-lg ">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3 font-semibold text-lg ">
                                Email Verificado
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($barbers as $barber)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                              <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white ">
                                {{$barber->name}}
                              </th>
                            <td class="px-6 py-4 text-base whitespace-nowrap">{{$barber->email}}</td>
                            <td class="px-6 py-4 text-base text-right">{{$barber->email_verified_at}}</td>
                            <td class="px-6 py-4 text-right">
                                 <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                     </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:p-8 mt-100 mt-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
         <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Registre um Barbeiro:</h1>
         @if ($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.barbers.store') }}" method="POST" class="text-white p-6 rounded-lg shadow-md">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-white-700 text-sm font-semibold mb-2">Nome:</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-white-700 text-sm font-semibold mb-2">E-mail:</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-white-700 text-sm font-semibold mb-2">Senha:</label>
                <input type="password" name="password" id="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="block text-white-700 text-sm font-semibold mb-2">Confirmar Senha:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="start_time" class="block text-white-700 text-sm font-semibold mb-2">Horário de Entrada:</label>
                <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required class="shadow appearance-none border rounded  py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="end_time" class="block text-white-700 text-sm font-semibold mb-2">Horário de Saída:</label>
                <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required class="shadow appearance-none border rounded  py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label class="block text-white-700 text-sm font-semibold mb-2">Dias que Trabalha:</label>
                <div class="flex flex-wrap gap-4">
                    @foreach(['monday' => 'Segunda-feira', 'tuesday' => 'Terça-feira', 'wednesday' => 'Quarta-feira', 'thursday' => 'Quinta-feira', 'friday' => 'Sexta-feira', 'saturday' => 'Sábado', 'sunday' => 'Domingo'] as $day => $label)
                        <div class="flex items-center">
                            <input type="checkbox" name="working_days[]" value="{{ $day }}" id="{{ $day }}" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="{{ $day }}" class="ml-2 text-sm font-medium text-gray-300">{{ $label }}</label>
                        </div>
                    @endforeach
        </div>
            </div>
            <input type="submit" value="Registrar" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            </form>
    </div>
</x-app-layout>
