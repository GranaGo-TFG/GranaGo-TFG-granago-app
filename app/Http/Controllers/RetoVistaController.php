<?php

namespace App\Http\Controllers;

use App\Models\Reto;
use App\Models\ValidacionReto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RetoVistaController extends Controller
{
    public function index(Request $request): View
    {
        $estadosVisibles = ['publicado', 'caducado'];
        $estadoSeleccionado = $request->query('estado', 'todos');
        $ordenSeleccionado = $request->query('orden', 'recientes');
        $busqueda = trim((string) $request->query('buscar', ''));

        if (! in_array($estadoSeleccionado, array_merge(['todos'], $estadosVisibles), true)) {
            $estadoSeleccionado = 'todos';
        }

        if (! in_array($ordenSeleccionado, ['recientes', 'puntos_desc', 'puntos_asc', 'caducan'], true)) {
            $ordenSeleccionado = 'recientes';
        }

        $retos = Reto::query()
            ->with('creador:id,nombre,nickname')
            ->withCount([
                'validaciones',
                'validaciones as validaciones_verificadas_count' => fn ($query) => $query->where('estado', 'verificado'),
            ])
            ->whereIn('estado', $estadosVisibles)
            ->when($estadoSeleccionado !== 'todos', fn ($query) => $query->where('estado', $estadoSeleccionado))
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($query) use ($busqueda) {
                    $query->where('nombre', 'like', "%{$busqueda}%")
                        ->orWhere('descripcion', 'like', "%{$busqueda}%")
                        ->orWhere('ubicacion_referencia', 'like', "%{$busqueda}%");
                });
            })
            ->when($ordenSeleccionado === 'recientes', fn ($query) => $query
                ->orderByRaw("CASE estado WHEN 'publicado' THEN 0 ELSE 1 END")
                ->orderByDesc('fecha_inicio'))
            ->when($ordenSeleccionado === 'puntos_desc', fn ($query) => $query->orderByDesc('puntos_recompensa'))
            ->when($ordenSeleccionado === 'puntos_asc', fn ($query) => $query->orderBy('puntos_recompensa'))
            ->when($ordenSeleccionado === 'caducan', fn ($query) => $query->orderBy('fecha_fin'))
            ->get();

        return view('vistas.retos', [
            'retos' => $retos,
            'estadoSeleccionado' => $estadoSeleccionado,
            'ordenSeleccionado' => $ordenSeleccionado,
            'busqueda' => $busqueda,
        ]);
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
            'titulo_relato' => ['nullable', 'string', 'max:255'],
            'leyenda_relato' => ['nullable', 'string'],
            'contenido_relato' => ['nullable', 'string'],
            'cierre_relato' => ['nullable', 'string'],
        ]);

        $data['creador_id'] = (int) $request->user()->id;
        $data['estado'] = 'borrador';

        $reto = Reto::create($data);

        return redirect()
            ->route('vistas.reto-detalle', $reto)
            ->with('status', 'Reto creado correctamente.');
    }

    public function show(Reto $reto): View
    {
        $reto->load('creador:id,nombre,nickname');
        $reto->loadCount([
            'validaciones',
            'validaciones as validaciones_verificadas_count' => fn ($query) => $query->where('estado', 'verificado'),
            'validaciones as validaciones_pendientes_count' => fn ($query) => $query->where('estado', 'pendiente'),
        ]);

        $validacionesRecientes = ValidacionReto::query()
            ->with('user:id,nombre,nickname')
            ->where('reto_id', $reto->id)
            ->latest('fecha_envio')
            ->limit(3)
            ->get();

        return view('vistas.reto-detalle', [
            'reto' => $reto,
            'validacionesRecientes' => $validacionesRecientes,
        ]);
    }

    private function autorizarCreacion(): void
    {
        abort_unless(Auth::user()?->rol === 'creador', 403, 'Solo los usuarios creadores pueden crear retos.');
    }
}
