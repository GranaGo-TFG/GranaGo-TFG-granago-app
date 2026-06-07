@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <h1 class="home-kicker">Control de usuarios</h1>
                <p>Bloquea o reactiva cuentas de usuarios desde un unico panel de administracion.</p>
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
            @forelse ($usuarios as $usuario)
                <div class="home-panel admin-row">
                    <div class="admin-row-main">
                        <div class="admin-row-top">
                            <span class="status-pill status-{{ $usuario->esta_baneado ? 'rejected' : 'open' }}">
                                {{ $usuario->esta_baneado ? 'Baneado' : 'Activo' }}
                            </span>
                            <span class="admin-row-meta">{{ ucfirst($usuario->rol) }}</span>
                        </div>
                        <h2>{{ $usuario->nombre }}</h2>
                        <p>{{ $usuario->email }}</p>
                        <div class="admin-row-meta-wrap">
                            <span>Puntos: {{ $usuario->puntos_totales }}</span>
                            <span>Racha: x{{ number_format((float) $usuario->racha_multiplicador, 2) }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}" class="admin-inline-form">
                        @csrf
                        @method('PATCH')
                        <label for="estado-usuario-{{ $usuario->id }}" class="form-label">Accion</label>
                        <select
                            id="estado-usuario-{{ $usuario->id }}"
                            name="esta_baneado"
                            class="form-select"
                            @disabled($usuario->rol === 'admin')
                        >
                            <option value="0" @selected(! $usuario->esta_baneado)>Activo</option>
                            <option value="1" @selected($usuario->esta_baneado)>Baneado</option>
                        </select>
                        <button type="submit" class="btn btn-primary home-btn" @disabled($usuario->rol === 'admin')>
                            Guardar
                        </button>
                    </form>
                </div>
            @empty
                <div class="home-panel admin-empty">No hay usuarios disponibles.</div>
            @endforelse
        </div>

        <div class="store-pagination">
            {{ $usuarios->onEachSide(1)->links('vendor.pagination.store-bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
