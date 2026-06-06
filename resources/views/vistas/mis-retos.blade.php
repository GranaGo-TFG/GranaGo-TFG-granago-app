@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <h1 class="home-kicker">Mis retos</h1>
                <h2>Gestion de retos creados por ti</h2>
                <p>Edita la informacion de cada reto o eliminalo cuando ya no quieras mantenerlo.</p>
            </div>
            <a href="{{ route('vistas.crear-reto') }}" class="btn btn-primary home-btn">Crear reto</a>
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
                    id="misRetosFilterDropdown"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <svg class="challenge-filter-icon" aria-hidden="true" viewBox="0 0 24 24" focusable="false">
                        <path d="M4 5h16l-6.5 7.4v5.1l-3 1.5v-6.6L4 5Z" />
                    </svg>
                    <span>{{ $filtrosEstado[$estadoSeleccionado ?? 'todos'] ?? 'Todos' }}</span>
                </button>

                <div class="dropdown-menu challenge-filter-dropdown" aria-labelledby="misRetosFilterDropdown">
                    <span class="challenge-filter-heading">Estado</span>
                    @foreach ($filtrosEstado as $estado => $label)
                        <a
                            href="{{ route('vistas.mis-retos', $estado === 'todos' ? [] : ['estado' => $estado]) }}"
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
                @continue((int) $reto->creador_id !== (int) ($creadorId ?? Auth::id()))

                <div class="home-panel admin-row">
                    <div class="admin-row-main">
                        <div class="admin-row-top">
                            <span class="status-pill status-{{ $reto->estado === 'publicado' ? 'open' : ($reto->estado === 'caducado' ? 'rejected' : 'pending') }}">
                                {{ ucfirst($reto->estado) }}
                            </span>
                            <span class="admin-row-meta">{{ $reto->puntos_recompensa }} pts</span>
                        </div>

                        <h2>{{ $reto->nombre }}</h2>
                        <p>{{ \Illuminate\Support\Str::limit($reto->descripcion, 170) }}</p>

                        <div class="admin-row-meta-wrap">
                            <span>Inicio: {{ optional($reto->fecha_inicio)->format('d/m/Y H:i') ?? 'Sin fecha' }}</span>
                            <span>Fin: {{ optional($reto->fecha_fin)->format('d/m/Y H:i') ?? 'Sin fecha' }}</span>
                            <span>
                                Validaciones: {{ $reto->validaciones_count }} ({{ $reto->validaciones_verificadas_count }} verificadas)
                            </span>
                        </div>
                    </div>

                    <div class="admin-inline-form d-grid gap-2">
                        <a href="{{ route('vistas.retos.edit', $reto) }}" class="btn btn-primary home-btn">Editar</a>
                        <a href="{{ route('vistas.reto-detalle', $reto) }}" class="btn btn-outline-secondary home-btn">Ver detalle</a>

                        <form
                            method="POST"
                            action="{{ route('vistas.retos.destroy', $reto) }}"
                            onsubmit="return confirm('Se eliminara este reto de forma permanente. Continuar?');"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger home-btn w-100">Eliminar</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="home-panel admin-empty">Aun no has creado retos. Pulsa en "Crear reto" para empezar.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
