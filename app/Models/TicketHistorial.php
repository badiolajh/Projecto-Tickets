<?php
// app/Models/TicketHistorial.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketHistorial extends Model
{
    protected $table      = 'ticket_historial';
    protected $primaryKey = 'id_historial';
    public    $timestamps = false;

    protected $fillable = [
        'id_ticket', 'estado_ant', 'estado_nuevo',
        'comentario', 'cambiado_por', 'cambiado_at',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'id_ticket', 'id_ticket');
    }
}