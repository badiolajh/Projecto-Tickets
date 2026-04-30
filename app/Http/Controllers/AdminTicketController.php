<?php
namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Tecnico;
use App\Models\TicketHistorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminTicketController extends Controller
{
    // Ver todos los tickets
    public function index()
    {
        $tickets = Ticket::with(['empleado.usuario', 'tecnico.usuario'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.tickets', compact('tickets'));
    }

    // Asignar ticket a un técnico
    public function asignar(Request $request, $id)
    {
        $request->validate([
            'id_tecnico' => 'required|exists:tecnico,id_tecnico',
        ], [
            'id_tecnico.required' => 'Debes seleccionar un técnico.',
            'id_tecnico.exists'   => 'El técnico seleccionado no existe.',
        ]);

        $ticket = Ticket::findOrFail($id);

        // Verificar que el ticket no esté ya cerrado
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

        // Registrar en historial
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

    // Ver lista de técnicos disponibles para asignar
    public function tecnicos()
    {
        return Tecnico::with('usuario')->get();
    }
}