@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="detail-layout">
            <section class="home-panel detail-main">
                <span class="home-kicker">Reto fotografico</span>
                <h1>Foto en el Mirador de San Nicolas</h1>
                <p class="detail-copy">
                    El objetivo es llegar al mirador, encontrar una vista clara de la Alhambra
                    y subir una foto hecha durante la ruta. No hace falta que sea perfecta,
                    solo que se reconozca bien el sitio.
                </p>

                <div class="detail-map">
                    <img src="{{ asset('images/mapaGranaIlustracion.png') }}" alt="Mapa ilustrado de Granada">
                </div>

                <div class="detail-notes">
                    <article>
                        <span>Recompensa</span>
                        <strong>50 puntos</strong>
                    </article>
                    <article>
                        <span>Estado</span>
                        <strong>Publicado</strong>
                    </article>
                    <article>
                        <span>Zona</span>
                        <strong>Albaicin</strong>
                    </article>
                </div>
            </section>

            <aside class="home-panel">
                <span class="home-kicker">Prueba</span>
                <h2>Sube tu foto</h2>
                <p class="muted-copy">Sube una imagen donde se reconozca bien el lugar del reto.</p>
                <a href="{{ route('vistas.subir-prueba') }}" class="btn btn-primary home-btn w-100">Enviar prueba</a>

                <div class="mini-feed">
                    <article>
                        <strong>Usuario cercano</strong>
                        <p>Prueba pendiente de revisar.</p>
                    </article>
                    <article>
                        <strong>Explorador local</strong>
                        <p>Validado hace 2 dias.</p>
                    </article>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
