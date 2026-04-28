<?php
// app/Models/Ticket.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table      = 'ticket';
    protected $primaryKey = 'id_ticket';

    // tu tabla usa created_at y updated_at, Laravel los maneja solo
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'folio', 'titulo', 'descripcion',
        'estado', 'prioridad',
        'id_empleado', 'id_tecnico',
        'closed_at',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'id_empleado', 'id_empleado');
    }

    public function tecnico()
    {
        return $this->belongsTo(Tecnico::class, 'id_tecnico', 'id_tecnico');
    }

    public function historial()
    {
        return $this->hasMany(TicketHistorial::class, 'id_ticket', 'id_ticket');
    }
}