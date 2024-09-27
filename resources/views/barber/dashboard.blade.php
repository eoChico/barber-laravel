<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-800 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @forelse ($appointments as $appointment)
                        <div class="bg-gray-900 border-gray-300 rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold">{{ $appointment->client->name ?? 'Barbeiro não
                                    encontrado' }}</h3>
                            </div>
                            <div class="mt-2">
                                <p class="text-white">Data: {{
                                    \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</p>
                                <p class="text-white">Horário de entrada: {{
                                    \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</p>
                                <p class="text-white">Horário de saída: {{
                                    \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</p>
                                <p class="text-white">Serviços:</p>
                                <ul class="list-disc pl-5">
                                    @foreach ($appointment->services as $service)
                                    <li>{{ $service->name }} - R$ {{ number_format($service->value, 2, ',', '.') }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="flex justify-end mt-4">
                                <!-- Botão para abrir o modal -->
                                <button onclick="openModal('{{ $appointment->id }}')"
                                    class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600">Cancelar</button>
                            </div>
                        </div>
                        @empty
                        <div class=" text-white p-3 rounded-lg ">
                            <h1 class="font-semibold text-xl mb-4">Parece que você não nenhum agendamento
                                marcado!
                            </h1>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div id="deleteModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div
                    class="inline-block w-64 max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-800 rounded-lg shadow-xl">
                    <h3 class="text-lg font-medium text-white" id="modal-title">Confirmar Cancelamento</h3>
                    <div class="mt-2">
                        <p class="text-sm text-white">Você tem certeza que deseja cancelar este agendamento?</p>
                    </div>
                    <div class="mt-4">
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700">
                                Confirmar
                            </button>
                            <button type="button" onclick="closeModal()"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                                Cancelar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts para abrir/fechar o modal e atualizar o formulário com o ID correto -->
        <script>
            function openModal(appointmentId) {
                // Atualiza a ação do formulário com o ID do agendamento
                const form = document.getElementById('deleteForm');
                form.action = `/barber/schedule/${appointmentId}`;

                // Mostra o modal
                document.getElementById('deleteModal').classList.remove('hidden');
            }

            function closeModal() {
                // Esconde o modal
                document.getElementById('deleteModal').classList.add('hidden');
            }
        </script>
    </div>
</x-app-layout>
