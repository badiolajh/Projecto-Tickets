<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema Tickets</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex h-screen">

    <aside class="w-64 bg-gray-900 text-white p-5 space-y-6">
        <h1 class="text-xl font-bold">Tickets</h1>

        <nav class="space-y-3">
            <a href="/empleado/dashboard" class="block hover:text-blue-400">Inicio</a>
            <a href="/tickets" class="block hover:text-blue-400">Tickets</a>
            <a href="/login" class="block hover:text-red-400">Salir</a>
            
        </nav>
    </aside>

    <div class="flex-1 flex flex-col">

        <header class="bg-white shadow p-4 flex justify-between">
            <h2 class="font-semibold">Panel</h2>
            <span class="text-gray-500">Usuario</span>
        </header>

        <main class="p-6 overflow-y-auto">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>