@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-bold mb-6">Usuarios</h2>

<div class="bg-white p-4 rounded-xl shadow">

    <div class="flex justify-between mb-4">
        <input placeholder="Buscar usuario..." class="border p-2 rounded w-1/3">

        <a href="/admin/users/create" 
           class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            ➕ Nuevo Usuario
        </a>
    </div>

    <table class="w-full text-sm">

        <thead class="bg-gray-100">
            <tr>
                <th class="p-3">Nombre</th>
                <th class="p-3">Correo</th>
                <th class="p-3">Rol</th>
                <th class="p-3">Estado</th>
            </tr>
        </thead>

        <tbody>

            <tr class="border-t hover:bg-gray-50">
                <td class="p-3">Juan Pérez</td>
                <td class="p-3">juan@mail.com</td>
                <td class="p-3">Empleado</td>

                <td class="p-3">
                    <span class="bg-green-500 text-white px-2 py-1 rounded">
                        Activo
                    </span>
                </td>
            </tr>

        </tbody>

    </table>

</div>

@endsection