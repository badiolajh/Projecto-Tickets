@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-bold text-gray-800 mb-6">
    Agregar Usuario
</h2>

<form class="bg-white p-6 rounded-xl shadow space-y-4">

    <input 
        type="text" 
        placeholder="Nombre"
        class="w-full p-3 border rounded-lg"
    >

    <input 
        type="email" 
        placeholder="Correo"
        class="w-full p-3 border rounded-lg"
    >

    <input 
        type="password" 
        placeholder="Contraseña"
        class="w-full p-3 border rounded-lg"
    >

    <select class="w-full p-3 border rounded-lg">
        <option>Empleado</option>
        <option>Técnico</option>
        <option>Administrador</option>
    </select>

    <button class="w-full bg-green-500 text-white p-3 rounded-lg hover:bg-green-600">
        Crear Usuario
    </button>

    <button class="w-full bg-red-500 text-white p-3 rounded-lg hover:bg-red-600">
        Cancelar
    </button>

</form>

@endsection