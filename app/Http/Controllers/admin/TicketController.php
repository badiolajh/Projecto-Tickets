<?php
 
// MOVIDO: antes estaba en App\Http\Controllers\AdminTicketController
// AHORA:  App\Http\Controllers\Admin\TicketController
// Esto es para que coincida con lo que espera web.php y tener mejor orden
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Tecnico;
use App\Models\TicketHistorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 
class TicketController extends Controller
{
    // Ver todos los tickets con filtros
    public function index(Request $request)
    {
        $query = Ticket::with(['empleado.usuario', 'tecnico.usuario']);
 
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('folio', 'ilike', "%$q%")
                    ->orWhere('titulo', 'ilike', "%$q%");
            });
        }
        if ($request->filled('estado'))    $query->where('estado', $request->estado);
        if ($request->filled('prioridad')) $query->where('prioridad', $request->prioridad);
        if ($request->filled('tecnico'))   $query->where('id_tecnico', $request->tecnico);
 
        $tickets  = $query->orderBy('created_at', 'desc')->paginate(20);
        $tecnicos = Tecnico::with('usuario')->get();
 
        return view('admin.tickets', compact('tickets', 'tecnicos'));
    }
 
    // Ver detalle de un ticket
public function show(Ticket $ticket)
{
    $ticket->load(['empleado.usuario', 'tecnico.usuario', 'historial']);
    $tecnicos = Tecnico::with('usuario')->get();
    return view('admin.ticket-show', compact('ticket', 'tecnicos'));
}
 
    // Asignar técnico a un ticket
    public function asignar(Request $request, Ticket $ticket)
    {
        $request->validate([
            'id_tecnico' => 'required|exists:tecnico,id_tecnico',
        ], [
            'id_tecnico.required' => 'Debes seleccionar un técnico.',
            'id_tecnico.exists'   => 'El técnico seleccionado no existe.',
        ]);
 
        if (in_array($ticket->estado, ['resuelto', 'cerrado'])) {
            return back()->withErrors([
                'error' => 'No puedes reasignar un ticket ya cerrado o resuelto.'
            ]);
        }
 
        $estadoAnterior = $ticket->estado;
 
        $ticket->update([
            'id_tecnico' => $request->id_tecnico,
            'estado'     => 'en_proceso',
            'updated_at' => now(),
        ]);
 
        TicketHistorial::create([
            'id_ticket'    => $ticket->id_ticket,
            'estado_ant'   => $estadoAnterior,
            'estado_nuevo' => 'en_proceso',
            'comentario'   => 'Ticket asignado a técnico por administrador.',
            'cambiado_por' => Auth::id(),
            'cambiado_at'  => now(),
        ]);
 
        return redirect()->route('admin.tickets')
            ->with('success', 'Ticket asignado correctamente.');
    }
}