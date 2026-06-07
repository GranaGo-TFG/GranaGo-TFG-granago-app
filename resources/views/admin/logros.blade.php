@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="screen-head reveal-item">
            <div>
                <h1 class="home-kicker">Gestion de logros</h1>
                <p>Apartado exclusivo de administracion para crear, editar y eliminar logros del sistema.</p>
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

        <section class="home-panel mb-3 reveal-item" style="transition-delay: 40ms;">
            <div class="admin-row-top mb-2">
                <span class="status-pill status-open">Nuevo logro</span>
            </div>

            <form method="POST" action="{{ route('admin.logros.store') }}" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label for="crear-logro-nombre" class="form-label">Nombre del logro</label>
                    <input
                        type="text"
                        id="crear-logro-nombre"
                        name="nombre_logro"
                        class="form-control"
                        maxlength="100"
                        value="{{ old('nombre_logro') }}"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label for="crear-logro-icono" class="form-label">Icono (ruta)</label>
                    <input
                        type="text"
                        id="crear-logro-icono"
                        name="icono"
                        class="form-control"
                        maxlength="255"
                        value="{{ old('icono', 'logros/generico.png') }}"
                        placeholder="logros/mi-logro.png"
                    >
                </div>

                <div class="col-12">
                    <label for="crear-logro-descripcion" class="form-label">Descripcion</label>
                    <textarea
                        id="crear-logro-descripcion"
                        name="descripcion"
                        class="form-control"
                        rows="3"
                        required
                    >{{ old('descripcion') }}</textarea>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary home-btn">Crear logro</button>
                </div>
            </form>
        </section>

        <section class="home-panel mb-3 reveal-item" style="transition-delay: 90ms;">
            <div class="admin-row-top mb-2">
                <span class="status-pill status-pending">Editar logros</span>
            </div>
            <h2 class="mb-2">Logros existentes</h2>
            <p class="mb-0 muted-copy">Aqui puedes actualizar o eliminar cada logro registrado.</p>
        </section>

        <div class="admin-list">
            @forelse ($logros as $logro)
                <article class="home-panel admin-row reveal-item" style="transition-delay: {{ 120 + ($loop->index * 35) }}ms;">
                    <div class="admin-row-main">
                        <div class="admin-row-top">
                            <span class="status-pill status-open">Logro</span>
                        </div>
                        <h2>{{ $logro->nombre_logro }}</h2>
                        <p>{{ $logro->descripcion }}</p>
                        <div class="admin-row-meta-wrap">
                            <span>Icono: {{ $logro->icono }}</span>
                            <span>Desbloqueado por {{ $logro->usuarios_count }} usuarios</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.logros.update', $logro) }}" class="row g-2 mt-2">
                        @csrf
                        @method('PATCH')

                        <div class="col-md-6">
                            <label for="logro-nombre-{{ $logro->id }}" class="form-label">Nombre</label>
                            <input
                                type="text"
                                id="logro-nombre-{{ $logro->id }}"
                                name="nombre_logro"
                                class="form-control"
                                maxlength="100"
                                value="{{ $logro->nombre_logro }}"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="logro-icono-{{ $logro->id }}" class="form-label">Icono (ruta)</label>
                            <input
                                type="text"
                                id="logro-icono-{{ $logro->id }}"
                                name="icono"
                                class="form-control"
                                maxlength="255"
                                value="{{ $logro->icono }}"
                            >
                        </div>

                        <div class="col-12">
                            <label for="logro-descripcion-{{ $logro->id }}" class="form-label">Descripcion</label>
                            <textarea
                                id="logro-descripcion-{{ $logro->id }}"
                                name="descripcion"
                                class="form-control"
                                rows="3"
                                required
                            >{{ $logro->descripcion }}</textarea>
                        </div>

                        <div class="col-12 d-flex flex-wrap justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary home-btn">Guardar cambios</button>
                        </div>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('admin.logros.destroy', $logro) }}"
                        class="d-flex justify-content-end mt-2"
                        onsubmit="return confirm('Se eliminara este logro y sus desbloqueos asociados. ¿Continuar?');"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-secondary home-btn">Eliminar logro</button>
                    </form>
                </article>
            @empty
                <div class="home-panel admin-empty">No hay logros registrados todavia.</div>
            @endforelse
        </div>

        <div class="store-pagination">
            {{ $logros->onEachSide(1)->links('vendor.pagination.store-bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
