<?php

namespace App\Http\Controllers;

use App\Models\Reto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RetoVistaController extends Controller
{
    public function index(): View
    {
        $retos = Reto::query()
            ->with('creador:id,nombre')
            ->withCount([
                'validaciones',
                'validaciones as validaciones_verificadas_count' => fn ($query) => $query->where('estado', 'verificado'),
            ])
            ->orderByRaw("CASE estado WHEN 'publicado' THEN 0 WHEN 'borrador' THEN 1 ELSE 2 END")
            ->orderByDesc('fecha_inicio')
            ->get();

        return view('vistas.retos', compact('retos'));
    }

    public function create(): View
    {
        $this->autorizarCreacion();

        return view('vistas.crear-reto');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->autorizarCreacion();

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['required', 'string'],
            'archivo_multimedia' => ['nullable', 'string', 'max:255'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'puntos_recompensa' => ['required', 'integer', 'min:0'],
            'ubicacion_referencia' => ['nullable', 'string', 'max:120'],
            'latitud' => ['required', 'numeric', 'between:-90,90'],
            'longitud' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $data['creador_id'] = (int) $request->user()->id;
        $data['estado'] = 'borrador';

        $reto = Reto::create($data);

        return redirect()
            ->route('vistas.retos')
            ->with('status', 'Reto creado correctamente.');
    }

    private function autorizarCreacion(): void
    {
        abort_unless(auth()->user()?->rol === 'creador', 403, 'Solo los usuarios creadores pueden crear retos.');
    }
}
