@extends('layouts.app')

@section('content')
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
                <h1>Granada te espera, {{ Auth::user()->nombre }}</h1>
                <p>
                    Sal a explorar, completa retos fotograficos y suma puntos mientras descubres
                    sitios que normalmente pasan de largo.
                </p>
                <div class="home-actions">
                    <a href="{{ route('vistas.retos') }}" class="btn btn-primary home-btn">Ver retos</a>
                    <a href="{{ route('vistas.subir-prueba') }}" class="btn btn-outline-secondary home-btn">Subir prueba</a>
                </div>

                <div class="home-quick-stats" aria-label="Resumen de actividad">
                    <div>
                        <strong>3</strong>
                        <span>retos activos</span>
                    </div>
                    <div>
                        <strong>120</strong>
                        <span>puntos</span>
                    </div>
                    <div>
                        <strong>x1.25</strong>
                        <span>racha</span>
                    </div>
                </div>
            </div>

            <div class="home-map-card">
                <img src="{{ asset('images/mapaGranaIlustracion.png') }}" alt="Mapa ilustrado de Granada">
                <div class="home-map-note">
                    <span>Siguiente ruta</span>
                    <strong>Albaicin - Mirador - Centro</strong>
                </div>
            </div>
        </section>

        <section class="home-focus-grid">
            <article class="home-next-challenge">
                <div>
                    <span class="home-kicker">Reto recomendado</span>
                    <h2>Foto en el Mirador de San Nicolas</h2>
                    <p>Un reto corto para empezar la ruta: encuentra una buena vista de la Alhambra y sube tu prueba.</p>
                </div>
                <div class="home-next-meta">
                    <span>+50 pts</span>
                    <span>Fotografico</span>
                    <span>Albaicin</span>
                </div>
                <a href="{{ route('vistas.reto-detalle') }}" class="home-small-link">Ver detalle</a>
            </article>

            <aside class="home-side-panel">
                <div>
                    <span class="home-kicker">Tu progreso</span>
                    <h2>Vas 3º esta semana</h2>
                    <p>Te faltan 30 puntos para alcanzar el segundo puesto.</p>
                </div>
                <div class="home-mini-progress">
                    <span style="width: 68%"></span>
                </div>
                <a href="{{ route('vistas.ranking') }}" class="home-small-link">Ver ranking</a>
            </aside>
        </section>
    </div>
</div>
@endsection
