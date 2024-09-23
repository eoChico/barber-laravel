<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\ConfirmablePasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Barber;
use App\Models\Service;

class BarberDashboardController extends Controller
{
    public function index()
    {
        // Adicione a lógica para obter dados necessários para o dashboard do cliente

        return view('barber.dashboard');
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
