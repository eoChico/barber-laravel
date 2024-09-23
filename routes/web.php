<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\BarberDashboardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('/schedule', [ClientDashboardController::class, 'store'])->name('schedule.store');
Route::post('/get-times/{barberId}', [ClientDashboardController::class, 'gettimes'])->name('get-times');
Route::get('/get-services/{barberId}', [ClientDashboardController::class, 'getservices'])->name('get-services');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware('auth', 'admin')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/barbers', [AdminDashboardController::class, 'barbers'])->name('admin.barbers');
    Route::post('/admin/barbers', [AdminDashboardController::class, 'store'])->name('admin.barbers.store');
});
Route::middleware('auth', 'client')->group(function () {
    Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
    Route::get('/client/schedule', [ClientDashboardController::class, 'schedule'])->name('client.schedule');
});

Route::middleware('auth', 'barber')->group(function () {
    Route::get('/barber/dashboard', [BarberDashboardController::class, 'index'])->name('barber.dashboard');
    Route::get('/barber/services', [BarberDashboardController::class, 'services'])->name('barber.services');
    Route::post('/barber/services', [BarberDashboardController::class, 'store'])->name('barber.services.store');
});
require __DIR__ . '/auth.php';
