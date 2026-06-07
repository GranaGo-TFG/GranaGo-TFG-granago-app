@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <h1 class="home-kicker">Retos</h1>
                <h2>Retos disponibles por Granada</h2>
                <p>Elige una prueba, sal a la calle y sube una foto cuando la completes.</p>
            </div>
            @if (Auth::check() && Auth::user()->rol === 'creador')
                <a href="{{ route('vistas.crear-reto') }}" class="btn btn-primary home-btn">Crear reto</a>
            @endif
        </div>

        @if (session('status'))
            <div class="alert alert-success home-alert" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @php
            $filtrosEstado = [
                'todos' => 'Todos',
                'publicado' => 'Publicados',
                'caducado' => 'Caducados',
            ];

            $ordenesReto = [
                'recientes' => 'Mas recientes',
                'caducan' => 'Proximos a caducar',
                'puntos_desc' => 'Mas puntos',
                'puntos_asc' => 'Menos puntos',
            ];
        @endphp

        <div class="screen-filters challenge-filter-menu">
            <div class="dropdown">
                <button
                    class="btn btn-outline-secondary challenge-filter-toggle"
                    type="button"
                    id="challengeFilterDropdown"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <svg class="challenge-filter-icon" aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                        <path d="M4 5h16l-6.5 7.4v5.1l-3 1.5v-6.6L4 5Z" />
                    </svg>
                    <span>Filtros</span>
                </button>

                <div class="dropdown-menu challenge-filter-dropdown" aria-labelledby="challengeFilterDropdown">
                    <span class="challenge-filter-heading">Estado</span>
                    @foreach ($filtrosEstado as $estado => $label)
                        @php
                            $paramsEstado = array_filter([
                                'estado' => $estado === 'todos' ? null : $estado,
                                'orden' => ($ordenSeleccionado ?? 'recientes') === 'recientes' ? null : $ordenSeleccionado,
                                'buscar' => ($busqueda ?? '') !== '' ? $busqueda : null,
                            ], fn ($value) => $value !== null && $value !== '');
                        @endphp
                        <a
                            href="{{ route('vistas.retos', $paramsEstado) }}"
                            class="dropdown-item {{ ($estadoSeleccionado ?? 'todos') === $estado ? 'active' : '' }}"
                            aria-current="{{ ($estadoSeleccionado ?? 'todos') === $estado ? 'page' : 'false' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach

                    <span class="challenge-filter-heading">Ordenar</span>
                    @foreach ($ordenesReto as $orden => $label)
                        @php
                            $paramsOrden = array_filter([
                                'estado' => ($estadoSeleccionado ?? 'todos') === 'todos' ? null : $estadoSeleccionado,
                                'orden' => $orden === 'recientes' ? null : $orden,
                                'buscar' => ($busqueda ?? '') !== '' ? $busqueda : null,
                            ], fn ($value) => $value !== null && $value !== '');
                        @endphp
                        <a
                            href="{{ route('vistas.retos', $paramsOrden) }}"
                            class="dropdown-item {{ ($ordenSeleccionado ?? 'recientes') === $orden ? 'active' : '' }}"
                            aria-current="{{ ($ordenSeleccionado ?? 'recientes') === $orden ? 'page' : 'false' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <form method="GET" action="{{ route('vistas.retos') }}" class="challenge-search-form">
                @if (($estadoSeleccionado ?? 'todos') !== 'todos')
                    <input type="hidden" name="estado" value="{{ $estadoSeleccionado }}">
                @endif

                @if (($ordenSeleccionado ?? 'recientes') !== 'recientes')
                    <input type="hidden" name="orden" value="{{ $ordenSeleccionado }}">
                @endif

                <label for="buscar-reto" class="visually-hidden">Buscar reto</label>
                <input
                    id="buscar-reto"
                    type="search"
                    name="buscar"
                    value="{{ $busqueda ?? '' }}"
                    class="form-control"
                    placeholder="Buscar por nombre o ubicacion"
                >
            </form>
        </div>

        <section class="challenge-grid reveal-list">
            @forelse ($retos as $reto)
                @php
                    $statusClass = match ($reto->estado) {
                        'publicado' => 'status-open',
                        'borrador' => 'status-draft',
                        default => 'status-rejected',
                    };
                @endphp

                <article class="challenge-card reveal-item">
                    <div class="challenge-card-media">
                        @if ($reto->archivo_multimedia)
                            <img
                                src="{{ $reto->archivo_multimedia }}"
                                alt="Imagen del reto {{ $reto->nombre }}"
                                class="challenge-card-media-image"
                            >
                        @else
                            <div class="challenge-card-media-fallback" aria-hidden="true"></div>
                        @endif
                    </div>
                    <div class="challenge-body">
                        <span class="home-kicker">+{{ $reto->puntos_recompensa }} pts</span>
                        <span class="status-pill {{ $statusClass }}">{{ ucfirst($reto->estado) }}</span>
                        <h2>{{ $reto->nombre }}</h2>
                        <p>{{ \Illuminate\Support\Str::limit($reto->descripcion, 125) }}</p>
                        <div class="challenge-card-meta">
                            <span class="d-block">{{ $reto->ubicacion_referencia ?? 'Ubicacion pendiente' }}</span>
                            <span class="d-block">{{ optional($reto->fecha_fin)->format('d/m/Y') ?? 'Sin fecha fin' }}</span>
                            <span class="d-block">{{ $reto->validaciones_verificadas_count }} validadas</span>
                        </div>
                        <a href="{{ Auth::check() ? route('vistas.reto-detalle', $reto) : route('login') }}" class="status-pill home-small-link">Ver detalle</a>
                    </div>
                </article>
            @empty
                <div class="home-panel admin-empty">
                    No hay retos todavia. @if (Auth::check() && Auth::user()->rol === 'creador') Puedes crear el primero desde "Crear reto". @endif
                </div>
            @endforelse
        </section>

        <div class="store-pagination">
            {{ $retos->onEachSide(1)->links('vendor.pagination.store-bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
