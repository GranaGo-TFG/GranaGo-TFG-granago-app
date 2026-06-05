<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ComunidadController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogroController;
use App\Http\Controllers\PlanesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RetoController;
use App\Http\Controllers\RetoVistaController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\ValidacionRetoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

Route::redirect('/mapa-retos', '/home')->name('retos.mapa');
Route::get('/api/retos-mapa', [RetoController::class, 'mapaData'])->name('retos.mapa.data');
Route::view('/privacidad', 'legal.privacidad')->name('legal.privacidad');
Route::view('/aviso-legal', 'legal.aviso-legal')->name('legal.aviso-legal');
Route::view('/contacto', 'legal.contacto')->name('legal.contacto');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/vistas/retos', [RetoVistaController::class, 'index'])->name('vistas.retos');

Route::middleware(['auth', 'not_banned'])->group(function () {
    Route::middleware('not_admin')->prefix('vistas')->name('vistas.')->group(function () {
        Route::get('/retos/crear', [RetoVistaController::class, 'create'])->name('crear-reto');
        Route::post('/retos', [RetoVistaController::class, 'store'])->name('retos.store');
        Route::get('/retos/{reto}', [RetoVistaController::class, 'show'])->name('reto-detalle');
        Route::get('/retos/{reto}/subir-prueba', [ValidacionRetoController::class, 'create'])->name('subir-prueba');
        Route::post('/retos/{reto}/subir-prueba', [ValidacionRetoController::class, 'storeFromView'])->name('subir-prueba.store');
        Route::view('/ranking', 'vistas.ranking')->name('ranking');
        Route::view('/logros', 'vistas.logros')->name('logros');
        Route::get('/tienda', [TiendaController::class, 'index'])->name('tienda');
        Route::get('/tienda/productos/{producto}', [TiendaController::class, 'show'])->name('tienda.producto');
        Route::get('/tienda/productos/{producto}/pago', [TiendaController::class, 'pago'])->name('tienda.pago');
        Route::post('/tienda/productos/{producto}/pago', [TiendaController::class, 'procesarPago'])->name('tienda.pago.store');
        Route::view('/planes', 'vistas.planes')->name('planes');
        Route::get('/planes/{plan}/pago', [PlanesController::class, 'pago'])->name('planes.pago');
        Route::post('/planes/{plan}/pago', [PlanesController::class, 'procesarPago'])->name('planes.pago.store');
        Route::view('/perfil', 'vistas.perfil')->name('perfil');

        Route::get('/perfil/editar', [ProfileController::class, 'edit'])->name('editar-perfil');
        Route::patch('/perfil', [ProfileController::class, 'update'])->name('perfil.update');
    });

    Route::prefix('vistas')->name('vistas.')->group(function () {
        Route::get('/comunidad', [ComunidadController::class, 'index'])->name('comunidad');
        Route::post('/comunidad/publicaciones', [ComunidadController::class, 'storePublicacion'])->name('comunidad.publicaciones.store');
        Route::patch('/comunidad/publicaciones/{publicacion}', [ComunidadController::class, 'updatePublicacion'])->name('comunidad.publicaciones.update');
        Route::delete('/comunidad/publicaciones/{publicacion}', [ComunidadController::class, 'destroyPublicacion'])->name('comunidad.publicaciones.destroy');
        Route::post('/comunidad/publicaciones/{publicacion}/me-gusta', [ComunidadController::class, 'toggleMeGusta'])->name('comunidad.publicaciones.toggle-like');
        Route::post('/comunidad/publicaciones/{publicacion}/comentarios', [ComunidadController::class, 'storeComentario'])->name('comunidad.comentarios.store');
        Route::delete('/comunidad/comentarios/{comentario}', [ComunidadController::class, 'destroyComentario'])->name('comunidad.comentarios.destroy');
    });

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/productos', [AdminController::class, 'productos'])->name('productos.index');
        Route::get('/productos/crear', [AdminController::class, 'createProducto'])->name('productos.create');
        Route::post('/productos', [AdminController::class, 'storeProducto'])->name('productos.store');
        Route::get('/productos/{producto}', [AdminController::class, 'showProducto'])->name('productos.show');
        Route::patch('/productos/{producto}', [AdminController::class, 'updateProducto'])->name('productos.update');
        Route::delete('/productos/{producto}', [AdminController::class, 'destroyProducto'])->name('productos.destroy');

        Route::get('/retos', [AdminController::class, 'retos'])->name('retos.index');
        Route::patch('/retos/{reto}', [AdminController::class, 'actualizarEstadoReto'])->name('retos.update');

        Route::get('/validaciones', [AdminController::class, 'validaciones'])->name('validaciones.index');
        Route::patch('/validaciones/{validacion}', [AdminController::class, 'actualizarEstadoValidacion'])->name('validaciones.update');

        Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('usuarios.index');
        Route::patch('/usuarios/{user}', [AdminController::class, 'actualizarBaneoUsuario'])->name('usuarios.update');

        Route::get('/logros', [AdminController::class, 'logros'])->name('logros.index');
        Route::post('/logros', [AdminController::class, 'storeLogro'])->name('logros.store');
        Route::patch('/logros/{logro}', [AdminController::class, 'updateLogro'])->name('logros.update');
        Route::delete('/logros/{logro}', [AdminController::class, 'destroyLogro'])->name('logros.destroy');
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
