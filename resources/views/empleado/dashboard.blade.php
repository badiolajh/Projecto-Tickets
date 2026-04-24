@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-bold text-gray-800 mb-6">
    Bienvenido 👋
</h2>

<p class="text-gray-500 mb-6">
    Gestiona tus incidencias de manera rápida y sencilla.
</p>

<div class="grid grid-cols-2 gap-6">

    <!-- Crear Ticket -->
    <a href="/tickets/create" 
       class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition transform hover:-translate-y-1">

        <h3 class="text-lg font-bold text-gray-700 mb-2">
            ➕ Crear Ticket
        </h3>

        <p class="text-sm text-gray-500">
            Reporta un problema con tu equipo o sistema.
        </p>

    </a>

    <!-- Ver Tickets -->
    <a href="/tickets" 
       class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition transform hover:-translate-y-1">

        <h3 class="text-lg font-bold text-gray-700 mb-2">
            📋 Mis Tickets
        </h3>

        <p class="text-sm text-gray-500">
            Consulta el estado de tus solicitudes.
        </p>

    </a>

</div>

@endsection