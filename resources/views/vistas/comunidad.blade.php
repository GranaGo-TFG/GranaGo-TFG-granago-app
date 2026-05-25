@extends('layouts.app')

@section('content')
<div class="screen-page community-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <h1 class="home-kicker">Comunidad</h1>
                <h2>Muro de la comunidad</h2>
                <p>Publica texto o foto y comenta lo que comparte el resto de usuarios.</p>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success home-alert" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Revisa el formulario.</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="community-layout">
            <article class="community-composer">
                <h3>Crear publicacion</h3>
                <form
                    action="{{ route('vistas.comunidad.publicaciones.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="community-composer-form"
                >
                    @csrf

                    <label for="community-content" class="form-label">Que quieres compartir</label>
                    <textarea
                        id="community-content"
                        name="contenido"
                        rows="5"
                        class="form-control"
                        placeholder="Escribe una historia, una recomendacion o lo que quieras mostrar..."
                    >{{ old('contenido') }}</textarea>

                    <div class="community-composer-actions">
                        <label for="community-image" class="community-file-label">
                            <span>Anadir foto (opcional)</span>
                            <input
                                id="community-image"
                                type="file"
                                name="imagen"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                class="form-control"
                            >
                        </label>
                        <button type="submit" class="btn btn-primary home-btn">Publicar</button>
                    </div>

                    <p class="community-composer-note">Puedes publicar solo texto, solo foto o ambos.</p>
                </form>
            </article>

            <div class="community-feed">
                @forelse ($publicaciones as $publicacion)
                    @php
                        $urlImagen = null;

                        if ($publicacion->imagen) {
                            $urlImagen = \Illuminate\Support\Str::startsWith($publicacion->imagen, ['http://', 'https://'])
                                ? $publicacion->imagen
                                : \Illuminate\Support\Facades\Storage::url($publicacion->imagen);
                        }

                        $inicialUsuario = \Illuminate\Support\Str::upper(
                            \Illuminate\Support\Str::substr((string) $publicacion->user->nombre, 0, 1)
                        );
                    @endphp

                    <article class="community-post" id="publicacion-{{ $publicacion->id }}">
                        <header class="community-post-head">
                            <div class="community-avatar" aria-hidden="true">{{ $inicialUsuario }}</div>
                            <div>
                                <strong>{{ $publicacion->user->nombre }}</strong>
                                <time datetime="{{ optional($publicacion->fecha_publicacion)->toIso8601String() }}">
                                    {{ optional($publicacion->fecha_publicacion)->format('d/m/Y H:i') }}
                                </time>
                            </div>
                        </header>

                        @if ($publicacion->contenido)
                            <p class="community-post-content">{!! nl2br(e($publicacion->contenido)) !!}</p>
                        @endif

                        @if ($urlImagen)
                            <figure class="community-post-image">
                                <img src="{{ $urlImagen }}" alt="Imagen publicada por {{ $publicacion->user->nombre }}">
                            </figure>
                        @endif

                        <section class="community-comments">
                            <h4>Comentarios ({{ $publicacion->comentarios->count() }})</h4>

                            @forelse ($publicacion->comentarios as $comentario)
                                <article class="community-comment">
                                    <p>
                                        <strong>{{ $comentario->user->nombre }}</strong>
                                        <span>{{ $comentario->contenido }}</span>
                                    </p>
                                    <time datetime="{{ optional($comentario->fecha_comentario)->toIso8601String() }}">
                                        {{ optional($comentario->fecha_comentario)->format('d/m/Y H:i') }}
                                    </time>
                                </article>
                            @empty
                                <p class="community-empty-comments">Todavia no hay comentarios. Se el primero.</p>
                            @endforelse
                        </section>

                        <form
                            action="{{ route('vistas.comunidad.comentarios.store', $publicacion) }}"
                            method="POST"
                            class="community-comment-form"
                        >
                            @csrf
                            <input type="hidden" name="page" value="{{ $publicaciones->currentPage() }}">
                            <label for="comentario-{{ $publicacion->id }}" class="visually-hidden">Escribe un comentario</label>
                            <textarea
                                id="comentario-{{ $publicacion->id }}"
                                name="comentario"
                                rows="2"
                                class="form-control"
                                placeholder="Escribe un comentario..."
                                required
                            ></textarea>
                            <button type="submit" class="btn btn-outline-primary home-btn">Comentar</button>
                        </form>
                    </article>
                @empty
                    <article class="home-panel community-empty-state">
                        <h3>El muro esta vacio</h3>
                        <p>Publica la primera entrada para iniciar la conversacion de la comunidad.</p>
                    </article>
                @endforelse
            </div>
        </section>

        @if ($publicaciones->hasPages())
            <div class="community-pagination">
                {{ $publicaciones->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
