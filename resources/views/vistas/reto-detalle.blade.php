@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
@endpush

@section('content')
<div class="screen-page">
    <div class="container">
        @php
            $statusClass = match ($reto->estado) {
                'publicado' => 'status-open',
                'borrador' => 'status-draft',
                default => 'status-rejected',
            };
        @endphp

        @if (session('status'))
            <div class="alert alert-success home-alert" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <section class="detail-hero">
            @if ($reto->archivo_multimedia)
                <img src="{{ $reto->archivo_multimedia }}" alt="Imagen del reto {{ $reto->nombre }}" class="detail-hero-media">
            @else
                <div class="detail-hero-media detail-hero-media-fallback" aria-hidden="true"></div>
            @endif

            <div class="detail-hero-overlay">
                <span class="home-kicker">Reto:</span>
                <h1>{{ $reto->nombre }}</h1>
                <span class="status-pill {{ $statusClass }} detail-status-pill">Estado: {{ ucfirst($reto->estado) }}</span>
                <p>{{ $reto->descripcion }}</p>
            </div>
        </section>

        <section class="home-panel detail-content-panel">

            <div class="row g-3 mt-2">
                <div class="col-lg-8">
                    <div class="p-3 border rounded-4 bg-white h-100">
                        @if (! is_null($reto->latitud) && ! is_null($reto->longitud))
                            <div
                                id="detalle-reto-map"
                                style="width:100%; min-height:22rem; border:1px solid rgba(30, 41, 59, 0.14); border-radius:0.9rem;"
                                data-lat="{{ $reto->latitud }}"
                                data-lng="{{ $reto->longitud }}"
                                data-title="{{ $reto->nombre }}"
                                data-ref="{{ $reto->ubicacion_referencia ?? 'Granada' }}"
                            ></div>
                        @else
                            <p class="muted-copy mb-0">Este reto no tiene coordenadas todavia.</p>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="p-3 border rounded-4 bg-white h-100 d-grid gap-3">
                        <div>
                            <h2 class="h5 mb-3">Resumen</h2>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><strong>Recompensa:</strong> {{ $reto->puntos_recompensa }} puntos</li>
                                <li class="mb-2"><strong>Estado:</strong> {{ ucfirst($reto->estado) }}</li>
                                <li class="mb-2"><strong>Zona:</strong> {{ $reto->ubicacion_referencia ?? 'Sin zona definida' }}</li>
                                <li class="mb-2"><strong>Inicio:</strong> {{ optional($reto->fecha_inicio)->format('d/m/Y H:i') ?? 'Sin fecha' }}</li>
                                <li class="mb-2"><strong>Fin:</strong> {{ optional($reto->fecha_fin)->format('d/m/Y H:i') ?? 'Sin fecha' }}</li>
                                <li><strong>Creador:</strong> {{ $reto->creador->nombre ?? 'No disponible' }}</li>
                            </ul>
                        </div>

                        <a href="{{ route('vistas.subir-prueba') }}" class="btn btn-primary home-btn w-100">Subir prueba</a>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-1">
                <div class="col-md-4">
                    <article class="p-3 border rounded-4 bg-white h-100">
                        <span class="d-block text-muted small">Total envios</span>
                        <strong>{{ $reto->validaciones_count }}</strong>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="p-3 border rounded-4 bg-white h-100">
                        <span class="d-block text-muted small">Verificadas</span>
                        <strong>{{ $reto->validaciones_verificadas_count }}</strong>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="p-3 border rounded-4 bg-white h-100">
                        <span class="d-block text-muted small">Pendientes</span>
                        <strong>{{ $reto->validaciones_pendientes_count }}</strong>
                    </article>
                </div>
            </div>

            <div class="home-panel mt-3">
                <span class="home-kicker">Actividad reciente</span>

                <div class="d-grid gap-2 mt-2">
                    @forelse ($validacionesRecientes as $validacion)
                        <article class="p-3 border rounded-4 bg-white">
                            <strong>{{ $validacion->user->nombre ?? 'Usuario' }}</strong>
                            <p class="mb-0 muted-copy">
                                Estado: {{ ucfirst($validacion->estado) }}
                                @if ($validacion->fecha_envio)
                                    · {{ optional($validacion->fecha_envio)->format('d/m/Y H:i') }}
                                @endif
                            </p>
                        </article>
                    @empty
                        <article class="p-3 border rounded-4 bg-white">
                            <strong>Sin actividad aun</strong>
                            <p class="mb-0 muted-copy">No se han enviado validaciones para este reto.</p>
                        </article>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('vistas.retos') }}" class="home-small-link d-inline-block mt-3">Volver a retos</a>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var elMapa = document.getElementById('detalle-reto-map');

    if (!elMapa || typeof L === 'undefined') {
        return;
    }

    var lat = Number.parseFloat(elMapa.dataset.lat || '');
    var lng = Number.parseFloat(elMapa.dataset.lng || '');

    if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
        return;
    }

    var escapar = function (texto) {
        return String(texto)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    };

    var mapa = L.map(elMapa, {
        scrollWheelZoom: false,
    }).setView([lat, lng], 15);

    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19,
        minZoom: 5,
    }).addTo(mapa);

    var marker = L.marker([lat, lng]).addTo(mapa);
    var titulo = elMapa.dataset.title || 'Reto';
    var referencia = elMapa.dataset.ref || 'Granada';

    marker.bindPopup('<strong>' + escapar(titulo) + '</strong><br>' + escapar(referencia)).openPopup();
});
</script>
@endpush
