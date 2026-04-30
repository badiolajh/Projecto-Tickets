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
        if (!Auth::user()->tecnico) {
            abort(403, 'No tienes acceso a esta sección.');
        }

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
        if (!Auth::user()->tecnico) {
            abort(403, 'No tienes acceso a esta sección.');
        }

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
            'comentario' => 'nullable|string|max:500',
        ], [
            'estado.required' => 'El estado es obligatorio.',
            'estado.in'       => 'El estado no es válido.',
            'comentario.max'  => 'El comentario no puede superar 500 caracteres.',
        ]);

        $ticket = Ticket::findOrFail($id);

        // Verificar que el ticket pertenece a este técnico
        if ($ticket->id_tecnico !== Auth::user()->tecnico->id_tecnico) {
            abort(403, 'Este ticket no está asignado a ti.');
        }

        // Verificar que el ticket no esté ya cerrado
        if ($ticket->estado === 'cerrado') {
            return back()->withErrors([
                'error' => 'No puedes modificar un ticket ya cerrado.'
            ]);
        }

        $estadoAnterior = $ticket->estado;

        $ticket->update([
            'estado'     => $request->estado,
            'updated_at' => now(),
            'closed_at'  => in_array($request->estado, ['resuelto', 'cerrado']) ? now() : null,
        ]);

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