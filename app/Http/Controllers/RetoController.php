<?php

namespace App\Http\Controllers;

use App\Models\Reto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RetoController extends Controller
{
    public function indexView(): View
    {
        $retos = Reto::query()
            ->where('estado', '!=', 'borrador')
            ->orderByDesc('fecha_inicio')
            ->orderByDesc('id')
            ->get();

        return view('vistas.retos', compact('retos'));
    }

    public function createView(): View
    {
        abort_if(Auth::user()?->rol !== 'creador', 403);

        return view('vistas.crear-reto');
    }

    public function showView(Reto $reto): View
    {
        abort_if($reto->estado === 'borrador', 404);

        return view('vistas.reto-detalle', compact('reto'));
    }

    public function storeView(Request $request): RedirectResponse
    {
        abort_if(Auth::user()?->rol !== 'creador', 403);

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['required', 'string'],
            'archivo_multimedia' => ['nullable', 'string', 'max:255'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'estado' => ['required', 'in:borrador,publicado,caducado'],
            'puntos_recompensa' => ['required', 'integer', 'min:0'],
        ]);

        Reto::create([
            ...$data,
            'creador_id' => Auth::id(),
        ]);

        return redirect()
            ->route('vistas.retos')
            ->with('status', 'Proyecto creado correctamente.');
    }

    public function index(): JsonResponse
    {
        $this->asegurarAdministrador();

        $retos = Reto::with('creador:id,nombre,email')
            ->orderByDesc('id')
            ->get();

        return response()->json($retos);
    }

    public function mapaData(Request $request): JsonResponse
    {
        $filtros = $request->validate([
            'latMin' => ['nullable', 'numeric', 'between:-90,90'],
            'latMax' => ['nullable', 'numeric', 'between:-90,90'],
            'longMin' => ['nullable', 'numeric', 'between:-180,180'],
            'longMax' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $query = Reto::query()
            ->where('estado', 'publicado')
            ->whereNotNull('latitud')
            ->whereNotNull('longitud')
            ->orderBy('fecha_fin')
            ->select([
                'id',
                'nombre',
                'descripcion',
                'estado',
                'puntos_recompensa',
                'ubicacion_referencia',
                'latitud',
                'longitud',
                'fecha_inicio',
                'fecha_fin',
            ]);

        if (
            isset($filtros['latMin'], $filtros['latMax'], $filtros['longMin'], $filtros['longMax'])
        ) {
            $latMin = min((float) $filtros['latMin'], (float) $filtros['latMax']);
            $latMax = max((float) $filtros['latMin'], (float) $filtros['latMax']);
            $longMin = min((float) $filtros['longMin'], (float) $filtros['longMax']);
            $longMax = max((float) $filtros['longMin'], (float) $filtros['longMax']);

            $query
                ->whereBetween('latitud', [$latMin, $latMax])
                ->whereBetween('longitud', [$longMin, $longMax]);
        }

        $retos = $query->get()->map(function (Reto $reto) {
            return [
                'nr' => $reto->id,
                'nm' => $reto->nombre,
                'ds' => Str::limit($reto->descripcion, 150),
                'st' => $reto->estado,
                'pnt' => (int) $reto->puntos_recompensa,
                'ref' => $reto->ubicacion_referencia,
                'lt' => (float) $reto->latitud,
                'lg' => (float) $reto->longitud,
                'inicio' => optional($reto->fecha_inicio)->toDateTimeString(),
                'fin' => optional($reto->fecha_fin)->toDateTimeString(),
                'url' => route('vistas.reto-detalle', $reto),
            ];
        })->values();

        return response()->json($retos);
    }

    public function store(Request $request): JsonResponse
    {
        $this->asegurarAdministrador();

        $data = $request->validate([
            'creador_id' => ['required', 'exists:users,id'],
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['required', 'string'],
            'ubicacion_referencia' => ['nullable', 'string', 'max:120'],
            'archivo_multimedia' => ['nullable', 'string', 'max:255'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'estado' => ['nullable', 'in:borrador,publicado,caducado'],
            'puntos_recompensa' => ['required', 'integer', 'min:0'],
            'latitud' => ['nullable', 'numeric', 'between:-90,90', 'required_with:longitud'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180', 'required_with:latitud'],
        ]);

        $reto = Reto::create($data);

        return response()->json($reto, 201);
    }

    public function show(Reto $reto): JsonResponse
    {
        $this->asegurarAdministrador();

        $reto->load([
            'creador:id,nombre,email',
            'validaciones.user:id,nombre,email',
        ]);

        return response()->json($reto);
    }

    public function update(Request $request, Reto $reto): JsonResponse
    {
        $this->asegurarAdministrador();

        $data = $request->validate([
            'creador_id' => ['sometimes', 'exists:users,id'],
            'nombre' => ['sometimes', 'string', 'max:100'],
            'descripcion' => ['sometimes', 'string'],
            'ubicacion_referencia' => ['sometimes', 'nullable', 'string', 'max:120'],
            'archivo_multimedia' => ['nullable', 'string', 'max:255'],
            'fecha_inicio' => ['sometimes', 'date'],
            'fecha_fin' => ['sometimes', 'date'],
            'estado' => ['sometimes', 'in:borrador,publicado,caducado'],
            'puntos_recompensa' => ['sometimes', 'integer', 'min:0'],
            'latitud' => ['sometimes', 'nullable', 'numeric', 'between:-90,90', 'required_with:longitud'],
            'longitud' => ['sometimes', 'nullable', 'numeric', 'between:-180,180', 'required_with:latitud'],
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
        $this->asegurarAdministrador();

        $reto->delete();

        return response()->json(null, 204);
    }

    private function asegurarAdministrador(): void
    {
        abort_if(Auth::user()?->rol !== 'admin', 403);
    }
}
