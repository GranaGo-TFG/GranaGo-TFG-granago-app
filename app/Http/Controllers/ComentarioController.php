<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ComentarioController extends Controller
{
    public function index(): JsonResponse
    {
        $comentarios = Comentario::with([
            'user:id,nombre,email',
            'validacion:id,reto_id,estado',
        ])->orderByDesc('id')->get();

        return response()->json($comentarios);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'validacion_id' => ['required', 'exists:validaciones_retos,id'],
            'texto' => ['required', 'string'],
            'fecha' => ['nullable', 'date'],
        ]);

        $data['fecha'] = $data['fecha'] ?? Carbon::now()->toDateTimeString();

        $comentario = Comentario::create($data);

        return response()->json($comentario, 201);
    }

    public function show(Comentario $comentario): JsonResponse
    {
        $comentario->load([
            'user:id,nombre,email',
            'validacion.reto:id,nombre',
        ]);

        return response()->json($comentario);
    }

    public function update(Request $request, Comentario $comentario): JsonResponse
    {
        $data = $request->validate([
            'texto' => ['sometimes', 'string'],
            'fecha' => ['sometimes', 'date'],
        ]);

        $comentario->update($data);

        return response()->json($comentario->fresh());
    }

    public function destroy(Comentario $comentario): JsonResponse
    {
        $comentario->delete();

        return response()->json(null, 204);
    }
}
