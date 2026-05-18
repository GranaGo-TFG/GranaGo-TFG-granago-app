<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogroController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RetoController;
use App\Http\Controllers\RetoVistaController;
use App\Http\Controllers\ValidacionRetoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::redirect('/mapa-retos', '/home')->name('retos.mapa');
Route::get('/api/retos-mapa', [RetoController::class, 'mapaData'])->name('retos.mapa.data');

Auth::routes();

Route::middleware(['auth', 'not_banned'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::middleware('not_admin')->prefix('vistas')->name('vistas.')->group(function () {
        Route::get('/retos', [RetoVistaController::class, 'index'])->name('retos');
        Route::get('/retos/crear', [RetoVistaController::class, 'create'])->name('crear-reto');
        Route::post('/retos', [RetoVistaController::class, 'store'])->name('retos.store');
        Route::get('/retos/{reto}', [RetoVistaController::class, 'show'])->name('reto-detalle');

        Route::view('/subir-prueba', 'vistas.subir-prueba')->name('subir-prueba');
        Route::view('/ranking', 'vistas.ranking')->name('ranking');
        Route::view('/comunidad', 'vistas.comunidad')->name('comunidad');
        Route::view('/tienda', 'vistas.tienda')->name('tienda');
        Route::view('/perfil', 'vistas.perfil')->name('perfil');

        Route::get('/perfil/editar', [ProfileController::class, 'edit'])->name('editar-perfil');
        Route::patch('/perfil', [ProfileController::class, 'update'])->name('perfil.update');
    });

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/retos', [AdminController::class, 'retos'])->name('retos.index');
        Route::patch('/retos/{reto}', [AdminController::class, 'actualizarEstadoReto'])->name('retos.update');

        Route::get('/validaciones', [AdminController::class, 'validaciones'])->name('validaciones.index');
        Route::patch('/validaciones/{validacion}', [AdminController::class, 'actualizarEstadoValidacion'])->name('validaciones.update');

        Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('usuarios.index');
        Route::patch('/usuarios/{user}', [AdminController::class, 'actualizarBaneoUsuario'])->name('usuarios.update');
    });

    Route::apiResource('retos', RetoController::class);
    Route::apiResource('validaciones-reto', ValidacionRetoController::class);
    Route::apiResource('logros', LogroController::class);
    Route::apiResource('comentarios', ComentarioController::class);

    Route::post('/logros/{logro}/asignar-usuario', [LogroController::class, 'asignarUsuario'])
        ->name('logros.asignar-usuario');
    Route::delete('/logros/{logro}/retirar-usuario/{user}', [LogroController::class, 'retirarUsuario'])
        ->name('logros.retirar-usuario');
});
