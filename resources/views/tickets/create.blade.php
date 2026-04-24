@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-bold text-gray-800 mb-6">
    Crear Ticket
</h2>

<p class="text-gray-500 mb-6">
    Describe el problema que estás presentando.
</p>

<form action="#" method="POST" class="bg-white p-6 rounded-2xl shadow space-y-5">

    @csrf

    <!-- TITULO -->
    <div>
        <label class="block text-sm text-gray-600 mb-1">
            Título del problema
        </label>

        <input 
            type="text"
            name="titulo"
            placeholder="Ej: No enciende mi computadora"
            class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
        >
    </div>

    <!-- DESCRIPCIÓN -->
    <div>
        <label class="block text-sm text-gray-600 mb-1">
            Descripción
        </label>

        <textarea 
            name="descripcion"
            rows="4"
            placeholder="Explica detalladamente el problema..."
            class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
        ></textarea>
    </div>

    <!-- PRIORIDAD -->
    <div>
        <label class="block text-sm text-gray-600 mb-1">
            Prioridad
        </label>

        <select 
            name="prioridad"
            class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
        >
            <option value="Baja">Baja</option>
            <option value="Normal" selected>Normal</option>
            <option value="Alta">Alta</option>
        </select>
    </div>

    <!-- BOTÓN -->
    <button 
        class="w-full bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600 transition">
        Enviar Ticket
    </button>

</form>

@endsection