<?php
//Este es el modelo central del sistema
//Aqui se define como laravel autentica al usuario y como se conectan los roles(admin,tècnico,empleado)
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;

//Tipo de modelo
//No es un modelo normal, este extiende de Authenticatable
//Esto permite:
//login con Auth::attemp(), manejo de sessiones y uso del sistema de autenticaciòn de Laravel
class Usuario extends Authenticatable
{
    //Configuracion Basica
    protected $table      = 'usuario'; //tabal usuario
    protected $primaryKey = 'id';  //PK
    public    $timestamps = false; // Sin timestamps automaticos

    //Campos asignables
    //Permite hacer Usuario::create([...])
    //incluye datos personales, estado(activo) y contraseña(hash)
    protected $fillable = [
        'nombre', 'email', 'password_hash',
        'area', 'cargo_u', 'edi_u', 'activo',
    ];

    //Ocultar contraseña
    //evita que aparesca en JSON y se exponga en APIs
    protected $hidden = ['password_hash'];

    //Personalizaciòn del password
    public function getAuthPassword(): string
    {   //Laravel por defecto usa `password` pero se cambia a `password_hash`
        return $this->password_hash;
    }

    //Relaciones de roles
    public function administrador(): HasOne
    {
        //Un usuario puede ser administrado, si existe registro en Administrador es admin
        return $this->hasOne(Administrador::class, 'id_admin', 'id');
    }

    public function empleado(): HasOne
    {
        //Un usuario puede ser Empleado, si existe registro en Empleado es empleado
        return $this->hasOne(Empleado::class, 'id_empleado', 'id');
    }

    public function tecnico(): HasOne
    {   //Un usuario puede ser Tecnico, si existe registro en Tecnico es tecnico
        return $this->hasOne(Tecnico::class, 'id_tecnico', 'id');
    }
}