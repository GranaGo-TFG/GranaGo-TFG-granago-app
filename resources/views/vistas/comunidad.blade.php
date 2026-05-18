@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <span class="home-kicker">Comunidad</span>
                <h1>Actividad de retos</h1>
                <p>Fotos validadas, comentarios y movimiento reciente de los usuarios.</p>
            </div>
        </div>

        <section class="community-grid">
            <article class="community-card">
                <div class="fake-photo fake-photo-red"></div>
                <strong>Mirador de San Nicolas</strong>
                <p>Un usuario subio una prueba. "Las vistas merecen la cuesta".</p>
                <span class="status-pill status-open">Verificado</span>
            </article>
            <article class="community-card">
                <div class="fake-photo fake-photo-gold"></div>
                <strong>Ruta de arte urbano</strong>
                <p>Comentario nuevo: "Ese mural esta cerca de Realejo".</p>
                <span class="status-pill status-pending">Pendiente</span>
            </article>
            <article class="community-card">
                <div class="fake-photo fake-photo-dark"></div>
                <strong>Comercio local oculto</strong>
                <p>Una validacion fue rechazada porque no se veia el lugar.</p>
                <span class="status-pill status-rejected">Rechazado</span>
            </article>
        </section>
    </div>
</div>
@endsection
