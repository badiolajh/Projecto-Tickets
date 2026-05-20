<?php

namespace App\Http\Controllers\Empleado;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $empleadoId = Auth::user()->empleado->id_empleado;

        // Carga todos los tickets pero el dashboard los separa en activos y recientes resueltos
        $tickets = Ticket::with(['tecnico.usuario', 'historial'])
            ->where('id_empleado', $empleadoId)
            ->orderBy('created_at', 'desc')
            ->paginate(50); // suficientes para mostrar activos + últimos 3 resueltos

        $stats = [
            'abiertos'   => Ticket::where('id_empleado', $empleadoId)->where('estado', 'abierto')->count(),
            'en_proceso' => Ticket::where('id_empleado', $empleadoId)->where('estado', 'en_proceso')->count(),
            'cerrados'   => Ticket::where('id_empleado', $empleadoId)->whereIn('estado', ['cerrado', 'resuelto'])->count(),
        ];

        return view('empleado.dashboard', compact('tickets', 'stats'));
    }
}