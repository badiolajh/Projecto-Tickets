<?php

namespace App\Http\Controllers\Empleado;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * "Mis Tickets" — historial completo con filtro por estado
     */
    public function index(Request $request)
    {
        $empleadoId = Auth::user()->empleado->id_empleado;

        $query = Ticket::with(['tecnico.usuario', 'historial'])
            ->where('id_empleado', $empleadoId);

        // Filtro por estado desde los botones de la vista
        if ($request->filled('estado')) {
            if ($request->estado === 'cerrado') {
                $query->whereIn('estado', ['cerrado', 'resuelto']);
            } else {
                $query->where('estado', $request->estado);
            }
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'abiertos'   => Ticket::where('id_empleado', $empleadoId)->where('estado', 'abierto')->count(),
            'en_proceso' => Ticket::where('id_empleado', $empleadoId)->where('estado', 'en_proceso')->count(),
            'cerrados'   => Ticket::where('id_empleado', $empleadoId)->whereIn('estado', ['cerrado', 'resuelto'])->count(),
        ];

        return view('empleado.mis-tickets', compact('tickets', 'stats'));
    }

    /**
     * Formulario de nuevo ticket
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Guardar nuevo ticket — corregido TTicket → Ticket
     */
public function store(Request $request)
{
    $request->validate([
        'titulo'      => 'required|string|min:5|max:150',
        'descripcion' => 'required|string|min:10',
        'prioridad'   => 'required|in:Normal,Alta,Baja',
        'imagen'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
    ]);

    // ← AQUÍ, antes del create
    $empleadoId = Auth::user()->empleado->id_empleado;

    $rutaImagen = null;
    if ($request->hasFile('imagen')) {
        $rutaImagen = $request->file('imagen')->store('tickets', 'public');
    }

    Ticket::create([
        'folio'       => $this->generarFolio(),
        'titulo'      => $request->titulo,
        'descripcion' => $request->descripcion,
        'prioridad'   => $request->prioridad,
        'estado'      => 'abierto',
        'id_empleado' => $empleadoId,
        'imagen_ruta' => $rutaImagen,
    ]);

    return redirect()->route('empleado.dashboard')
        ->with('success', 'Ticket creado correctamente. El administrador asignará un tecnico pronto');
}

    private function generarFolio(): string
    {
        $fecha  = now()->format('Ymd');
        $ultimo = Ticket::whereDate('created_at', today())->count() + 1;
        return 'TKT-' . $fecha . '-' . str_pad($ultimo, 4, '0', STR_PAD_LEFT);
    }
}