<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Barber;
use App\Models\Service;

class BarberDashboardController extends Controller
{

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        return redirect()->back()->with('success', 'Agendamento deletado com sucesso!');
    }
    public function index()
    {
        $id = Auth::id();
        $barber = Barber::where('user_id', $id)->first();
        $barberid = $barber ? $barber->id : null;
        $appointments = Appointment::with(['barber.user', 'services', 'client'])->where('appointment_date', '>=', now())->where('barber_id', $barberid)->get();

        return view('barber.dashboard', compact('appointments'));
    }

    public function services()
    {
        $userId = Auth::id();

        // Buscar o barbeiro com base no ID do usuário
        $barber = Barber::where('user_id', $userId)->first();

        $services = Service::where('barber_id', $barber->id)->get();

        return view('barber.services', compact('services'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|numeric',
            'duration' => 'required|integer',
        ]);
        $userId = Auth::id();

        // Buscar o barbeiro com base no ID do usuário
        $barber = Barber::where('user_id', $userId)->first();
        Service::create([
            'barber_id' => $barber->id,
            'duration' => $request->duration,
            'value' => $request->value,
            'name' => $request->name
        ]);

        // Redireciona após criar o serviço
        return redirect()->route('barber.services')->with('success', 'Serviço criado com sucesso!');
    }
}
