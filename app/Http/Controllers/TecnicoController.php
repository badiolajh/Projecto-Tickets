<?php
namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketHistorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TecnicoController extends Controller
{
    // Tickets pendientes (abierto o en_proceso)
    public function index()
    {
        $tickets = Ticket::with(['empleado.usuario'])
            ->where('id_tecnico', Auth::user()->tecnico->id_tecnico)
            ->whereIn('estado', ['abierto', 'en_proceso'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tecnico.pendientes', compact('tickets'));
    }

    // Tickets finalizados (resuelto o cerrado)
    public function finalizados()
    {
        $tickets = Ticket::with(['empleado.usuario'])
            ->where('id_tecnico', Auth::user()->tecnico->id_tecnico)
            ->whereIn('estado', ['resuelto', 'cerrado'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('tecnico.finalizados', compact('tickets'));
    }

    // Cambiar estado del ticket + comentario
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado'     => 'required|in:en_proceso,resuelto,cerrado',
            'comentario' => 'nullable|string',
        ]);

        $ticket = Ticket::findOrFail($id);
        $estadoAnterior = $ticket->estado;

        $ticket->update([
            'estado'     => $request->estado,
            'updated_at' => now(),
            'closed_at'  => in_array($request->estado, ['resuelto', 'cerrado']) ? now() : null,
        ]);

        // Registrar en historial
        TicketHistorial::create([
            'id_ticket'    => $ticket->id_ticket,
            'estado_ant'   => $estadoAnterior,
            'estado_nuevo' => $request->estado,
            'comentario'   => $request->comentario,
            'cambiado_por' => Auth::id(),
            'cambiado_at'  => now(),
        ]);

        return redirect()->route('tecnico.tickets')
            ->with('success', 'Estado actualizado correctamente.');
    }
}