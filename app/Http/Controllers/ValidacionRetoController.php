<?php

namespace App\Http\Controllers;

use App\Models\Reto;
use App\Models\User;
use App\Models\ValidacionReto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ValidacionRetoController extends Controller
{
    public function create(Reto $reto): View
    {
        abort_if($reto->estado === 'caducado', 403, 'No se pueden subir pruebas a retos caducados.');
        abort_if(auth()->user()?->rol === 'creador', 403, 'Los creadores no pueden participar en retos.');

        return view('vistas.subir-prueba', [
            'reto' => $reto,
        ]);
    }

    public function index(): JsonResponse
    {
        $this->asegurarAdministrador();

        $validaciones = ValidacionReto::with([
            'user:id,nombre,email',
            'reto:id,nombre,estado',
        ])->orderByDesc('id')->get();

        return response()->json($validaciones);
    }

    public function store(Request $request): JsonResponse
    {
        $this->asegurarAdministrador();

        abort_if($request->user()?->rol === 'creador', 403, 'Los creadores no pueden participar en retos.');

        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'reto_id' => ['required', 'exists:retos,id'],
            'foto_prueba' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'estado' => ['nullable', 'in:pendiente,verificado,rechazado'],
            'fecha_envio' => ['nullable', 'date'],
        ]);

        $this->asegurarQuePuedeEnviar(
            (int) $data['user_id'],
            (int) $data['reto_id'],
            'user_id'
        );

        $data['foto_prueba'] = $request->file('foto_prueba')->store('validaciones', 'public');
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

    public function storeFromView(Request $request, Reto $reto): RedirectResponse
    {
        abort_if($reto->estado === 'caducado', 403, 'No se pueden subir pruebas a retos caducados.');
        abort_if($request->user()?->rol === 'creador', 403, 'Los creadores no pueden participar en retos.');

        $request->validate([
            'foto_prueba' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'foto_prueba.required' => 'Selecciona una imagen para enviar la prueba.',
            'foto_prueba.image' => 'El archivo enviado debe ser una imagen valida.',
            'foto_prueba.mimes' => 'La imagen debe estar en formato JPG, JPEG, PNG o WEBP.',
            'foto_prueba.max' => 'La imagen no puede superar los 5 MB.',
        ]);

        $this->asegurarQuePuedeEnviar((int) $request->user()->id, (int) $reto->id);

        $rutaImagen = $request->file('foto_prueba')->store('validaciones', 'public');

        ValidacionReto::create([
            'user_id' => (int) $request->user()->id,
            'reto_id' => (int) $reto->id,
            'foto_prueba' => $rutaImagen,
            'estado' => 'pendiente',
            'fecha_envio' => Carbon::now()->toDateTimeString(),
        ]);

        return redirect()
            ->route('vistas.reto-detalle', $reto)
            ->with('status', 'Prueba subida correctamente. La revision queda pendiente.');
    }

    public function show(ValidacionReto $validaciones_reto): JsonResponse
    {
        $this->asegurarAdministrador();

        $validaciones_reto->load([
            'user:id,nombre,email',
            'reto:id,nombre,estado,puntos_recompensa',
            'comentarios.user:id,nombre,email',
        ]);

        return response()->json($validaciones_reto);
    }

    public function update(Request $request, ValidacionReto $validaciones_reto): JsonResponse
    {
        $this->asegurarAdministrador();

        $data = $request->validate([
            'user_id' => ['sometimes', 'exists:users,id'],
            'reto_id' => ['sometimes', 'exists:retos,id'],
            'foto_prueba' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'estado' => ['sometimes', 'in:pendiente,verificado,rechazado'],
            'fecha_envio' => ['sometimes', 'date'],
        ]);

        if ($request->hasFile('foto_prueba')) {
            $data['foto_prueba'] = $request->file('foto_prueba')->store('validaciones', 'public');
        }

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
        $this->asegurarAdministrador();

        if ($validaciones_reto->foto_prueba && ! filter_var($validaciones_reto->foto_prueba, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($validaciones_reto->foto_prueba);
        }

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

    private function asegurarQuePuedeEnviar(int $userId, int $retoId, string $campoError = 'foto_prueba'): void
    {
        $user = User::query()->findOrFail($userId);

        if ($user->rol === 'creador') {
            throw ValidationException::withMessages([
                $campoError => 'Los creadores no pueden participar en retos.',
            ]);
        }

        $yaExiste = ValidacionReto::query()
            ->where('user_id', $userId)
            ->where('reto_id', $retoId)
            ->whereIn('estado', ['pendiente', 'verificado'])
            ->exists();

        if (! $yaExiste) {
            return;
        }

        throw ValidationException::withMessages([
            $campoError => 'Ya tienes una prueba pendiente o verificada para este reto.',
        ]);
    }

    private function asegurarAdministrador(): void
    {
        abort_if(auth()->user()?->rol !== 'admin', 403);
    }
}
