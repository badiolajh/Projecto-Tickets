<?php

//Este middleware se encarga de proteger rutas segùn el rol del usuario.
//Es decir, decide si alguien puede o no acceder a cierta parte del sistema.

//Este se ejecuta antes de que el usuario llegue a una ruta/controlador
//sirve para validar acceso
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolMiddleware
{
    //Metodo principal: handle()
    //Recibe la peticion(Request)
    //Recibe $next (para continuar la ejecuciòn)
    //Recibe $rol requerido
    public function handle(Request $request, Closure $next, string $rol)
    {
        //Verifica si el usuario està autenticado
        if (!Auth::check()) { //verifica si hay sesiòn activa
            return redirect()->route('login'); //si no hay usuarios logueados los manda al login
        }

        //Obtiene el usuario
        $user = Auth::user(); //Aqui ya tiene acceso a los datos del usuario autenticado

        //Verificar el rol
        //si tiene relaciòn el acceso es permitido de lo contrario es acceso denegado
        $permitido = match($rol) { //usa match() similar a switch para validar el rol
            'admin'    => $user->administrador !== null,
            'tecnico'  => $user->tecnico !== null,
            'empleado' => $user->empleado !== null,
            default    => false,
        };

        //Bloqueo si no tiene permiso
        //Si no cumple el rol lanza el error 403(Forbidden) y muestra mensaje
        //Esto evita que accedan incluso si escriben la URL manualmente
        if (!$permitido) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        //Permite continuar
        //Si todo està bien la peticion sigue su camino
        //LLega al controlador o vista
        return $next($request);
    }
}