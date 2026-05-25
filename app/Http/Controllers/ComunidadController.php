<?php

namespace App\Http\Controllers;

use App\Models\ComentarioComunidad;
use App\Models\PublicacionComunidad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ComunidadController extends Controller
{
    public function index(): View
    {
        $publicaciones = PublicacionComunidad::query()
            ->with([
                'user:id,nombre',
                'comentarios' => fn ($query) => $query
                    ->with('user:id,nombre')
                    ->orderByDesc('fecha_comentario'),
                'meGustaUsuarios' => fn ($query) => $query->orderBy('users.nombre'),
            ])
            ->withCount('meGustaUsuarios')
            ->orderByDesc('fecha_publicacion')
            ->paginate(8);

        return view('vistas.comunidad', [
            'publicaciones' => $publicaciones,
        ]);
    }

    public function storePublicacion(Request $request): RedirectResponse
    {
        $contenidoLimpio = trim((string) $request->input('contenido', ''));

        $request->merge([
            'contenido' => $contenidoLimpio !== '' ? $contenidoLimpio : null,
        ]);

        $data = $request->validate([
            'contenido' => ['nullable', 'string', 'max:3000', 'required_without:imagen'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120', 'required_without:contenido'],
        ], [
            'contenido.required_without' => 'Escribe un texto o sube una imagen para publicar.',
            'contenido.max' => 'El texto no puede superar los 3000 caracteres.',
            'imagen.required_without' => 'Sube una imagen o escribe un texto para publicar.',
            'imagen.image' => 'El archivo subido debe ser una imagen valida.',
            'imagen.mimes' => 'La imagen debe estar en formato JPG, JPEG, PNG o WEBP.',
            'imagen.max' => 'La imagen no puede superar los 5 MB.',
        ]);

        $rutaImagen = null;

        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('comunidad/publicaciones', 'public');
        }

        PublicacionComunidad::create([
            'user_id' => (int) $request->user()->id,
            'contenido' => $data['contenido'] ?? null,
            'imagen' => $rutaImagen,
            'fecha_publicacion' => Carbon::now()->toDateTimeString(),
        ]);

        return redirect()
            ->route('vistas.comunidad');
    }

    public function updatePublicacion(Request $request, PublicacionComunidad $publicacion): RedirectResponse
    {
        $this->autorizarGestion($request, (int) $publicacion->user_id);

        $contenidoLimpio = trim((string) $request->input('contenido', ''));

        $request->merge([
            'contenido' => $contenidoLimpio !== '' ? $contenidoLimpio : null,
        ]);

        $data = $request->validate([
            'contenido' => ['nullable', 'string', 'max:3000'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'eliminar_imagen' => ['nullable', 'boolean'],
            'page' => ['nullable', 'integer', 'min:1'],
        ], [
            'contenido.max' => 'El texto no puede superar los 3000 caracteres.',
            'imagen.image' => 'El archivo subido debe ser una imagen valida.',
            'imagen.mimes' => 'La imagen debe estar en formato JPG, JPEG, PNG o WEBP.',
            'imagen.max' => 'La imagen no puede superar los 5 MB.',
        ]);

        $rutaImagenOriginal = $publicacion->imagen;
        $rutaImagenFinal = $rutaImagenOriginal;

        if ((bool) ($data['eliminar_imagen'] ?? false)) {
            $rutaImagenFinal = null;
        }

        if ($request->hasFile('imagen')) {
            $rutaImagenFinal = $request->file('imagen')->store('comunidad/publicaciones', 'public');
        }

        if ($rutaImagenFinal !== $rutaImagenOriginal) {
            $this->eliminarImagenSiEsLocal($rutaImagenOriginal);
        }

        $contenidoFinal = $data['contenido'] ?? null;

        if ($contenidoFinal === null && $rutaImagenFinal === null) {
            return redirect()
                ->route('vistas.comunidad', $this->parametrosPaginacion($data))
                ->withFragment('publicacion-'.$publicacion->id)
                ->withErrors([
                    'contenido' => 'La publicacion debe tener texto o imagen.',
                ])
                ->withInput();
        }

        $publicacion->update([
            'contenido' => $contenidoFinal,
            'imagen' => $rutaImagenFinal,
        ]);

        return redirect()
            ->route('vistas.comunidad', $this->parametrosPaginacion($data))
            ->withFragment('publicacion-'.$publicacion->id)
            ->with('status', 'Publicacion actualizada correctamente.');
    }

    public function destroyPublicacion(Request $request, PublicacionComunidad $publicacion): RedirectResponse
    {
        $this->autorizarGestion($request, (int) $publicacion->user_id);

        $data = $request->validate([
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $this->eliminarImagenSiEsLocal($publicacion->imagen);
        $publicacion->delete();

        return redirect()
            ->route('vistas.comunidad', $this->parametrosPaginacion($data));
    }

    public function toggleMeGusta(Request $request, PublicacionComunidad $publicacion): RedirectResponse
    {
        $data = $request->validate([
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $userId = (int) $request->user()->id;

        $yaExiste = $publicacion->meGustaUsuarios()
            ->where('users.id', $userId)
            ->exists();

        if ($yaExiste) {
            $publicacion->meGustaUsuarios()->detach($userId);
        } else {
            $publicacion->meGustaUsuarios()->syncWithoutDetaching([
                $userId => [
                    'fecha_reaccion' => Carbon::now()->toDateTimeString(),
                ],
            ]);
        }

        return redirect()
            ->route('vistas.comunidad', $this->parametrosPaginacion($data))
            ->withFragment('publicacion-'.$publicacion->id);
    }

    public function storeComentario(Request $request, PublicacionComunidad $publicacion): RedirectResponse
    {
        $comentarioLimpio = trim((string) $request->input('comentario', ''));

        $request->merge([
            'comentario' => $comentarioLimpio,
        ]);

        $data = $request->validate([
            'comentario' => ['required', 'string', 'max:1000'],
            'page' => ['nullable', 'integer', 'min:1'],
        ], [
            'comentario.required' => 'Escribe un comentario para publicar.',
            'comentario.max' => 'El comentario no puede superar los 1000 caracteres.',
        ]);

        $publicacion->comentarios()->create([
            'user_id' => (int) $request->user()->id,
            'contenido' => $data['comentario'],
            'fecha_comentario' => Carbon::now()->toDateTimeString(),
        ]);

        return redirect()
            ->route('vistas.comunidad', $this->parametrosPaginacion($data))
            ->withFragment('publicacion-'.$publicacion->id);
    }

    public function destroyComentario(Request $request, ComentarioComunidad $comentario): RedirectResponse
    {
        $this->autorizarGestion($request, (int) $comentario->user_id);

        $data = $request->validate([
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $publicacionId = (int) $comentario->publicacion_id;
        $comentario->delete();

        return redirect()
            ->route('vistas.comunidad', $this->parametrosPaginacion($data))
            ->withFragment('publicacion-'.$publicacionId)
            ->with('status', 'Comentario eliminado correctamente.');
    }

    private function autorizarGestion(Request $request, int $autorId): void
    {
        $esAdmin = $request->user()->rol === 'admin';
        $esAutor = (int) $request->user()->id === $autorId;

        abort_unless($esAdmin || $esAutor, 403, 'No tienes permisos para esta accion.');
    }

    private function parametrosPaginacion(array $data): array
    {
        if (! isset($data['page'])) {
            return [];
        }

        return [
            'page' => (int) $data['page'],
        ];
    }

    private function eliminarImagenSiEsLocal(?string $rutaImagen): void
    {
        if (! $rutaImagen || filter_var($rutaImagen, FILTER_VALIDATE_URL)) {
            return;
        }

        Storage::disk('public')->delete($rutaImagen);
    }
}
