<?php
//Aqui se maneja todo el flujo de autentificaciòn: Mostrar login
//Iniciar sesipon, cerrar sesiòn y redirigir segun el rol.

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //Mostrar el formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    //Metodo login()
public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    // 1. Buscar el usuario por email primero
    $usuario = \App\Models\Usuario::where('email', $request->email)->first();

    // 2. Si existe pero está inactivo → mensaje específico
    if ($usuario && !$usuario->activo) {
        return back()->withErrors([
            'email' => 'Tu cuenta ha sido desactivada. Contacta al administrador.',
        ])->onlyInput('email');
    }

    // 3. Intentar login normal (solo usuarios activos)
    $credenciales = [
        'email'    => $request->email,
        'password' => $request->password,
        'activo'   => true,
    ];

    if (Auth::attempt($credenciales)) {
        $request->session()->regenerate();
        return $this->redirigirPorRol();
    }

    // 4. Credenciales incorrectas
    return back()->withErrors([
        'email' => 'Correo o contraseña incorrectos.',
    ])->onlyInput('email');
}

    //Logout (Cerrar sesiòn)
    public function logout(Request $request)
    {
        //Cierra la sesiòn del usuario
        Auth::logout();
        //Limpia la sesiòn
        $request->session()->invalidate();
        //Genera nuevo token (Seguridad CSRF)
        $request->session()->regenerateToken();
        //Lo manda al login otra vez
        return redirect()->route('login');
    }


    //Redireccion segùn rol
    private function redirigirPorRol()
    {
        //Carga relaciones
        //Obtiene el usuario autentificado
        //Carga sus relaciones (roles)
        $user = Auth::user()->load(['administrador', 'tecnico', 'empleado']);


        //Logica de roles
        //Si es administrador muestra dashboard admin
        //Si es tècnico muestra dashboard tècnico
        //Si es empleado muestra dashboard empleado

        if ($user->administrador) return redirect('/admin/dashboard');
        if ($user->tecnico)       return redirect('/tecnico/dashboard');
        if ($user->empleado)      return redirect('/empleado/dashboard');

        // Caso sin rol
        //Si el usuario existe pero no tiene rol:
        //lo saca del sistema
        //Muestra error
        Auth::logout();
        return redirect()->route('login')->withErrors([
            'email' => 'Tu cuenta no tiene un rol asignado. Contacta al administrador.',
        ]);
    }
}