@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
@endpush

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <h1 class="home-kicker">Editar reto</h1>
                <h2>Actualiza la informacion del reto</h2>
                <p>Puedes cambiar la descripcion, la historia y la ubicacion en el mapa cuando lo necesites.</p>
            </div>
            <a href="{{ route('vistas.mis-retos') }}" class="btn btn-outline-secondary home-btn">Volver a mis retos</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="home-panel challenge-form-card">
            <form method="POST" action="{{ route('vistas.retos.update', $reto) }}" class="challenge-form-grid">
                @csrf
                @method('PATCH')

                <div class="challenge-form-main">
                    <div class="challenge-form-field challenge-form-field-full">
                        <label for="nombre" class="form-label">Nombre del reto</label>
                        <input id="nombre" type="text" name="nombre" class="form-control" maxlength="100" value="{{ old('nombre', $reto->nombre) }}" required>
                    </div>

                    <div class="challenge-form-field challenge-form-field-full">
                        <label for="descripcion" class="form-label">Descripcion</label>
                        <textarea id="descripcion" name="descripcion" rows="5" class="form-control" required>{{ old('descripcion', $reto->descripcion) }}</textarea>
                    </div>

                    <div class="challenge-form-field">
                        <label for="archivo_multimedia" class="form-label">URL multimedia (opcional)</label>
                        <input id="archivo_multimedia" type="text" name="archivo_multimedia" class="form-control" value="{{ old('archivo_multimedia', $reto->archivo_multimedia) }}" placeholder="https://...">
                    </div>

                    <div class="challenge-form-field">
                        <label for="ubicacion_referencia" class="form-label">Zona o referencia</label>
                        <input id="ubicacion_referencia" type="text" name="ubicacion_referencia" class="form-control" maxlength="120" value="{{ old('ubicacion_referencia', $reto->ubicacion_referencia) }}" placeholder="Ej: Mirador de San Nicolas">
                    </div>

                    <div class="challenge-form-field">
                        <label for="fecha_inicio" class="form-label">Fecha inicio</label>
                        <input
                            id="fecha_inicio"
                            type="datetime-local"
                            name="fecha_inicio"
                            class="form-control"
                            value="{{ old('fecha_inicio', optional($reto->fecha_inicio)->format('Y-m-d\\TH:i')) }}"
                            required
                        >
                    </div>

                    <div class="challenge-form-field">
                        <label for="fecha_fin" class="form-label">Fecha fin</label>
                        <input
                            id="fecha_fin"
                            type="datetime-local"
                            name="fecha_fin"
                            class="form-control"
                            value="{{ old('fecha_fin', optional($reto->fecha_fin)->format('Y-m-d\\TH:i')) }}"
                            required
                        >
                    </div>

                    <div class="challenge-form-field">
                        <label for="puntos_recompensa" class="form-label">Puntos recompensa</label>
                        <input id="puntos_recompensa" type="number" name="puntos_recompensa" min="0" class="form-control" value="{{ old('puntos_recompensa', $reto->puntos_recompensa) }}" required>
                    </div>

                    <div class="challenge-form-field challenge-form-field-full challenge-story-head">
                        <span class="home-kicker">Eco de Granada</span>
                        <h2>Historia del lugar</h2>
                        <p class="muted-copy mb-0">Este contenido aparecera al pulsar el icono de historia en los detalles del reto.</p>
                    </div>

                    <div class="challenge-form-field challenge-form-field-full">
                        <label for="titulo_relato" class="form-label">Titulo del relato (opcional)</label>
                        <input id="titulo_relato" type="text" name="titulo_relato" class="form-control" maxlength="255" value="{{ old('titulo_relato', $reto->titulo_relato) }}">
                    </div>

                    <div class="challenge-form-field challenge-form-field-full">
                        <label for="leyenda_relato" class="form-label">Introduccion o leyenda (opcional)</label>
                        <textarea id="leyenda_relato" name="leyenda_relato" rows="3" class="form-control">{{ old('leyenda_relato', $reto->leyenda_relato) }}</textarea>
                    </div>

                    <div class="challenge-form-field challenge-form-field-full">
                        <label for="contenido_relato" class="form-label">Historia (opcional)</label>
                        <textarea id="contenido_relato" name="contenido_relato" rows="5" class="form-control">{{ old('contenido_relato', $reto->contenido_relato) }}</textarea>
                    </div>

                    <div class="challenge-form-field challenge-form-field-full">
                        <label for="cierre_relato" class="form-label">Cierre de la cronica (opcional)</label>
                        <textarea id="cierre_relato" name="cierre_relato" rows="3" class="form-control">{{ old('cierre_relato', $reto->cierre_relato) }}</textarea>
                    </div>
                </div>

                <aside class="challenge-form-map-panel">
                    <h2>Ubicacion en mapa</h2>
                    <p class="muted-copy">Haz click en el mapa para fijar las coordenadas del reto.</p>

                    <div
                        id="crear-reto-map"
                        class="challenge-map-picker"
                        data-default-lat="{{ old('latitud', $reto->latitud ?? 37.1773) }}"
                        data-default-lng="{{ old('longitud', $reto->longitud ?? -3.5986) }}"
                        aria-label="Selector de coordenadas"
                    ></div>

                    <div class="challenge-form-coords">
                        <div class="challenge-form-field">
                            <label for="latitud" class="form-label">Latitud</label>
                            <input id="latitud" type="number" step="0.0000001" name="latitud" class="form-control" value="{{ old('latitud', $reto->latitud) }}" required>
                        </div>

                        <div class="challenge-form-field">
                            <label for="longitud" class="form-label">Longitud</label>
                            <input id="longitud" type="number" step="0.0000001" name="longitud" class="form-control" value="{{ old('longitud', $reto->longitud) }}" required>
                        </div>
                    </div>

                    <div class="challenge-form-actions">
                        <button type="submit" class="btn btn-primary home-btn">Guardar cambios</button>
                    </div>
                </aside>
            </form>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
@endpush
