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

        @php
            $usuarioAutenticado = auth()->user();
            $esAdmin = $esAdmin ?? ($usuarioAutenticado->rol === 'admin');
            $filtrosOrden = [
                'recientes' => 'Mas recientes',
                'mas_gustadas' => 'Mas gustadas',
                'mas_comentadas' => 'Mas comentadas',
                'antiguas' => 'Mas antiguas',
            ];
            $filtrosTipo = [
                'todos' => 'Todo el contenido',
                'texto' => 'Solo texto',
                'imagen' => 'Solo imagen',
            ];
        @endphp

        <div class="screen-filters challenge-filter-menu">
            <div class="dropdown">
                <button
                    class="btn btn-outline-secondary challenge-filter-toggle"
                    type="button"
                    id="communityFilterDropdown"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <svg class="challenge-filter-icon" aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                        <path d="M4 5h16l-6.5 7.4v5.1l-3 1.5v-6.6L4 5Z" />
                    </svg>
                    <span>{{ $filtrosOrden[$ordenSeleccionado ?? 'recientes'] ?? 'Mas recientes' }}</span>
                </button>

                <div class="dropdown-menu challenge-filter-dropdown" aria-labelledby="communityFilterDropdown">
                    <span class="challenge-filter-heading">Ordenar</span>
                    @foreach ($filtrosOrden as $orden => $label)
                        @php
                            $paramsOrden = array_filter([
                                'orden' => $orden,
                                'tipo' => ($tipoSeleccionado ?? 'todos') !== 'todos' ? $tipoSeleccionado : null,
                            ]);
                        @endphp
                        <a
                            href="{{ route('vistas.comunidad', $paramsOrden) }}"
                            class="dropdown-item {{ ($ordenSeleccionado ?? 'recientes') === $orden ? 'active' : '' }}"
                            aria-current="{{ ($ordenSeleccionado ?? 'recientes') === $orden ? 'page' : 'false' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach

                    <span class="challenge-filter-heading">Contenido</span>
                    @foreach ($filtrosTipo as $tipo => $label)
                        @php
                            $paramsTipo = array_filter([
                                'orden' => $ordenSeleccionado ?? 'recientes',
                                'tipo' => $tipo === 'todos' ? null : $tipo,
                            ]);
                        @endphp
                        <a
                            href="{{ route('vistas.comunidad', $paramsTipo) }}"
                            class="dropdown-item {{ ($tipoSeleccionado ?? 'todos') === $tipo ? 'active' : '' }}"
                            aria-current="{{ ($tipoSeleccionado ?? 'todos') === $tipo ? 'page' : 'false' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <section class="community-layout {{ $esAdmin ? 'is-admin' : '' }}">
            @unless ($esAdmin)
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
            @endunless

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
                            \Illuminate\Support\Str::substr((string) $publicacion->user->nombre_publico, 0, 1)
                        );

                        $usuariosConMeGusta = $publicacion->meGustaUsuarios;
                        $leGustaAlUsuario = $usuariosConMeGusta->contains('id', (int) $usuarioAutenticado->id);
                        $totalMeGusta = (int) $publicacion->me_gusta_usuarios_count;
                        $comentariosPublicacion = $publicacion->comentarios;
                        $totalComentarios = (int) $publicacion->comentarios_count;
                        $comentarioReciente = $comentariosPublicacion->first();
                        $puedeGestionarPublicacion = (int) $usuarioAutenticado->id === (int) $publicacion->user_id;
                        $puedeBorrarPublicacion = $esAdmin || $puedeGestionarPublicacion;

                        $contenidoEdicion = old('publicacion_id') == (string) $publicacion->id
                            ? old('contenido')
                            : $publicacion->contenido;

                        $likesModalId = 'community-likes-modal-'.$publicacion->id;
                        $commentsModalId = 'community-comments-modal-'.$publicacion->id;
                    @endphp

                    <article class="community-post" id="publicacion-{{ $publicacion->id }}">
                        <header class="community-post-head">
                            <div class="community-post-author">
                                <div class="community-avatar" aria-hidden="true">{{ $inicialUsuario }}</div>
                                <div>
                                    <strong>{{ $publicacion->user->nombre_publico }}</strong>
                                    <time datetime="{{ optional($publicacion->fecha_publicacion)->toIso8601String() }}">
                                        {{ optional($publicacion->fecha_publicacion)->format('d/m/Y H:i') }}
                                    </time>
                                </div>
                            </div>

                            @if ($puedeBorrarPublicacion)
                                <details class="community-post-menu">
                                    <summary aria-label="Opciones de publicacion">
                                        <span class="community-post-menu-dots" aria-hidden="true">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </span>
                                    </summary>

                                    <div class="community-post-menu-panel">
                                        @if ($esAdmin)
                                            <form
                                                action="{{ route('vistas.comunidad.publicaciones.destroy', $publicacion) }}"
                                                method="POST"
                                                onsubmit="return confirm('Se eliminara esta publicacion y sus comentarios. Quieres continuar?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="page" value="{{ $publicaciones->currentPage() }}">
                                                <button type="submit" class="community-menu-item community-menu-item-danger">Eliminar publicacion</button>
                                            </form>
                                        @else
                                            <details class="community-menu-edit">
                                                <summary class="community-menu-item">Editar publicacion</summary>

                                                <div class="community-menu-edit-panel">
                                                    <form
                                                        action="{{ route('vistas.comunidad.publicaciones.update', $publicacion) }}"
                                                        method="POST"
                                                        enctype="multipart/form-data"
                                                        class="community-edit-form"
                                                    >
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="page" value="{{ $publicaciones->currentPage() }}">
                                                        <input type="hidden" name="publicacion_id" value="{{ $publicacion->id }}">

                                                        <label for="editar-contenido-{{ $publicacion->id }}" class="form-label">Texto</label>
                                                        <textarea
                                                            id="editar-contenido-{{ $publicacion->id }}"
                                                            name="contenido"
                                                            rows="4"
                                                            class="form-control"
                                                            placeholder="Actualiza el texto de tu publicacion"
                                                        >{{ $contenidoEdicion }}</textarea>

                                                        <label for="editar-imagen-{{ $publicacion->id }}" class="form-label">Reemplazar imagen (opcional)</label>
                                                        <input
                                                            id="editar-imagen-{{ $publicacion->id }}"
                                                            type="file"
                                                            name="imagen"
                                                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                                            class="form-control"
                                                        >

                                                        @if ($publicacion->imagen)
                                                            <div class="form-check community-remove-image-check">
                                                                <input
                                                                    class="form-check-input"
                                                                    type="checkbox"
                                                                    name="eliminar_imagen"
                                                                    value="1"
                                                                    id="eliminar-imagen-{{ $publicacion->id }}"
                                                                >
                                                                <label class="form-check-label" for="eliminar-imagen-{{ $publicacion->id }}">
                                                                    Eliminar imagen actual
                                                                </label>
                                                            </div>
                                                        @endif

                                                        <button type="submit" class="btn btn-outline-primary home-btn">Guardar cambios</button>
                                                    </form>
                                                </div>
                                            </details>

                                            <form
                                                action="{{ route('vistas.comunidad.publicaciones.destroy', $publicacion) }}"
                                                method="POST"
                                                onsubmit="return confirm('Se eliminara esta publicacion y sus comentarios. Quieres continuar?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="page" value="{{ $publicaciones->currentPage() }}">
                                                <button type="submit" class="community-menu-item community-menu-item-danger">Eliminar publicacion</button>
                                            </form>
                                        @endif
                                    </div>
                                </details>
                            @endif
                        </header>

                        @if ($publicacion->contenido)
                            <p class="community-post-content">{!! nl2br(e($publicacion->contenido)) !!}</p>
                        @endif

                        @if ($urlImagen)
                            <figure class="community-post-image">
                                <img src="{{ $urlImagen }}" alt="Imagen publicada por {{ $publicacion->user->nombre_publico }}">
                            </figure>
                        @endif

                        <div class="community-post-actions">
                            @if ($esAdmin)
                                <div class="community-admin-stats-inline">
                                    <span>{{ $totalMeGusta }} me gusta</span>
                                    <span>{{ $totalComentarios }} comentarios</span>
                                </div>
                            @else
                                <form
                                    action="{{ route('vistas.comunidad.publicaciones.toggle-like', $publicacion) }}"
                                    method="POST"
                                    class="community-like-form"
                                >
                                    @csrf
                                    <input type="hidden" name="page" value="{{ $publicaciones->currentPage() }}">
                                    <button
                                        type="submit"
                                        class="btn btn-sm community-like-btn {{ $leGustaAlUsuario ? 'is-active' : '' }}"
                                    >
                                        <span aria-hidden="true">&#9829;</span>
                                        <span>{{ $leGustaAlUsuario ? 'Te gusta' : 'Me gusta' }}</span>
                                    </button>
                                    <span class="community-like-counter">{{ $totalMeGusta }} me gusta</span>
                                </form>
                            @endif
                        </div>

                        @if ($totalMeGusta > 1 || $totalComentarios > 1)
                            <section class="community-totals-zone" aria-label="Resumen de actividad de la publicacion">
                                @if ($totalMeGusta > 1)
                                    <button
                                        type="button"
                                        class="community-total-trigger"
                                        data-community-modal-target="{{ $likesModalId }}"
                                    >
                                        Ver total de me gusta
                                    </button>
                                @endif

                                @if ($totalComentarios > 1)
                                    <button
                                        type="button"
                                        class="community-total-trigger"
                                        data-community-modal-target="{{ $commentsModalId }}"
                                    >
                                        Ver total de comentarios
                                    </button>
                                @endif
                            </section>
                        @endif

                        @if ($totalMeGusta > 1)
                            <dialog id="{{ $likesModalId }}" class="community-modal">
                                <article class="community-modal-card">
                                    <header class="community-modal-header">
                                        <h5>Personas con me gusta ({{ $totalMeGusta }})</h5>
                                        <button type="button" class="community-modal-close" data-community-modal-close>Cerrar</button>
                                    </header>

                                    <ul class="community-modal-list" aria-label="Usuarios que dieron me gusta">
                                        @foreach ($usuariosConMeGusta as $usuarioConMeGusta)
                                            <li>{{ $usuarioConMeGusta->nombre_publico }}</li>
                                        @endforeach
                                    </ul>
                                </article>
                            </dialog>
                        @endif

                        @if ($totalComentarios > 1)
                            <dialog id="{{ $commentsModalId }}" class="community-modal">
                                <article class="community-modal-card">
                                    <header class="community-modal-header">
                                        <h5>Comentarios de la publicacion ({{ $totalComentarios }})</h5>
                                        <button type="button" class="community-modal-close" data-community-modal-close>Cerrar</button>
                                    </header>

                                    <div class="community-modal-comments-list" aria-label="Lista completa de comentarios">
                                        @foreach ($comentariosPublicacion as $comentarioModal)
                                            @php
                                                $puedeEliminarComentarioModal =
                                                    $usuarioAutenticado->rol === 'admin' ||
                                                    (int) $usuarioAutenticado->id === (int) $comentarioModal->user_id;
                                            @endphp

                                            <article class="community-modal-comment">
                                                <div class="community-modal-comment-head">
                                                    <strong>{{ $comentarioModal->user->nombre_publico }}</strong>

                                                    @if ($puedeEliminarComentarioModal)
                                                        <form
                                                            action="{{ route('vistas.comunidad.comentarios.destroy', $comentarioModal) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Quieres eliminar este comentario?');"
                                                        >
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="page" value="{{ $publicaciones->currentPage() }}">
                                                            <button type="submit" class="community-modal-comment-delete">Eliminar</button>
                                                        </form>
                                                    @endif
                                                </div>
                                                <p>{{ $comentarioModal->contenido }}</p>
                                            </article>
                                        @endforeach
                                    </div>
                                </article>
                            </dialog>
                        @endif

                        <section class="community-comments">
                            <h4>Comentarios ({{ $totalComentarios }})</h4>

                            @if ($comentarioReciente)
                                @php
                                    $puedeEliminarComentario =
                                        $usuarioAutenticado->rol === 'admin' ||
                                        (int) $usuarioAutenticado->id === (int) $comentarioReciente->user_id;
                                @endphp

                                <article class="community-comment">
                                    <div class="community-comment-head">
                                        <p>
                                            <strong>{{ $comentarioReciente->user->nombre_publico }}</strong>
                                            <span>{{ $comentarioReciente->contenido }}</span>
                                        </p>

                                        @if ($puedeEliminarComentario)
                                            <form
                                                action="{{ route('vistas.comunidad.comentarios.destroy', $comentarioReciente) }}"
                                                method="POST"
                                                onsubmit="return confirm('Quieres eliminar este comentario?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="page" value="{{ $publicaciones->currentPage() }}">
                                                <button type="submit" class="btn btn-sm btn-link community-comment-delete-btn">Eliminar</button>
                                            </form>
                                        @endif
                                    </div>
                                    <time datetime="{{ optional($comentarioReciente->fecha_comentario)->toIso8601String() }}">
                                        {{ optional($comentarioReciente->fecha_comentario)->format('d/m/Y H:i') }}
                                    </time>
                                </article>
                            @else
                                <p class="community-empty-comments">Todavia no hay comentarios. Se el primero.</p>
                            @endif
                        </section>

                        @unless ($esAdmin)
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
                        @endunless
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

@push('scripts')
<script>
    (function () {
        document.addEventListener('click', function (event) {
            var trigger = event.target.closest('[data-community-modal-target]');

            if (trigger) {
                var modalId = trigger.getAttribute('data-community-modal-target');
                var modal = document.getElementById(modalId);

                if (modal && typeof modal.showModal === 'function') {
                    modal.showModal();
                }

                return;
            }

            var closeButton = event.target.closest('[data-community-modal-close]');

            if (closeButton) {
                var parentModal = closeButton.closest('dialog');

                if (parentModal) {
                    parentModal.close();
                }

                return;
            }

            var modalBackground = event.target.closest('dialog.community-modal');

            if (modalBackground && event.target === modalBackground) {
                modalBackground.close();
            }
        });
    }());
</script>
@endpush
