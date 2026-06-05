<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Reto;
use App\Models\User;
use App\Models\ValidacionReto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function retos(Request $request): View
    {
        $estadosReto = ['todos', 'borrador', 'publicado', 'caducado'];
        $estadoSeleccionado = $request->query('estado', 'todos');

        if (! in_array($estadoSeleccionado, $estadosReto, true)) {
            $estadoSeleccionado = 'todos';
        }

        $retos = Reto::with('creador:id,nombre,email')
            ->when($estadoSeleccionado !== 'todos', fn ($query) => $query->where('estado', $estadoSeleccionado))
            ->orderByDesc('id')
            ->get();

        return view('admin.retos', [
            'retos' => $retos,
            'estadoSeleccionado' => $estadoSeleccionado,
        ]);
    }

    public function actualizarEstadoReto(Request $request, Reto $reto): RedirectResponse
    {
        $data = $request->validate([
            'estado' => ['required', 'in:borrador,publicado,caducado'],
        ]);

        $reto->update($data);

        return back()->with('status', 'Estado del reto actualizado.');
    }

    public function validaciones(Request $request): View
    {
        $estadosValidacion = ['todos', 'pendiente', 'verificado', 'rechazado'];
        $estadoSeleccionado = $request->query('estado', 'todos');

        if (! in_array($estadoSeleccionado, $estadosValidacion, true)) {
            $estadoSeleccionado = 'todos';
        }

        $validaciones = ValidacionReto::with([
            'user:id,nombre,email,esta_baneado',
            'reto:id,nombre,estado,puntos_recompensa',
        ])
            ->when($estadoSeleccionado !== 'todos', fn ($query) => $query->where('estado', $estadoSeleccionado))
            ->orderByDesc('id')
            ->get();

        return view('admin.validaciones', [
            'validaciones' => $validaciones,
            'estadoSeleccionado' => $estadoSeleccionado,
        ]);
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

    public function productos(): View
    {
        $productos = Producto::query()
            ->orderByDesc('created_at')
            ->paginate(12);

        $masVendidos = Producto::query()
            ->orderByDesc('vendidos_total')
            ->orderBy('nombre')
            ->limit(5)
            ->get();

        if ($masVendidos->isEmpty()) {
            $masVendidos = $productos->getCollection()->take(5);
        }

        return view('vistas.tienda', [
            'productos' => $productos,
            'masVendidos' => $masVendidos,
            'modoAdmin' => true,
            'detalleRouteName' => 'admin.productos.show',
            'crearRouteName' => 'admin.productos.create',
        ]);
    }

    public function createProducto(): View
    {
        return view('admin.productos-crear');
    }

    public function showProducto(Producto $producto): View
    {
        $relacionados = Producto::query()
            ->whereKeyNot($producto->id)
            ->where('categoria', $producto->categoria)
            ->orderByDesc('vendidos_total')
            ->limit(3)
            ->get();

        return view('vistas.tienda-producto', [
            'producto' => $producto,
            'relacionados' => $relacionados,
            'modoAdmin' => true,
            'detalleRouteName' => 'admin.productos.show',
            'backRouteName' => 'admin.productos.index',
        ]);
    }

    public function storeProducto(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'categoria' => ['required', 'string', 'max:80'],
            'descripcion_corta' => ['required', 'string', 'max:180'],
            'descripcion' => ['required', 'string'],
            'precio' => ['required', 'numeric', 'min:0', 'max:25'],
            'precio_puntos' => ['required', 'integer', 'min:100', 'max:999999'],
            'stock' => ['required', 'integer', 'min:0', 'max:99999'],
            'imagen_url' => ['nullable', 'url', 'max:255'],
            'activo' => ['required', 'boolean'],
        ]);

        $data['slug'] = $this->generarSlugUnico($data['nombre']);

        $producto = Producto::query()->create($data);

        return redirect()->route('admin.productos.show', $producto);
    }

    public function updateProducto(Request $request, Producto $producto): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'categoria' => ['required', 'string', 'max:80'],
            'descripcion_corta' => ['required', 'string', 'max:180'],
            'descripcion' => ['required', 'string'],
            'precio' => ['required', 'numeric', 'min:0', 'max:25'],
            'precio_puntos' => ['required', 'integer', 'min:100', 'max:999999'],
            'stock' => ['required', 'integer', 'min:0', 'max:99999'],
            'imagen_url' => ['nullable', 'url', 'max:255'],
            'activo' => ['required', 'boolean'],
        ]);

        if ($producto->nombre !== $data['nombre']) {
            $data['slug'] = $this->generarSlugUnico($data['nombre'], $producto->id);
        }

        $producto->update($data);

        return back();
    }

    public function destroyProducto(Producto $producto): RedirectResponse
    {
        $producto->delete();

        return redirect()->route('admin.productos.index');
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

    private function generarSlugUnico(string $nombre, ?int $exceptoProductoId = null): string
    {
        $base = Str::slug($nombre);
        $base = $base !== '' ? $base : 'producto';
        $slug = $base;
        $intento = 2;

        while (true) {
            $query = Producto::query()->where('slug', $slug);

            if (! is_null($exceptoProductoId)) {
                $query->where('id', '!=', $exceptoProductoId);
            }

            if (! $query->exists()) {
                return $slug;
            }

            $slug = $base . '-' . $intento;
            $intento++;
        }
    }
}
