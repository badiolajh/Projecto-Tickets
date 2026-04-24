<?php
// Este modelo define como laravel representa a los empleados y como se relaciona 
// con usuarios y tickets
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//Es un modelo Eloquent
//Representa la tabla empleado
//Cada registro = un empleado del sistema
class Empleado extends Model
{
    //Configuracion de la tabla
    protected $table      = 'empleado'; //nombre de la tabla
    protected $primaryKey = 'id_empleado'; //PK
    public    $timestamps = false; //sin timestamps

    //Campos asignables
    //solo se puede asignar este campo masivamente
    protected $fillable = ['id_empleado'];


    //Relacion con Usuario
    public function usuario()
    {                                           //FK           // Apunta a usuario.id
        return $this->belongsTo(Usuario::class, 'id_empleado', 'id');
    }

    //Relacion con tickets
    //un empleado puede tener muchos tickets
    public function tickets()
    {                         //Modelo       //FK tb tickets  //PK tb empleados
        return $this->hasMany(Ticket::class, 'id_empleado',  'id_empleado');
    }
}