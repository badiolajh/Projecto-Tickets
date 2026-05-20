<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Tecnico\DashboardController as TecnicoDashboard;
use App\Http\Controllers\Tecnico\TicketController as TecnicoTicketController;
use App\Http\Controllers\Empleado\DashboardController as EmpleadoDashboard;
use App\Http\Controllers\Empleado\TicketController as EmpleadoTicketController;
 // Ruta para cambiar contraseña desde el modal de perfil (todos los roles)
Route::post('/perfil/password', [App\Http\Controllers\PerfilController::class, 'cambiarPassword'])
    ->name('perfil.password')
    ->middleware('auth');
/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('login'));
 
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout',[LoginController::class, 'logout'])->name('logout')->middleware('auth');
 
/*
|--------------------------------------------------------------------------
| Administrador
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'rol:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
 
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
 
        Route::get('/tickets',                    [AdminTicketController::class, 'index'])->name('tickets');
        Route::get('/tickets/{ticket}',           [AdminTicketController::class, 'show'])->name('tickets.show');
        Route::patch('/tickets/{ticket}/asignar', [AdminTicketController::class, 'asignar'])->name('tickets.asignar');
 
        Route::get('/users',             [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create',      [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users',            [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::patch('/users/{user}',    [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',   [AdminUserController::class, 'destroy'])->name('users.destroy');
    });
 
/*
|--------------------------------------------------------------------------
| Técnico
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'rol:tecnico'])
    ->prefix('tecnico')
    ->name('tecnico.')
    ->group(function () {
 
        Route::get('/dashboard', [TecnicoDashboard::class, 'index'])->name('dashboard');
 
        Route::get('/tickets',                    [TecnicoTicketController::class, 'index'])->name('tickets');
        Route::get('/tickets/finalizados',        [TecnicoTicketController::class, 'finalizados'])->name('tickets.finalizados');
        Route::get('/tickets/{ticket}',           [TecnicoTicketController::class, 'show'])->name('tickets.show');
        Route::patch('/tickets/{ticket}/cerrar',  [TecnicoTicketController::class, 'cerrar'])->name('tickets.cerrar');
        Route::patch('/tickets/{ticket}/estado',  [TecnicoTicketController::class, 'cambiarEstado'])->name('tickets.estado');
        Route::post('/tickets/{ticket}/comentar', [TecnicoTicketController::class, 'comentar'])->name('tickets.comentar');
    });
 
/*
|--------------------------------------------------------------------------
| Empleado
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'rol:empleado'])
    ->prefix('empleado')
    ->name('empleado.')
    ->group(function () {
 
        Route::get('/dashboard', [EmpleadoDashboard::class, 'index'])->name('dashboard');
 
        Route::get('/tickets',       [EmpleadoTicketController::class, 'index'])->name('tickets');
        Route::get('/tickets/crear', [EmpleadoTicketController::class, 'create'])->name('tickets.crear');
        Route::post('/tickets',      [EmpleadoTicketController::class, 'store'])->name('tickets.store');
    });