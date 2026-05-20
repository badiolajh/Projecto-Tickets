<?php
 
// MOVIDO: la lógica antes estaba en App\Http\Controllers\TecnicoController
// AHORA:  App\Http\Controllers\Tecnico\DashboardController
// Esto es para que coincida con lo que espera web.php
 
namespace App\Http\Controllers\Tecnico;
 
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
 
class DashboardController extends Controller
{
    public function index()
    {
        $tecnicoId = Auth::user()->tecnico->id_tecnico;
 
        $ticketsActivos = Ticket::with(['empleado.usuario'])
            ->where('id_tecnico', $tecnicoId)
            ->where('estado', 'en_proceso')
            ->orderByRaw("CASE prioridad WHEN 'Alta' THEN 1 WHEN 'Normal' THEN 2 ELSE 3 END")
            ->get();
 
        $ticketsCerrados = Ticket::with(['empleado.usuario'])
            ->where('id_tecnico', $tecnicoId)
            ->whereIn('estado', ['cerrado', 'resuelto'])
            ->orderBy('closed_at', 'desc')
            ->take(10)
            ->get();
 
        $stats = [
            'total'          => Ticket::where('id_tecnico', $tecnicoId)->count(),
            'en_proceso'     => Ticket::where('id_tecnico', $tecnicoId)->where('estado', 'en_proceso')->count(),
            'alta_prioridad' => Ticket::where('id_tecnico', $tecnicoId)->where('estado', 'en_proceso')->where('prioridad', 'Alta')->count(),
            'cerrados'       => Ticket::where('id_tecnico', $tecnicoId)->whereIn('estado', ['cerrado', 'resuelto'])->count(),
        ];
 
        return view('tecnico.dashboard', compact('ticketsActivos', 'ticketsCerrados', 'stats'));
    }
}