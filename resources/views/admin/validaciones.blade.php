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

        <div class="admin-list">
            @forelse ($validaciones as $validacion)
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
                        <p>{{ $validacion->user->nombre ?? 'Usuario eliminado' }} envio esta prueba para revision.</p>
                        <div class="admin-row-meta-wrap">
                            <span>Email: {{ $validacion->user->email ?? 'No disponible' }}</span>
                            <span>Estado del proyecto: {{ $validacion->reto->estado ?? 'No disponible' }}</span>
                            <span>Prueba: {{ $validacion->foto_prueba }}</span>
                        </div>
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
