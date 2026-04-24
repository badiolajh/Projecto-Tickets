<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Projectls</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-700 h-screen flex items-center justify-center">

    <div class="flex w-[900px] bg-white rounded-2xl shadow-2xl overflow-hidden fade-in">

        <!-- LADO IZQUIERDO (branding) -->
        <div class="w-1/2 bg-blue-600 text-white flex flex-col justify-center items-center p-10">

            <h1 class="text-3xl font-bold mb-4">Projectls</h1>
            <p class="text-center text-sm opacity-90">
                Gestiona incidencias de manera rápida y eficiente.
            </p>

        </div>

        <!-- LADO DERECHO (formulario) -->
        <div class="w-1/2 p-10">

            <h2 class="text-2xl font-bold mb-6 text-gray-700">
                Iniciar Sesión
            </h2>

            <form action="/empleado/dashboard" class="space-y-5">

                <div>
                    <label class="text-sm text-gray-600">Correo</label>
                    <input 
                        type="email"
                        class="w-full p-3 border rounded-lg mt-1 focus:ring-2 focus:ring-blue-400 outline-none transition"
                        placeholder="ejemplo@correo.com"
                    >
                </div>

                <div>
                    <label class="text-sm text-gray-600">Contraseña</label>
                    <input 
                        type="password"
                        class="w-full p-3 border rounded-lg mt-1 focus:ring-2 focus:ring-blue-400 outline-none transition"
                        placeholder="********"
                    >
                </div>

                <button class="w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition transform hover:scale-105">
                    Entrar
                </button>

            </form>

            <p class="text-xs text-gray-400 mt-6 text-center">
                 2026 Sistema de Tickets
            </p>

        </div>

    </div>

</body>
</html>