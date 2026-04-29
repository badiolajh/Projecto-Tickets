<?php
//Este archivo es el archivo de rutas principal
//Aqui se conecta todo el sistema: login, middleware de roles y dashboards

//Aqui se importa el controlador Login y se importa Route para definir rutas
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminTicketController;
use App\Http\Controllers\TecnicoController;
use Illuminate\Support\Facades\Route;

// Rutas de autenticaciòn

//muestra formulario
Route::get( '/login',  [LoginController::class, 'showLogin'])->name('login');

//procesa login
Route::post('/login',  [LoginController::class, 'login']);
//Solo los usuarios autenticados pueden hacer logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

//Ruta raiz
//Si alguien entra a "/" lo manda directo al login
Route::get('/', fn() => redirect()->route('login'));

// Rutas empleado

//debe estar logueado (auth)
//debe ser empleado (rol:empleado)
//todas las rutas empiezan con /empleado
//los nombres empiezan con empleado
Route::middleware(['auth', 'rol:empleado'])->prefix('empleado')->name('empleado.')->group(function () {

    //Dashboard empleado
    Route::get('/dashboard', fn() => view('empleado.dashboard'))->name('dashboard');

    // ── Tickets ───────────────────────────────────────────────────
    Route::get('/tickets',        [TicketController::class, 'index'])->name('tickets');
    Route::get('/tickets/crear',  [TicketController::class, 'create'])->name('tickets.crear');
    Route::post('/tickets',       [TicketController::class, 'store'])->name('tickets.store');
    });

//Rutas de Admin
//solo los administradores pueden entrar
Route::middleware(['auth', 'rol:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',                  fn() => view('admin.dashboard'))->name('dashboard');
    Route::get('/tickets',                    [AdminTicketController::class, 'index'])->name('tickets');
    Route::patch('/tickets/{id}/asignar',     [AdminTicketController::class, 'asignar'])->name('tickets.asignar');
});

// Rutas de tecnico
//solo los tecnicos pueden acceder
Route::middleware(['auth', 'rol:tecnico'])->prefix('tecnico')->name('tecnico.')->group(function () {
    Route::get('/dashboard',             fn() => view('tecnico.dashboard'))->name('dashboard');
    Route::get('/tickets',               [TecnicoController::class, 'index'])->name('tickets');
    Route::get('/tickets/finalizados',   [TecnicoController::class, 'finalizados'])->name('tickets.finalizados');
    Route::patch('/tickets/{id}/estado', [TecnicoController::class, 'cambiarEstado'])->name('tickets.estado');
});
