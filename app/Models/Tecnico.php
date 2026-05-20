<?php
//Este modelo sigue el mismo patron que administrador y empleado
//pero en este hay observaciones
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//Modelo de Eloquent
//Representa la tabla tecnico
//Cada registro = un tecnico del sistema
class Tecnico extends Model
{
    //COnfiguracion basica
    protected $table      = 'tecnico'; //tabla tecnico
    protected $primaryKey = 'id_tecnico'; //PK
    public    $timestamps = false; // Sin timestampsd

    //Campos asignables
                           //relacion con usuario  //dato extra del tecnico
    protected $fillable = ['id_tecnico',           'observaciones'];

    //Relaciòn con usuario
    //El tecnico pertenece a un usario
    public function usuario()
    {                                           //FK           // Apunta a usuario.id
        return $this->belongsTo(Usuario::class, 'id_tecnico', 'id');
    }

    //Relacion con tickets
    //Un tecnico puede tener muchos tickets asignados
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'id_tecnico', 'id_tecnico');
    }
}