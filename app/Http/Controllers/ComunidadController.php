<?php

namespace App\Http\Controllers;

use App\Models\PublicacionComunidad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
                    ->orderBy('fecha_comentario'),
            ])
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
            ->route('vistas.comunidad')
            ->with('status', 'Publicacion creada correctamente.');
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

        $parametros = [];

        if (isset($data['page'])) {
            $parametros['page'] = (int) $data['page'];
        }

        return redirect()
            ->route('vistas.comunidad', $parametros)
            ->withFragment('publicacion-'.$publicacion->id)
            ->with('status', 'Comentario publicado correctamente.');
    }
}
