<?php

use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\LogroController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RetoController;
use App\Http\Controllers\ValidacionRetoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'not_banned', 'not_admin'])->group(function () {
    Route::get('/vista-retos', [RetoController::class, 'indexView'])->name('vistas.retos');
    Route::view('/vista-reto-detalle', 'vistas.reto-detalle')->name('vistas.reto-detalle');
    Route::view('/vista-subir-prueba', 'vistas.subir-prueba')->name('vistas.subir-prueba');
    Route::view('/vista-ranking', 'vistas.ranking')->name('vistas.ranking');
    Route::view('/vista-tienda', 'vistas.tienda')->name('vistas.tienda');
    Route::view('/vista-perfil', 'vistas.perfil')->name('vistas.perfil');
    Route::get('/vista-editar-perfil', [ProfileController::class, 'edit'])->name('vistas.editar-perfil');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('perfil.update');
    Route::view('/vista-comunidad', 'vistas.comunidad')->name('vistas.comunidad');
    Route::view('/vista-validaciones', 'vistas.validaciones')->name('vistas.validaciones');

    Route::apiResource('retos', RetoController::class);
    Route::apiResource('validaciones-reto', ValidacionRetoController::class);
    Route::apiResource('logros', LogroController::class);
    Route::apiResource('comentarios', ComentarioController::class);

    Route::post('/logros/{logro}/asignar-usuario', [LogroController::class, 'asignarUsuario'])
        ->name('logros.asignar-usuario');
    Route::delete('/logros/{logro}/retirar-usuario/{user}', [LogroController::class, 'retirarUsuario'])
        ->name('logros.retirar-usuario');
});

Route::prefix('admin')
    ->middleware(['auth', 'not_banned', 'admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/retos', [AdminController::class, 'retos'])->name('retos.index');
        Route::patch('/retos/{reto}/estado', [AdminController::class, 'actualizarEstadoReto'])->name('retos.update');

        Route::get('/validaciones', [AdminController::class, 'validaciones'])->name('validaciones.index');
        Route::patch('/validaciones/{validacion}/estado', [AdminController::class, 'actualizarEstadoValidacion'])->name('validaciones.update');

        Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('usuarios.index');
        Route::patch('/usuarios/{user}/baneo', [AdminController::class, 'actualizarBaneoUsuario'])->name('usuarios.update');
    });
