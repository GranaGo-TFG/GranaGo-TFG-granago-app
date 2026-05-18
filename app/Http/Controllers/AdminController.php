<?php

namespace App\Http\Controllers;

use App\Models\Reto;
use App\Models\User;
use App\Models\ValidacionReto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function retos(): View
    {
        $retos = Reto::with('creador:id,nombre,email')
            ->orderByDesc('id')
            ->get();

        return view('admin.retos', compact('retos'));
    }

    public function actualizarEstadoReto(Request $request, Reto $reto): RedirectResponse
    {
        $data = $request->validate([
            'estado' => ['required', 'in:borrador,publicado,caducado'],
        ]);

        $reto->update($data);

        return back()->with('status', 'Estado del reto actualizado.');
    }

    public function validaciones(): View
    {
        $validaciones = ValidacionReto::with([
            'user:id,nombre,email,esta_baneado',
            'reto:id,nombre,estado,puntos_recompensa',
        ])->orderByDesc('id')->get();

        return view('admin.validaciones', compact('validaciones'));
    }

    public function actualizarEstadoValidacion(Request $request, ValidacionReto $validacion): RedirectResponse
    {
        $data = $request->validate([
            'estado' => ['required', 'in:pendiente,verificado,rechazado'],
        ]);

        $resultado = DB::transaction(function () use ($data, $validacion) {
            $validacion = ValidacionReto::query()
                ->lockForUpdate()
                ->findOrFail($validacion->id);

            $estadoAnterior = $validacion->estado;
            $estadoNuevo = $data['estado'];

            if ($estadoAnterior === 'verificado' && $estadoNuevo !== 'verificado') {
                return 'No se puede cambiar el estado de una validacion ya verificada.';
            }

            $validacion->update($data);

            $this->sumarPuntosSiCorresponde($validacion, $estadoAnterior, $estadoNuevo);

            return null;
        });

        if ($resultado) {
            return back()->withErrors(['estado' => $resultado]);
        }

        return back()->with('status', 'Estado de la validacion actualizado.');
    }

    public function usuarios(): View
    {
        $usuarios = User::query()
            ->orderByRaw("CASE WHEN rol = 'admin' THEN 0 ELSE 1 END")
            ->orderBy('nombre')
            ->get();

        return view('admin.usuarios', compact('usuarios'));
    }

    public function actualizarBaneoUsuario(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'esta_baneado' => ['required', 'boolean'],
        ]);

        if ($user->rol === 'admin') {
            return back()->withErrors([
                'usuarios' => 'No se puede banear a un administrador.',
            ]);
        }

        $user->update([
            'esta_baneado' => (bool) $data['esta_baneado'],
        ]);

        if ($user->is(Auth::user())) {
            Auth::logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Tu cuenta ha sido bloqueada.',
                ]);
        }

        return back()->with('status', $user->esta_baneado ? 'Usuario baneado.' : 'Usuario desbloqueado.');
    }

    private function sumarPuntosSiCorresponde(
        ValidacionReto $validacion,
        string $estadoAnterior,
        string $estadoNuevo
    ): void {
        if ($estadoNuevo !== 'verificado' || $estadoAnterior === 'verificado') {
            return;
        }

        $user = User::query()->lockForUpdate()->findOrFail($validacion->user_id);
        $validacion->loadMissing('reto:id,puntos_recompensa');

        $puntosBase = (int) $validacion->reto->puntos_recompensa;
        $multiplicador = max(0, (float) $user->racha_multiplicador);
        $puntosGanados = (int) round($puntosBase * $multiplicador);

        if ($puntosGanados > 0) {
            $user->increment('puntos_totales', $puntosGanados);
        }
    }
}
