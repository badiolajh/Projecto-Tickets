@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-bold mb-4">Tickets</h2>

<div class="bg-white p-4 rounded-xl shadow">

    <div class="flex justify-between mb-4">
        <input placeholder="Buscar..." class="border p-2 rounded w-1/3">
    </div>

    <table class="w-full text-sm">

        <thead class="bg-gray-100">
            <tr>
                <th class="p-3">Folio</th>
                <th class="p-3">Usuario</th>
                <th class="p-3">Problema</th>
                <th class="p-3">Prioridad</th>
                <th class="p-3">Estado</th>
            </tr>
        </thead>

        <tbody>

            <tr class="border-t hover:bg-gray-50">
                <td class="p-3">#001</td>
                <td class="p-3">Juan Pérez</td>
                <td class="p-3">No enciende PC</td>

                <td class="p-3">
                    <span class="bg-red-500 text-white px-2 py-1 rounded">
                        Alta
                    </span>
                </td>

                <td class="p-3">
                    <span class="bg-yellow-400 px-2 py-1 rounded">
                        Abierto
                    </span>
                </td>
            </tr>

        </tbody>

    </table>

</div>

@endsection