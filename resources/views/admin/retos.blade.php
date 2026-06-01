@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <h1 class="home-kicker">Gestion de retos</h1>
                <p>Cambia el estado de los retos activos, borradores, rechazados o retos ya cerrados.</p>
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

        <div class="admin-list">
            @forelse ($retos as $reto)
                <div class="home-panel admin-row">
                    <div class="admin-row-main">
                        <div class="admin-row-top">
                            <span class="status-pill status-{{ $reto->estado === 'publicado' ? 'open' : (in_array($reto->estado, ['caducado', 'rechazado'], true) ? 'rejected' : 'pending') }}">
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
                            @foreach (['borrador', 'publicado', 'caducado', 'rechazado'] as $estado)
                                <option value="{{ $estado }}" @selected($reto->estado === $estado)>{{ ucfirst($estado) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary home-btn">Guardar</button>
                    </form>
                </div>
            @empty
                <div class="home-panel admin-empty">No hay proyectos registrados todavia.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
