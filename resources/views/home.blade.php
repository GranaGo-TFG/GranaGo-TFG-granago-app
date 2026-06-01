@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
@endpush

@section('content')
@php($user = Auth::user())
<div class="home-page">
    <section class="home-carousel-wrap" aria-label="Imagenes destacadas de Granada">
        <div id="granadaHomeCarousel" class="carousel slide home-carousel" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#granadaHomeCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Alhambra exterior"></button>
                <button type="button" data-bs-target="#granadaHomeCarousel" data-bs-slide-to="1" aria-label="Interior historico"></button>
                <button type="button" data-bs-target="#granadaHomeCarousel" data-bs-slide-to="2" aria-label="Patio de los Leones"></button>
                <button type="button" data-bs-target="#granadaHomeCarousel" data-bs-slide-to="3" aria-label="Sierra Nevada"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('images/carrusel/alhambraExterior.jpg') }}" class="d-block w-100" alt="Vista exterior de la Alhambra">
                    <div class="home-carousel-caption">
                        <span>Ruta destacada</span>
                        <strong>Miradores con vistas a la Alhambra</strong>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/carrusel/alhambraInterior.jpg') }}" class="d-block w-100" alt="Patio interior historico">
                    <div class="home-carousel-caption">
                        <span>Reto cultural</span>
                        <strong>Encuentra detalles que pasan desapercibidos</strong>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/carrusel/patioLeones.jpg') }}" class="d-block w-100" alt="Patio de los Leones">
                    <div class="home-carousel-caption">
                        <span>Patrimonio</span>
                        <strong>Granada tambien se juega mirando despacio</strong>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/carrusel/sierraNevada.jpg') }}" class="d-block w-100" alt="Sierra Nevada">
                    <div class="home-carousel-caption">
                        <span>Alrededores</span>
                        <strong>Retos para descubrir mas alla del centro</strong>
                    </div>
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#granadaHomeCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#granadaHomeCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    </section>

    <div class="container">
        @if (session('status'))
            <div class="alert alert-success home-alert" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <section class="home-hero home-hero-featured">
            <div class="home-hero-copy">
                <span class="home-kicker">GranaGO!</span>
                <h1>{{ $user ? 'Granada te espera, ' . $user->nombre : 'Granada te espera' }}</h1>
                <p>
                    Sal a explorar, completa retos fotograficos y suma puntos mientras descubres
                    sitios que normalmente pasan de largo.
                </p>
                <div class="home-actions">
                    <a href="{{ route('vistas.retos') }}" class="btn btn-primary home-btn">Ver retos</a>
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary home-btn">Crear cuenta</a>
                    @endguest
                </div>

                <div class="home-quick-stats" aria-label="Resumen de actividad">
                    <div>
                        <strong>{{ $retosActivos }}</strong>
                        <span>retos activos</span>
                    </div>
                    <div>
                        <strong>{{ $user?->puntos_totales ?? 0 }}</strong>
                        <span>{{ $user ? 'puntos' : 'puntos al empezar' }}</span>
                    </div>
                    <div>
                        <strong>x{{ number_format((float) ($user?->racha_multiplicador ?? 1), 2) }}</strong>
                        <span>{{ $user ? 'racha' : 'multiplicador base' }}</span>
                    </div>
                </div>
            </div>

            <div class="home-map-card">
                <div
                    id="inicio-retos-map"
                    class="home-live-map"
                    data-endpoint="{{ route('retos.mapa.data') }}"
                    data-initial-lat="{{ $retoDestacado?->latitud ?? 37.1773 }}"
                    data-initial-lng="{{ $retoDestacado?->longitud ?? -3.5986 }}"
                    aria-label="Mapa de retos de Granada"
                ></div>
                <label class="home-map-toggle" for="buscarLatLon">
                    <input id="buscarLatLon" type="checkbox" checked>
                    Actualizar por zona visible
                </label>
                <p id="retos-map-status" class="home-map-status" aria-live="polite">Cargando retos...</p>
                <input type="hidden" id="latMax" value="">
                <input type="hidden" id="longMax" value="">
                <input type="hidden" id="latMin" value="">
                <input type="hidden" id="longMin" value="">
            </div>
        </section>

        <section class="home-focus-grid">
            <article class="home-next-challenge">
                @if ($retoDestacado)
                    <div>
                        <span class="home-kicker">Reto recomendado</span>
                        <h2>{{ $retoDestacado->nombre }}</h2>
                        <p>{{ \Illuminate\Support\Str::limit($retoDestacado->descripcion, 160) }}</p>
                    </div>
                    <div class="home-next-meta">
                        <span>+{{ $retoDestacado->puntos_recompensa }} pts</span>
                        <span>{{ ucfirst($retoDestacado->estado) }}</span>
                        <span>{{ $retoDestacado->ubicacion_referencia ?? 'Granada' }}</span>
                    </div>
                    <a href="{{ $user ? route('vistas.reto-detalle', $retoDestacado) : route('login') }}" class="btn btn-primary home-btn">Ver detalle</a>
                @else
                    <div>
                        <span class="home-kicker">Reto recomendado</span>
                        <h2>Aun no hay retos publicados</h2>
                        <p>Crea un reto o revisa el listado para empezar a poblar el mapa del reto.</p>
                    </div>
                    <a href="{{ $user && $user->rol === 'creador' ? route('vistas.crear-reto') : route('vistas.retos') }}" class="btn btn-primary home-btn">Ir a retos</a>
                @endif
            </article>

            <aside class="home-side-panel">
                <div>
                    <span class="home-kicker">Tu progreso</span>
                    @auth
                        <h2>Vas {{ $rankingPosicion }}&ordm; en la clasificacion</h2>
                        @if ($puntosParaSiguiente > 0)
                            <p>Te faltan {{ $puntosParaSiguiente }} puntos para alcanzar la siguiente posicion.</p>
                        @else
                            <p>Estas en el primer puesto. Mantente activo para conservar la posicion.</p>
                        @endif
                    @else
                        <h2>Inicia sesion para seguir tu progreso</h2>
                        <p>Podras guardar tus puntos, ver tu posicion en el ranking y enviar validaciones en cada reto.</p>
                    @endauth
                </div>
                <div class="home-mini-progress">
                    <span style="width: {{ $user ? $progresoRanking : 22 }}%"></span>
                </div>
                <a href="{{ $user ? route('vistas.ranking') : route('login') }}" class="btn btn-primary home-btn">{{ $user ? 'Ver ranking' : 'Entrar para jugar' }}</a>
                <div class="home-map-note">
                    <span>Mapa del reto</span>
                    <strong>{{ $retosConMapa }} retos con coordenadas</strong>
                </div>
            </aside>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
@endpush
