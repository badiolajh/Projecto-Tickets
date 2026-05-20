<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Tecnico;
 
class DashboardController extends Controller
{
    public function index()
    {
        $ticketsSinAsignar = Ticket::with(['empleado.usuario'])
            ->whereNull('id_tecnico')
            ->where('estado', 'abierto')
            ->orderBy('created_at', 'desc')
            ->get();
 
        $ticketsEnProceso = Ticket::with(['empleado.usuario', 'tecnico.usuario'])
            ->where('estado', 'en_proceso')
            ->orderBy('updated_at', 'desc')
            ->get();
 
        $tecnicos = Tecnico::with('usuario')->get();
 
        $stats = [
            'abiertos'     => Ticket::where('estado', 'abierto')->count(),
            'en_proceso'   => Ticket::where('estado', 'en_proceso')->count(),
            'cerrados_hoy' => Ticket::where('estado', 'cerrado')
                                ->whereDate('closed_at', today())->count(),
            'tecnicos'     => Tecnico::count(),
        ];
 
        return view('admin.dashboard', compact(
            'ticketsSinAsignar', 'ticketsEnProceso', 'tecnicos', 'stats'
        ));
    }
}
 