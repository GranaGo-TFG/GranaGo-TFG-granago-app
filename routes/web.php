<?php

use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\LogroController;
use App\Http\Controllers\RetoController;
use App\Http\Controllers\ValidacionRetoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::apiResource('retos', RetoController::class);
    Route::apiResource('validaciones-reto', ValidacionRetoController::class);
    Route::apiResource('logros', LogroController::class);
    Route::apiResource('comentarios', ComentarioController::class);

    Route::post('/logros/{logro}/asignar-usuario', [LogroController::class, 'asignarUsuario'])
        ->name('logros.asignar-usuario');
    Route::delete('/logros/{logro}/retirar-usuario/{user}', [LogroController::class, 'retirarUsuario'])
        ->name('logros.retirar-usuario');
});
