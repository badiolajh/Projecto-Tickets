<?php
// Este modelo representa a los administradores y a su vez esta conectado al usuario
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//Definicon del modelo
class Administrador extends Model //Se esta creando un modelo de Eloquent
//Representa la tabla a¡'administrador' en la BD
{
    //Nombre de la tabla
    //Laravel por defecto uaria administradors, aqui se corrige manualmente a administrador
    protected $table      = 'administrador';
    //Llave primaria personalizada
    //le dice a laravel que la PK no es id, sino id_admin
    protected $primaryKey = 'id_admin';
    //Sin timestamps
    public    $timestamps = false;
    //Campos asignables
    //permite asignar este campo masivamente
    protected $fillable = ['id_admin'];

    //Relacion con Usuario, un administrador pertenece a un Usuario
    public function usuario()
    {
                                //Modelo        //FK        //PK
        return $this->belongsTo(Usuario::class, 'id_admin', 'id');
    }

    // Funcion para tickets del admin
    public function ticketsAsignados()
    {
        // Los tickets donde el técnico fue asignado por este admin
        // Por ahora contamos tickets abiertos sin técnico asignado
        return $this->hasMany(Ticket::class, 'id_empleado', 'id_admin');
    }

}