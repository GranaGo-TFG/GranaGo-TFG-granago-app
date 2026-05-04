<?php

namespace App\Http\Controllers;

use App\Models\Logro;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LogroController extends Controller
{
    public function index(): JsonResponse
    {
        $logros = Logro::orderByDesc('id')->get();

        return response()->json($logros);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre_logro' => ['required', 'string', 'max:100'],
            'descripcion' => ['required', 'string'],
            'icono' => ['required', 'string', 'max:255'],
        ]);

        $logro = Logro::create($data);

        return response()->json($logro, 201);
    }

    public function show(Logro $logro): JsonResponse
    {
        $logro->load('usuarios:id,nombre,email');

        return response()->json($logro);
    }

    public function update(Request $request, Logro $logro): JsonResponse
    {
        $data = $request->validate([
            'nombre_logro' => ['sometimes', 'string', 'max:100'],
            'descripcion' => ['sometimes', 'string'],
            'icono' => ['sometimes', 'string', 'max:255'],
        ]);

        $logro->update($data);

        return response()->json($logro->fresh());
    }

    public function destroy(Logro $logro): JsonResponse
    {
        $logro->delete();

        return response()->json(null, 204);
    }

    public function asignarUsuario(Request $request, Logro $logro): JsonResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'fecha_desbloqueo' => ['nullable', 'date'],
        ]);

        $fechaDesbloqueo = $data['fecha_desbloqueo'] ?? Carbon::now()->toDateTimeString();

        $logro->usuarios()->syncWithoutDetaching([
            $data['user_id'] => ['fecha_desbloqueo' => $fechaDesbloqueo],
        ]);

        return response()->json([
            'message' => 'Logro asignado correctamente.',
        ]);
    }

    public function retirarUsuario(Logro $logro, User $user): JsonResponse
    {
        $logro->usuarios()->detach($user->id);

        return response()->json([
            'message' => 'Logro retirado correctamente.',
        ]);
    }
}
