@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <span class="home-kicker">Creador</span>
                <h1>Crear proyecto</h1>
                <p>Publica un nuevo reto con sus fechas, puntuacion y estado inicial.</p>
            </div>
            <a href="{{ route('vistas.retos') }}" class="btn btn-outline-secondary home-btn">Volver a retos</a>
        </div>

        <div class="home-panel creator-form-card">
            <form method="POST" action="{{ route('vistas.crear-reto.store') }}" class="creator-form">
                @csrf

                <div class="creator-form-grid">
                    <div class="creator-form-field creator-form-field-wide">
                        <label for="nombre" class="form-label">Titulo del proyecto</label>
                        <input id="nombre" type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="creator-form-field creator-form-field-wide">
                        <label for="descripcion" class="form-label">Descripcion</label>
                        <textarea id="descripcion" name="descripcion" rows="5" class="form-control @error('descripcion') is-invalid @enderror" required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="creator-form-field">
                        <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                        <input id="fecha_inicio" type="date" name="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror" value="{{ old('fecha_inicio') }}" required>
                        @error('fecha_inicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="creator-form-field">
                        <label for="fecha_fin" class="form-label">Fecha de fin</label>
                        <input id="fecha_fin" type="date" name="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror" value="{{ old('fecha_fin') }}" required>
                        @error('fecha_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="creator-form-field">
                        <label for="puntos_recompensa" class="form-label">Puntuacion</label>
                        <input id="puntos_recompensa" type="number" min="0" name="puntos_recompensa" class="form-control @error('puntos_recompensa') is-invalid @enderror" value="{{ old('puntos_recompensa') }}" required>
                        @error('puntos_recompensa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="creator-form-field">
                        <label for="estado" class="form-label">Estado</label>
                        <select id="estado" name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                            <option value="borrador" @selected(old('estado', 'borrador') === 'borrador')>Borrador</option>
                            <option value="publicado" @selected(old('estado') === 'publicado')>Publicado</option>
                            <option value="caducado" @selected(old('estado') === 'caducado')>Caducado</option>
                        </select>
                        @error('estado')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="creator-form-field creator-form-field-wide">
                        <label for="archivo_multimedia" class="form-label">Archivo multimedia</label>
                        <input id="archivo_multimedia" type="text" name="archivo_multimedia" class="form-control @error('archivo_multimedia') is-invalid @enderror" value="{{ old('archivo_multimedia') }}" placeholder="Ruta o nombre del recurso multimedia">
                        @error('archivo_multimedia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="creator-form-actions">
                    <a href="{{ route('vistas.retos') }}" class="btn btn-outline-secondary home-btn">Cancelar</a>
                    <button type="submit" class="btn btn-primary home-btn">Crear proyecto</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
