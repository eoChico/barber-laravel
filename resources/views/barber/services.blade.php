<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 ">
        <div class="max-w-7xl gap-10 mx-auto sm:px-6 lg:p-8 mt-100 mt-100 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <h1 class="block font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Criar Serviço:</h1>

            <!-- Exibir mensagens de sucesso -->
            @if (session('success'))
                <div class="bg-green-500 text-white font-bold py-2 px-4 rounded w-48 mt-10">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Exibir erros de validação -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('barber.services.store') }}" method="POST"style="margin-top: 10px;" >
                @csrf
                <div class="mb-4">
                    <label for="nome" class="block text-white text-sm font-semibold mb-2">Nome do Serviço:</label>
                    <input type="text" name="name" class="shadow appearance-none border rounded w-64 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" value="{{ old('name') }}" required>
                </div>
                <div class="mb-4">
                    <label for="valor" class="block text-white text-sm font-semibold mb-2" >Valor do Serviço(R$):</label>
                    <input type="number" step="0.01" min="0" name="value" class="shadow appearance-none border rounded w-64 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="value" value="{{ old('value') }}" required>
                </div>
                <div class="mb-4">
                    <label for="duracao" class="block text-white text-sm font-semibold mb-2">Duração (Minutos):</label>
                    <input type="number" name="duration" class="shadow appearance-none border rounded w-5/6 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="duration" value="{{ old('duration') }}" required>
                </div>
                <button type="submit" class="mb-100 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Criar Serviço</button>
            </form>

            <!-- Move the table outside of the form -->
            <h1 class="block font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" style="margin-top: 30px;">Seus Serviços:</h1>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-100"style="margin-top: 30px;">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 m-100">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Nome
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Valor
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Duração
                            </th>
                            <th scope="col" class="px-6 py-3">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($services as $service)
                             <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                   {{ $service->name}}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $service->value}}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $service->duration}}
                                </td>
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
</x-app-layout>
