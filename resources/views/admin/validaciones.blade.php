@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <h1 class="home-kicker">Validaciones de usuarios</h1>
                <p>Revisa cada prueba enviada y decide si queda pendiente, verificada o rechazada.</p>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success home-alert" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

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
                    id="adminValidacionFilterDropdown"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <svg class="challenge-filter-icon" aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                        <path d="M4 5h16l-6.5 7.4v5.1l-3 1.5v-6.6L4 5Z" />
                    </svg>
                    <span>{{ $filtrosEstado[$estadoSeleccionado ?? 'todos'] ?? 'Todas' }}</span>
                </button>

                <div class="dropdown-menu challenge-filter-dropdown" aria-labelledby="adminValidacionFilterDropdown">
                    <span class="challenge-filter-heading">Estado</span>
                    @foreach ($filtrosEstado as $estado => $label)
                        <a
                            href="{{ route('admin.validaciones.index', $estado === 'todos' ? [] : ['estado' => $estado]) }}"
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
                <div class="home-panel admin-row">
                    <div class="admin-row-main">
                        @php
                            $fotoPrueba = $validacion->foto_prueba;
                            $fotoPruebaUrl = filter_var($fotoPrueba, FILTER_VALIDATE_URL)
                                ? $fotoPrueba
                                : route('vistas.validaciones.foto', $validacion);
                        @endphp

                        <div class="admin-row-top">
                            <span class="status-pill status-{{ $validacion->estado === 'verificado' ? 'open' : ($validacion->estado === 'rechazado' ? 'rejected' : 'pending') }}">
                                {{ ucfirst($validacion->estado) }}
                            </span>
                            <span class="admin-row-meta">
                                {{ optional($validacion->fecha_envio)->format('d/m/Y H:i') ?? 'Sin fecha' }}
                            </span>
                        </div>
                        <h2>{{ $validacion->reto->nombre ?? 'Reto eliminado' }}</h2>
                        <p>{{ $validacion->user->nombre ?? 'Usuario eliminado' }} envio esta prueba para revision.</p>
                        <div class="admin-row-meta-wrap">
                            <span>Email: {{ $validacion->user->email ?? 'No disponible' }}</span>
                            <span>Estado del reto: {{ $validacion->reto->estado ?? 'No disponible' }}</span>
                            <span>
                                Prueba:
                                <a href="{{ $fotoPruebaUrl }}" target="_blank" rel="noopener noreferrer">ver imagen</a>
                            </span>
                        </div>
                        <img
                            src="{{ $fotoPruebaUrl }}"
                            alt="Prueba enviada para {{ $validacion->reto->nombre ?? 'el reto' }}"
                            class="mt-3 rounded-4 border"
                            style="width:min(100%, 16rem); aspect-ratio:1; object-fit:cover;"
                        >
                    </div>

                    <form method="POST" action="{{ route('admin.validaciones.update', $validacion) }}" class="admin-inline-form">
                        @csrf
                        @method('PATCH')
                        <label for="estado-validacion-{{ $validacion->id }}" class="form-label">Estado</label>
                        <select id="estado-validacion-{{ $validacion->id }}" name="estado" class="form-select">
                            @foreach (['pendiente', 'verificado', 'rechazado'] as $estado)
                                <option value="{{ $estado }}" @selected($validacion->estado === $estado)>{{ ucfirst($estado) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary home-btn">Guardar</button>
                    </form>
                </div>
            @empty
                <div class="home-panel admin-empty">No hay validaciones registradas todavia.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
