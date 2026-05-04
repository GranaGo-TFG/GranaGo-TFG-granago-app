<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ValidacionReto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ValidacionRetoController extends Controller
{
    public function index(): JsonResponse
    {
        $validaciones = ValidacionReto::with([
            'user:id,nombre,email',
            'reto:id,nombre,estado',
        ])->orderByDesc('id')->get();

        return response()->json($validaciones);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'reto_id' => ['required', 'exists:retos,id'],
            'foto_prueba' => ['required', 'string', 'max:255'],
            'estado' => ['nullable', 'in:pendiente,verificado,rechazado'],
            'fecha_envio' => ['nullable', 'date'],
        ]);

        $data['fecha_envio'] = $data['fecha_envio'] ?? Carbon::now()->toDateTimeString();

        $validacion = DB::transaction(function () use ($data) {
            $validacion = ValidacionReto::create($data);

            $this->sumarPuntosSiCorresponde(
                $validacion,
                'pendiente',
                $data['estado'] ?? 'pendiente'
            );

            return $validacion;
        });

        return response()->json($validacion, 201);
    }

    public function show(ValidacionReto $validaciones_reto): JsonResponse
    {
        $validaciones_reto->load([
            'user:id,nombre,email',
            'reto:id,nombre,estado,puntos_recompensa',
            'comentarios.user:id,nombre,email',
        ]);

        return response()->json($validaciones_reto);
    }

    public function update(Request $request, ValidacionReto $validaciones_reto): JsonResponse
    {
        $data = $request->validate([
            'user_id' => ['sometimes', 'exists:users,id'],
            'reto_id' => ['sometimes', 'exists:retos,id'],
            'foto_prueba' => ['sometimes', 'string', 'max:255'],
            'estado' => ['sometimes', 'in:pendiente,verificado,rechazado'],
            'fecha_envio' => ['sometimes', 'date'],
        ]);

        return DB::transaction(function () use ($data, $validaciones_reto) {
            $validacion = ValidacionReto::query()
                ->lockForUpdate()
                ->findOrFail($validaciones_reto->id);

            $estadoAnterior = $validacion->estado;
            $estadoNuevo = $data['estado'] ?? $estadoAnterior;

            if ($estadoAnterior === 'verificado' && $estadoNuevo !== 'verificado') {
                return response()->json([
                    'message' => 'No se puede cambiar el estado de una validacion ya verificada.',
                ], 422);
            }

            $validacion->update($data);

            $this->sumarPuntosSiCorresponde($validacion, $estadoAnterior, $estadoNuevo);

            return response()->json($validacion->fresh());
        });
    }

    public function destroy(ValidacionReto $validaciones_reto): JsonResponse
    {
        $validaciones_reto->delete();

        return response()->json(null, 204);
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
