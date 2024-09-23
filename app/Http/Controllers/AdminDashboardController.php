<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\User;
use App\Models\Barber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Adicione a lógica para obter dados necessários para o dashboard do admin
        return view('admin.dashboard');
    }
    public function barbers()
    {
        $barbers = User::where('type', 'barber')->get();

        return view('admin.barbers', compact('barbers'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'working_days' => 'array|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Cria o usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => 'barber'
        ]);

        $barber = Barber::create([
            'user_id' => $user->id
        ]);
        Availability::create([
            'barber_id' => $barber->id,
            'monday' => in_array('monday', $request->working_days),
            'tuesday' => in_array('tuesday', $request->working_days),
            'wednesday' => in_array('wednesday', $request->working_days),
            'thursday' => in_array('thursday', $request->working_days),
            'friday' => in_array('friday', $request->working_days),
            'saturday' => in_array('saturday', $request->working_days),
            'sunday' => in_array('sunday', $request->working_days),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time')
        ]);
        // Redireciona ou faz outra ação
        return redirect()->route('admin.barbers')->with('success', 'Barbeiro criado com sucesso!');
    }
}
