@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <span class="home-kicker">Retos</span>
                <h1>Retos disponibles por Granada</h1>
                <p>Elige una prueba, sal a la calle y sube una foto cuando la completes.</p>
            </div>
            <a href="{{ route('vistas.subir-prueba') }}" class="btn btn-primary home-btn">Subir prueba</a>
        </div>

        <div class="screen-filters">
            <span class="home-chip">Todos</span>
            <span class="home-chip">Cerca</span>
            <span class="home-chip">Fotografia</span>
            <span class="home-chip">Cultura</span>
            <span class="home-chip">Comercio local</span>
        </div>

        <section class="challenge-grid">
            <article class="challenge-card">
                <div class="challenge-cover challenge-cover-red">
                    <span>+50 pts</span>
                </div>
                <div class="challenge-body">
                    <span class="status-pill status-open">Publicado</span>
                    <h2>Foto en el Mirador de San Nicolas</h2>
                    <p>Busca una vista reconocible de la Alhambra y sube la prueba desde el mirador.</p>
                    <a href="{{ route('vistas.reto-detalle') }}" class="home-small-link">Ver reto</a>
                </div>
            </article>

            <article class="challenge-card">
                <div class="challenge-cover challenge-cover-gold">
                    <span>+35 pts</span>
                </div>
                <div class="challenge-body">
                    <span class="status-pill status-open">Publicado</span>
                    <h2>Ruta de arte urbano</h2>
                    <p>Encuentra un mural o grafiti destacado y comparte una foto cuidada.</p>
                    <a href="{{ route('vistas.reto-detalle') }}" class="home-small-link">Ver reto</a>
                </div>
            </article>

            <article class="challenge-card">
                <div class="challenge-cover challenge-cover-dark">
                    <span>+25 pts</span>
                </div>
                <div class="challenge-body">
                    <span class="status-pill status-draft">Borrador</span>
                    <h2>Comercio de barrio</h2>
                    <p>Una prueba pensada para mover usuarios por zonas menos conocidas.</p>
                    <a href="{{ route('vistas.reto-detalle') }}" class="home-small-link">Ver reto</a>
                </div>
            </article>
        </section>
    </div>
</div>
@endsection
