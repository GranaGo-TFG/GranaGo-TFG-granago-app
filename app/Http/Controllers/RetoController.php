<?php

namespace App\Http\Controllers;

use App\Models\Reto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RetoController extends Controller
{
    public function indexView(): View
    {
        $retos = Reto::query()
            ->orderByDesc('fecha_inicio')
            ->orderByDesc('id')
            ->get();

        return view('vistas.retos', compact('retos'));
    }

    public function showView(Reto $reto): View
    {
        return view('vistas.reto-detalle', compact('reto'));
    }

    public function index(): JsonResponse
    {
        $retos = Reto::with('creador:id,nombre,email')
            ->orderByDesc('id')
            ->get();

        return response()->json($retos);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'creador_id' => ['required', 'exists:users,id'],
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['required', 'string'],
            'archivo_multimedia' => ['nullable', 'string', 'max:255'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'estado' => ['nullable', 'in:borrador,publicado,caducado'],
            'puntos_recompensa' => ['required', 'integer', 'min:0'],
        ]);

        $reto = Reto::create($data);

        return response()->json($reto, 201);
    }

    public function show(Reto $reto): JsonResponse
    {
        $reto->load([
            'creador:id,nombre,email',
            'validaciones.user:id,nombre,email',
        ]);

        return response()->json($reto);
    }

    public function update(Request $request, Reto $reto): JsonResponse
    {
        $data = $request->validate([
            'creador_id' => ['sometimes', 'exists:users,id'],
            'nombre' => ['sometimes', 'string', 'max:100'],
            'descripcion' => ['sometimes', 'string'],
            'archivo_multimedia' => ['nullable', 'string', 'max:255'],
            'fecha_inicio' => ['sometimes', 'date'],
            'fecha_fin' => ['sometimes', 'date'],
            'estado' => ['sometimes', 'in:borrador,publicado,caducado'],
            'puntos_recompensa' => ['sometimes', 'integer', 'min:0'],
        ]);

        $fechaInicio = $data['fecha_inicio'] ?? $reto->fecha_inicio?->toDateTimeString();
        $fechaFin = $data['fecha_fin'] ?? $reto->fecha_fin?->toDateTimeString();

        if ($fechaInicio && $fechaFin && strtotime($fechaFin) < strtotime($fechaInicio)) {
            return response()->json([
                'message' => 'La fecha_fin debe ser mayor o igual a fecha_inicio.',
            ], 422);
        }

        $reto->update($data);

        return response()->json($reto->fresh());
    }

    public function destroy(Reto $reto): JsonResponse
    {
        $reto->delete();

        return response()->json(null, 204);
    }
}
