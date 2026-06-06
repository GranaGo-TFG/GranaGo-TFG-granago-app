<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class RankingController extends Controller
{
    public function index(): View
    {
        $usuariosRanking = User::query()
            ->select(['id', 'nombre', 'nickname', 'rol', 'puntos_totales'])
            ->where('rol', 'usuario')
            ->where('esta_baneado', false)
            ->orderByDesc('puntos_totales')
            ->orderByRaw('COALESCE(nickname, nombre)')
            ->get()
            ->values()
            ->map(function (User $user, int $index) {
                $user->posicion_ranking = $index + 1;

                return $user;
            });

        return view('vistas.ranking', [
            'usuariosRanking' => $usuariosRanking,
        ]);
    }
}
