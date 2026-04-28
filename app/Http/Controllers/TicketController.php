<?php
// app/Http/Controllers/TicketController.php
namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // Listar tickets del empleado autenticado
    public function index()
    {
        $tickets = Ticket::where('id_empleado', Auth::user()->empleado->id_empleado)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('empleado.tickets', compact('tickets'));
    }

    // Mostrar formulario de crear ticket
    public function create()
    {
        return view('empleado.crear_ticket');
    }

    // Guardar nuevo ticket + asignación automática al admin
    public function store(Request $request)
    {
        $request->validate([
            'titulo'      => 'required|string|max:150',
            'descripcion' => 'required|string',
            'prioridad'   => 'required|in:Normal,Alta',
        ]);

        // ── Asignación automática al admin ──────────────────────────
        // Busca el admin con menos tickets asignados (reparto equitativo)
        $admin = Administrador::withCount('ticketsAsignados')
            ->orderBy('tickets_asignados_count', 'asc')
            ->first();

        if (!$admin) {
            return back()->withErrors([
                'error' => 'No hay administradores disponibles.'
            ]);
        }
        // ────────────────────────────────────────────────────────────

        Ticket::create([
            'folio'       => $this->generarFolio(),
            'titulo'      => $request->titulo,
            'descripcion' => $request->descripcion,
            'prioridad'   => $request->prioridad,
            'estado'      => 'abierto',
            'id_empleado' => Auth::user()->empleado->id_empleado,
            'id_tecnico'  => null,  // el admin lo asignará después
        ]);

        return redirect()->route('empleado.tickets')
            ->with('success', 'Ticket creado correctamente.');
    }

    // Generar folio único: TKT-YYYYMMDD-XXXX
    private function generarFolio(): string
    {
        $fecha = now()->format('Ymd');
        $ultimo = Ticket::whereDate('created_at', today())->count() + 1;
        return 'TKT-' . $fecha . '-' . str_pad($ultimo, 4, '0', STR_PAD_LEFT);
        // Ejemplo: TKT-20250427-0001
    }
}