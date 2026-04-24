<?php
//Este archivo es parte de la configuracion base de laravel 11+
//aqui se define como se arranca la app: rutas, middleware, etc
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


//Inicializa la aplicacion
//Define la ruta del proyecto
//dirname() apunta al directorio raiz del proyecto
return Application::configure(basePath: dirname(__DIR__))

    //Configuracion de rutas
    ->withRouting(
        web: __DIR__.'/../routes/web.php', //carga las rutas web normales
        commands: __DIR__.'/../routes/console.php', //comandos artisan
        health: '/up', // endpoint de salud
    )

    //Registro de middleware personalizado
    ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        //crea un nombre corto para el middleware
        'rol' => \App\Http\Middleware\RolMiddleware::class,
    ]);
})->create();
