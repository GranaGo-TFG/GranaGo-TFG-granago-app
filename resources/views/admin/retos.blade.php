@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <h1 class="home-kicker">Gestion de retos</h1>
                <p>Cambia el estado de los retos activos, borradores o retos ya cerrados.</p>
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
                'todos' => 'Todos',
                'borrador' => 'Borradores',
                'publicado' => 'Publicados',
                'caducado' => 'Caducados',
            ];
        @endphp

        <div class="screen-filters challenge-filter-menu">
            <div class="dropdown">
                <button
                    class="btn btn-outline-secondary challenge-filter-toggle"
                    type="button"
                    id="adminRetoFilterDropdown"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <svg class="challenge-filter-icon" aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                        <path d="M4 5h16l-6.5 7.4v5.1l-3 1.5v-6.6L4 5Z" />
                    </svg>
                    <span>{{ $filtrosEstado[$estadoSeleccionado ?? 'todos'] ?? 'Todos' }}</span>
                </button>

                <div class="dropdown-menu challenge-filter-dropdown" aria-labelledby="adminRetoFilterDropdown">
                    <span class="challenge-filter-heading">Estado</span>
                    @foreach ($filtrosEstado as $estado => $label)
                        <a
                            href="{{ route('admin.retos.index', $estado === 'todos' ? [] : ['estado' => $estado]) }}"
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
            @forelse ($retos as $reto)
                <div class="home-panel admin-row">
                    <div class="admin-row-main">
                        <div class="admin-row-top">
                            <span class="status-pill status-{{ $reto->estado === 'publicado' ? 'open' : ($reto->estado === 'caducado' ? 'rejected' : 'pending') }}">
                                {{ ucfirst($reto->estado) }}
                            </span>
                            <span class="admin-row-meta">{{ $reto->puntos_recompensa }} pts</span>
                        </div>
                        <h2>{{ $reto->nombre }}</h2>
                        <p>{{ $reto->descripcion }}</p>
                        <div class="admin-row-meta-wrap">
                            <span>Creador: {{ $reto->creador->nombre ?? 'Sin asignar' }}</span>
                            <span>Inicio: {{ optional($reto->fecha_inicio)->format('d/m/Y') ?? 'Sin fecha' }}</span>
                            <span>Fin: {{ optional($reto->fecha_fin)->format('d/m/Y') ?? 'Sin fecha' }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.retos.update', $reto) }}" class="admin-inline-form">
                        @csrf
                        @method('PATCH')
                        <label for="estado-reto-{{ $reto->id }}" class="form-label">Estado</label>
                        <select id="estado-reto-{{ $reto->id }}" name="estado" class="form-select">
                            @foreach (['borrador', 'publicado', 'caducado'] as $estado)
                                <option value="{{ $estado }}" @selected($reto->estado === $estado)>{{ ucfirst($estado) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary home-btn">Guardar</button>
                    </form>
                </div>
            @empty
                <div class="home-panel admin-empty">No hay retos registrados todavia.</div>
            @endforelse
        </div>

        <div class="store-pagination">
            {{ $retos->onEachSide(1)->links('vendor.pagination.store-bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
