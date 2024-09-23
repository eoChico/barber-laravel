<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Availability;
use App\Models\User;
use App\Models\Barber;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientDashboardController extends Controller
{
    public function index()
    {
        // Adicione a lógica para obter dados necessários para o dashboard do cliente
        return view('client.dashboard');
    }
    public function schedule()
    {
        $barbers = User::where('type', 'barber')->get();

        return view('client.schedule', compact('barbers'));
    }

    public function store(Request $request)
    {
        // Validar os dados recebidos
        $validatedData = $request->validate([
            'barber_id' => 'required',
            'appointment_date' => 'required|date',
            'start_time' => 'required',
            'services' => 'required|array', // Validando o array de serviços
            // Cada item deve ser um inteiro e existir na tabela services
        ]);

        try {
            $id = Auth::id();
            $barber = Barber::where('user_id', $validatedData['barber_id'])->first();
            // Busca as durações dos serviços selecionados
            $serviceDurations = Service::whereIn('id', $validatedData['services'])->sum('duration'); // Soma todas as durações

            // Converter start_time (exemplo: "14:30") em uma instância de Carbon para manipular o horário
            $startTime = \Carbon\Carbon::createFromFormat('H:i', $validatedData['start_time']);

            // Adicionar a duração total ao start_time para calcular o end_time
            $endTime = $startTime->copy()->addMinutes($serviceDurations);
            // Criar o agendamento diretamente
            $appointment = Appointment::create([
                'barber_id' => $barber->id,
                'appointment_date' => $validatedData['appointment_date'],
                'client_id' => $id,
                'start_time' => $validatedData['start_time'],
                'end_time' => $endTime->format('H:i'),
            ]);

            // Associar os serviços ao agendamento usando sync()
            $appointment->services()->sync($validatedData['services']);

            return response()->json([
                'message' => 'Appointment created successfully',
                'appointment' => $appointment,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create appointment',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function getServices($userId)
    {
        $barberId = Barber::where('user_id', $userId)->first();

        // Busca a disponibilidade do barbeiro
        $available_time = Availability::where('barber_id', $barberId->id)->first();

        $services = Service::where('barber_id', $barberId->id)->get();


        // Retorna a lista de horários em formato JSON
        return response()->json([

            'availability' => [
                'monday' => $available_time->monday,
                'tuesday' => $available_time->tuesday,
                'wednesday' => $available_time->wednesday,
                'thursday' => $available_time->thursday,
                'friday' => $available_time->friday,
                'saturday' => $available_time->saturday,
                'sunday' => $available_time->sunday,
            ],
            'services' => $services
        ]);
    }


    public function getTimes($userId)
    {
        $barberId = Barber::where('user_id', $userId)->first();
        $date = request()->get('date');
        $services = request()->get('services', []); // Receber os serviços selecionados

        // Busca a disponibilidade do barbeiro
        $available_time = Availability::where('barber_id', $barberId->id)->first();

        if ($available_time) {
            $times = [];
            $intervalMinutes = 5;

            // Converte o horário de início e fim para objetos Carbon
            $start = \Carbon\Carbon::parse($available_time->start_time);
            $end = \Carbon\Carbon::parse($available_time->end_time);

            // Busca os agendamentos do barbeiro para a data específica
            $appointments = Appointment::where('barber_id', $barberId->id)
                ->where('appointment_date', $date) // Filtra pela data
                ->get();

            // Obter a duração total dos serviços selecionados
            $totalDuration = 0;
            foreach ($services as $serviceId) {
                $service = Service::find($serviceId);
                if ($service) {
                    $totalDuration += $service->duration; // Somar a duração dos serviços
                }
            }

            // Calcular o horário final baseado na duração total
            $finalEnd = $end->clone()->subMinutes($totalDuration);

            // Gerar os horários em intervalos de 5 minutos
            while ($start <= $finalEnd) {
                // Verifica se o horário atual está em conflito com algum agendamento
                $conflict = $appointments->contains(function ($appointment) use ($start, $totalDuration) {
                    $endTime = $start->clone()->addMinutes($totalDuration); // Calcular o horário de fim
                    return (
                        $start->between($appointment->start_time, $appointment->end_time) ||
                        $endTime->between($appointment->start_time, $appointment->end_time) ||
                        ($start->lessThan($appointment->start_time) && $endTime->greaterThan($appointment->end_time))
                    );
                });

                if (!$conflict) {
                    $times[] = $start->format('H:i');
                }

                $start->addMinutes($intervalMinutes);
            }

            // Retorna a lista de horários em formato JSON
            return response()->json([
                'horarios' => $times,
            ]);
        } else {
            // Se não houver disponibilidade encontrada, retorna uma mensagem apropriada
            return response()->json([
                'error' => 'Nenhuma disponibilidade encontrada para o barbeiro.'
            ], 404); // Código de status 404 para "não encontrado"
        }
    }
}
