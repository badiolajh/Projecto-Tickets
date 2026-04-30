<?php
// app/Http/Controllers/TicketController.php
namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TicketController extends Controller
{
    // Listar tickets del empleado autenticado
    public function index()
    {
        // Verifica que el usuario tenga rol empleado
        if (!Auth::user()->empleado) {
            abort(403, 'No tienes acceso a esta sección.');
        }

        $tickets = Ticket::where('id_empleado', Auth::user()->empleado->id_empleado)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('empleado.tickets', compact('tickets'));
    }

    // Mostrar formulario de crear ticket
    public function create()
    {
        if (!Auth::user()->empleado) {
            abort(403, 'No tienes acceso a esta sección.');
        }

        return view('empleado.crear_ticket');
    }

    // Guardar nuevo ticket + asignación automática al admin
    public function store(Request $request)
    {
        $request->validate([
            'titulo'      => 'required|string|min:5|max:150',
            'descripcion' => 'required|string|min:10',
            'prioridad'   => 'required|in:Normal,Alta',
        ], [
            'titulo.required'      => 'El título es obligatorio.',
            'titulo.min'           => 'El título debe tener al menos 5 caracteres.',
            'titulo.max'           => 'El título no puede superar 150 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.min'      => 'La descripción debe tener al menos 10 caracteres.',
            'prioridad.required'   => 'La prioridad es obligatoria.',
            'prioridad.in'         => 'La prioridad debe ser Normal o Alta.',
        ]);

        // ── Asignación automática al admin ──────────────────────────
         // Verificar que existe al menos un admin
        $admin = Administrador::first();
        if (!$admin) {
            return back()->withErrors([
                'error' => 'No hay administradores disponibles. Contacta al sistema.'
            ])->withInput();
        }

        // Verificar que el usuario tiene rol empleado
        if (!Auth::user()->empleado) {
            abort(403, 'No tienes acceso a esta sección.');
        }
        // ────────────────────────────────────────────────────────────

        TTicket::create([
            'folio'       => $this->generarFolio(),
            'titulo'      => $request->titulo,
            'descripcion' => $request->descripcion,
            'prioridad'   => $request->prioridad,
            'estado'      => 'abierto',
            'id_empleado' => Auth::user()->empleado->id_empleado,
            'id_tecnico'  => null,
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