<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
 
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        $user = $request->user();
 
        if (!$user) {
            return redirect()->route('login');
        }
 
        $authorized = match ($role) {
            'administrador' => $user->administrador()->exists(),
            'tecnico'       => $user->tecnico()->exists(),
            'empleado'      => $user->empleado()->exists(),
            default         => false,
        };
 
        if (!$authorized) {
            // Redirige al dashboard correcto según el rol real del usuario
            if ($user->administrador()->exists()) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->tecnico()->exists()) {
                return redirect()->route('tecnico.dashboard');
            }
            return redirect()->route('empleado.dashboard');
        }
 
        return $next($request);
    }
}