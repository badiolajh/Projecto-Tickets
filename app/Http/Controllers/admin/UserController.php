<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Empleado;
use App\Models\Tecnico;
use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with(['administrador', 'tecnico', 'empleado'])
            ->orderBy('nombre')->get();
        return view('admin.users.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'email'    => 'required|email|unique:usuario,email',
            'password' => 'required|min:6',
            'rol'      => 'required|in:empleado,tecnico,administrador',
            'area'     => 'nullable|string',
            'cargo_u'  => 'nullable|string|max:25',
        ]);

        $user = Usuario::create([
            'nombre'        => $request->nombre,
            'email'         => $request->email,
            'password_hash' => Hash::make($request->password),
            'area'          => $request->area,
            'cargo_u'       => $request->cargo_u,
            'activo'        => true,
        ]);

        match ($request->rol) {
            'administrador' => Administrador::create(['id_admin'    => $user->id]),
            'tecnico'       => Tecnico::create(['id_tecnico'        => $user->id]),
            'empleado'      => Empleado::create(['id_empleado'      => $user->id]),
        };

        return redirect()->route('admin.users.index')
            ->with('success', "Usuario {$user->nombre} creado correctamente.");
    }

    public function edit(Usuario $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, Usuario $user)
    {
        $request->validate([
            'nombre'  => 'required|string|max:100',
            'email'   => 'required|email|unique:usuario,email,' . $user->id,
            'area'    => 'nullable|string',
            'cargo_u' => 'nullable|string|max:25',
        ]);

        $data = $request->only(['nombre', 'email', 'area', 'cargo_u']);
        $data['activo'] = $request->boolean('activo');

        if ($request->filled('password')) {
            $data['password_hash'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

public function destroy(Usuario $user)
{
    $nuevoEstado = !$user->activo; // toggle: true→false, false→true
    $user->update(['activo' => $nuevoEstado]);

    $mensaje = $nuevoEstado ? 'Usuario activado correctamente.' : 'Usuario desactivado correctamente.';
    return redirect()->route('admin.users.index')->with('success', $mensaje);
}
}