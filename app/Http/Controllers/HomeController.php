<?php

namespace App\Http\Controllers;

use App\Models\Reto;
use App\Models\User;
use App\Models\ValidacionReto;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (auth()->user()?->rol === 'admin') {
            return redirect()->route('admin.retos.index');
        }

        Reto::sincronizarCaducados();

        $user = auth()->user();

        $retoDestacado = Reto::query()
            ->where('estado', 'publicado')
            ->orderBy('fecha_fin')
            ->first();

        $retosActivos = Reto::query()
            ->where('estado', 'publicado')
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->count();

        $retosConMapa = Reto::query()
            ->whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->count();

        $retosCompletados = 0;
        $rankingPosicion = null;
        $puntosParaSiguiente = 0;
        $progresoRanking = 0;

        if ($user) {
            $retosCompletados = ValidacionReto::query()
                ->where('user_id', $user->id)
                ->where('estado', 'verificado')
                ->count();

            $usuariosConMasPuntos = User::query()
                ->where('rol', '!=', 'admin')
                ->where('puntos_totales', '>', $user->puntos_totales)
                ->count();

            $rankingPosicion = $usuariosConMasPuntos + 1;

            $siguientePuntaje = User::query()
                ->where('rol', '!=', 'admin')
                ->where('puntos_totales', '>', $user->puntos_totales)
                ->orderBy('puntos_totales')
                ->value('puntos_totales');

            $puntosParaSiguiente = $siguientePuntaje
                ? max(0, (int) $siguientePuntaje - (int) $user->puntos_totales)
                : 0;

            $progresoRanking = $puntosParaSiguiente > 0
                ? max(10, min(95, (int) round(($user->puntos_totales / max(1, (int) $siguientePuntaje)) * 100)))
                : 100;
        }

        return view('home', [
            'retoDestacado' => $retoDestacado,
            'retosActivos' => $retosActivos,
            'retosCompletados' => $retosCompletados,
            'retosConMapa' => $retosConMapa,
            'rankingPosicion' => $rankingPosicion,
            'puntosParaSiguiente' => $puntosParaSiguiente,
            'progresoRanking' => $progresoRanking,
        ]);
    }
}
