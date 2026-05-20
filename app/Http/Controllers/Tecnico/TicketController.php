<?php

namespace App\Http\Controllers\Tecnico;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketHistorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * "Mis Tickets" — muestra SOLO el historial de tickets resueltos/cerrados
     * por este técnico. Es la vista de historial completo.
     */
    public function index()
    {
        $tecnicoId = Auth::user()->tecnico->id_tecnico;

        $tickets = Ticket::with(['empleado.usuario', 'historial'])
            ->where('id_tecnico', $tecnicoId)
            ->whereIn('estado', ['cerrado', 'resuelto'])
            ->orderBy('closed_at', 'desc')
            ->paginate(20);

        return view('tecnico.mis-tickets', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['empleado.usuario', 'historial']);
        return view('tecnico.mis-tickets', compact('ticket'));
    }

    public function finalizados()
    {
        return $this->index();
    }

    /**
     * Cerrar ticket con diagnóstico y solución (desde el modal del dashboard)
     */
    public function cerrar(Request $request, Ticket $ticket)
    {
        $request->validate([
            'comentario' => 'required|string|min:10',
        ], [
            'comentario.required' => 'Debes documentar la solución aplicada.',
            'comentario.min'      => 'La solución debe tener al menos 10 caracteres.',
        ]);

        if ($ticket->id_tecnico !== Auth::user()->tecnico->id_tecnico) {
            abort(403, 'Este ticket no está asignado a ti.');
        }

        $ticket->update([
            'estado'     => 'cerrado',
            'closed_at'  => now(),
            'updated_at' => now(),
        ]);

        TicketHistorial::create([
            'id_ticket'    => $ticket->id_ticket,
            'estado_ant'   => 'en_proceso',
            'estado_nuevo' => 'cerrado',
            'comentario'   => $request->comentario,
            'cambiado_por' => Auth::id(),
            'cambiado_at'  => now(),
        ]);

        return redirect()->route('tecnico.dashboard')
            ->with('success', 'Ticket cerrado y solución registrada correctamente.');
    }

    /**
     * Cambiar estado con comentario
     */
    public function cambiarEstado(Request $request, Ticket $ticket)
    {
        $request->validate([
            'estado'     => 'required|in:en_proceso,resuelto,cerrado',
            'comentario' => 'nullable|string|max:500',
        ]);

        if ($ticket->id_tecnico !== Auth::user()->tecnico->id_tecnico) {
            abort(403, 'Este ticket no está asignado a ti.');
        }

        if ($ticket->estado === 'cerrado') {
            return back()->withErrors(['error' => 'No puedes modificar un ticket ya cerrado.']);
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

        return redirect()->route('tecnico.dashboard')
            ->with('success', 'Estado actualizado correctamente.');
    }

    /**
     * Agregar comentario sin cambiar estado
     */
    public function comentar(Request $request, Ticket $ticket)
    {
        $request->validate(['comentario' => 'required|string|max:500']);

        TicketHistorial::create([
            'id_ticket'    => $ticket->id_ticket,
            'estado_ant'   => $ticket->estado,
            'estado_nuevo' => $ticket->estado,
            'comentario'   => $request->comentario,
            'cambiado_por' => Auth::id(),
            'cambiado_at'  => now(),
        ]);

        return back()->with('success', 'Comentario agregado.');
    }
}