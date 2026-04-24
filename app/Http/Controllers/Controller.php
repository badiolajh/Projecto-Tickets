<?php
//Aqui se maneja todo el flujo de autentificaciòn: Mostrar login
//Iniciar sesipon, cerrar sesiòn y redirigir segun el rol.

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //Mostrar el formulario de login
    public function showLogin()
    {
        return view('auth.login');
    }

    //Metodo login()
    public function login(Request $request)
    {
        //Validaciòn
        //verifica que el email exista y que tenga formato valido
        // y que la contraseña no estè vacia
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Aqui se define que campos se usan para autentificar
        // Laravel solo permite login si activo = true en la BD
        $credenciales = [
            'email'  => $request->email, //usuario
            'password' => $request->password, //contraseña
            'activo' => true, //solo permite login si el usuario esta activo
        ];
    
        // intento de login
        // Busca el usuario por email
        // Verifica la contraseña (con hash automaticamente)
        // Tambien valida activo = true
        if (Auth::attempt($credenciales)) { 

            //Seguridad de sesiòn
            //Regenera el ID de sesiòn
            //Previene ataques de session fixation
            $request->session()->regenerate();

            //Redireccion por rol
            //si el login es correcto, manda al usuario segun su rol
            return $this->redirigirPorRol();
        }

        //Error si falla
        //regresa el formulario
        //Muestra error
        //Mantiene el email ingresado
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