<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 
class RolMiddleware
{
    public function handle(Request $request, Closure $next, string $rol)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
 
        // CORREGIDO: carga las relaciones antes de verificar
        // Sin esto, ->administrador puede ser null aunque exista en BD
        $user = Auth::user()->load(['administrador', 'tecnico', 'empleado']);
        if (!$user->activo) {
             Auth::logout();
        return redirect()->route('login')->withErrors([
            'email' => 'Tu cuenta ha sido desactivada. Contacta al administrador.'
        ]);
        }
        $permitido = match ($rol) {
            'admin'    => $user->administrador !== null,
            'tecnico'  => $user->tecnico !== null,
            'empleado' => $user->empleado !== null,
            default    => false,
        };
 
        if (!$permitido) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
 
        return $next($request);

    }
    
}