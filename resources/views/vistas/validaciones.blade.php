@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <h1 class="home-kicker">Mis validaciones</h1>
                <h2>Seguimiento de tus pruebas</h2>
                <p>Consulta el estado de todas las pruebas que has enviado a los retos.</p>
            </div>
            <a href="{{ route('vistas.retos') }}" class="btn btn-outline-secondary home-btn">Ver retos</a>
        </div>

        @php
            $filtrosEstado = [
                'todos' => 'Todas',
                'pendiente' => 'Pendientes',
                'verificado' => 'Verificadas',
                'rechazado' => 'Rechazadas',
            ];
        @endphp

        <div class="screen-filters challenge-filter-menu">
            <div class="dropdown">
                <button
                    class="btn btn-outline-secondary challenge-filter-toggle"
                    type="button"
                    id="usuarioValidacionFilterDropdown"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <svg class="challenge-filter-icon" aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                        <path d="M4 5h16l-6.5 7.4v5.1l-3 1.5v-6.6L4 5Z" />
                    </svg>
                    <span>{{ $filtrosEstado[$estadoSeleccionado ?? 'todos'] ?? 'Todas' }}</span>
                </button>

                <div class="dropdown-menu challenge-filter-dropdown" aria-labelledby="usuarioValidacionFilterDropdown">
                    <span class="challenge-filter-heading">Estado</span>
                    @foreach ($filtrosEstado as $estado => $label)
                        <a
                            href="{{ route('vistas.validaciones', $estado === 'todos' ? [] : ['estado' => $estado]) }}"
                            class="dropdown-item {{ ($estadoSeleccionado ?? 'todos') === $estado ? 'active' : '' }}"
                            aria-current="{{ ($estadoSeleccionado ?? 'todos') === $estado ? 'page' : 'false' }}"
                        >
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="admin-list">
            @forelse ($validaciones as $validacion)
                @php
                    $fotoPrueba = $validacion->foto_prueba;
                    $fotoPruebaUrl = $fotoPrueba
                        ? (filter_var($fotoPrueba, FILTER_VALIDATE_URL)
                            ? $fotoPrueba
                            : route('vistas.validaciones.foto', $validacion))
                        : null;
                @endphp

                <div class="home-panel admin-row">
                    <div class="admin-row-main">
                        <div class="admin-row-top">
                            <span class="status-pill status-{{ $validacion->estado === 'verificado' ? 'open' : ($validacion->estado === 'rechazado' ? 'rejected' : 'pending') }}">
                                {{ ucfirst($validacion->estado) }}
                            </span>
                            <span class="admin-row-meta">
                                {{ optional($validacion->fecha_envio)->format('d/m/Y H:i') ?? 'Sin fecha' }}
                            </span>
                        </div>

                        <h2>{{ $validacion->reto->nombre ?? 'Reto eliminado' }}</h2>
                        <p>
                            Estado del reto: {{ $validacion->reto->estado ?? 'No disponible' }}
                            @if ($validacion->reto)
                                · Recompensa: {{ $validacion->reto->puntos_recompensa }} pts
                            @endif
                        </p>

                        @if ($fotoPruebaUrl)
                            <div class="admin-row-meta-wrap">
                                <span>
                                    Prueba:
                                    <a href="{{ $fotoPruebaUrl }}" target="_blank" rel="noopener noreferrer">ver imagen</a>
                                </span>
                            </div>

                            <img
                                src="{{ $fotoPruebaUrl }}"
                                alt="Prueba enviada para {{ $validacion->reto->nombre ?? 'el reto' }}"
                                class="mt-3 rounded-4 border"
                                style="width:min(100%, 16rem); max-height:16rem; object-fit:contain; background:#fff;"
                            >
                        @endif
                    </div>

                    <div class="admin-inline-form d-grid gap-2">
                        <span class="form-label mb-0">Estado actual</span>
                        <strong>{{ ucfirst($validacion->estado) }}</strong>

                        @if ($validacion->reto)
                            <a href="{{ route('vistas.reto-detalle', $validacion->reto) }}" class="btn btn-outline-secondary home-btn">Ver detalle del reto</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="home-panel admin-empty">Aun no has enviado pruebas para ningun reto.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
