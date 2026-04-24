<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/empleado/dashboard', fn() => view('empleado.dashboard'));

Route::get('/tickets/create', fn() => view('tickets.create'));

Route::get('/tickets', fn() => view('tickets.index'));